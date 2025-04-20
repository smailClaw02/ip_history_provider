@extends('layouts.app')

@section('title', 'SPF & DMARC Lookup Tool')

@section('content')
<div class="container-fluid py-4">
    <div class="card border  ">
        <div class="card-header bg-teal">
            <h2 class="mb-0 text-center">SPF & DMARC Lookup Tool</h2>
        </div>
        
        <div class="card-body">
            <div class="row">
                <!-- Left Column - Input -->
                <div class="col-md-5 border-end border-teal pe-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-teal">Enter Domains</h4>
                        <div class="badge bg-secondary fs-6">
                            Checked <span id="domainCount">0</span>/<span id="totalCount">0</span>
                        </div>
                    </div>
                    
                    <textarea id="domains" class="form-control border  text-success mb-3" rows="6" 
                        placeholder="Enter domains (one per line)"></textarea>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <input type="file" id="fileInput" class="form-control border  " accept=".txt,.csv">
                        </div>
                        <div class="col-md-4">
                            <button id="lookupBtn" class="btn btn-success w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-teal">SPF Mechanisms to Filter</label>
                        <input type="text" id="spfFilters" class="form-control border  " 
                            value="" placeholder="e.g. exists:%,+all,include:">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-teal">Filtered Results</label>
                        <textarea id="doms_exist" class="form-control border  text-success" rows="5" 
                            placeholder="Matching domains will appear here"></textarea>
                    </div>
                    
                    <div id="completionMessage" class="text-center"></div>
                </div>
                
                <!-- Right Column - Results -->
                <div class="col-md-7 ps-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-teal">Lookup Results</h4>
                        <div class="d-flex gap-2">
                            <input type="text" id="name_file" class="form-control border  " 
                                placeholder="Filename for export">
                            <button id="exportBtn" class="col-auto btn btn-primary">
                                <i class="fas fa-file-export me-1"></i> Export
                            </button>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="border  p-2 rounded text-center">
                                <small class="text-muted">Start Time</small>
                                <div id="time_start" class="fw-bold">--:--:--</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border  p-2 rounded text-center">
                                <small class="text-muted">End Time</small>
                                <div id="time_fin" class="fw-bold">--:--:--</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border  p-2 rounded text-center">
                                <small class="text-muted">Duration</small>
                                <div id="seconds" class="fw-bold">-- min, -- s</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-dark table-bordered">
                            <thead class="bg-teal">
                                <tr>
                                    <th>Domain</th>
                                    <th>Record Type</th>
                                    <th>Record</th>
                                </tr>
                            </thead>
                            <tbody id="resultBody">
                                <!-- Results will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM elements
        const fileInput = document.getElementById('fileInput');
        const lookupBtn = document.getElementById('lookupBtn');
        const exportBtn = document.getElementById('exportBtn');
        const domainsTextarea = document.getElementById('domains');
        const resultBody = document.getElementById('resultBody');
        const domsExist = document.getElementById('doms_exist');
        const completionMessage = document.getElementById('completionMessage');
        
        // File upload handler
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                domainsTextarea.value = e.target.result.trim();
                document.getElementById('name_file').value = file.name.replace(/\..*$/, '');
            };
            reader.readAsText(file);
        });
        
        // Lookup function
        async function performLookup() {
            const domainsInput = domainsTextarea.value.trim();
            if (!domainsInput) {
                alert("Please enter or upload domains first");
                return;
            }
            
            const domains = domainsInput.split("\n").map(d => d.trim()).filter(d => d);
            const spfFilters = document.getElementById('spfFilters').value.split(',').map(f => f.trim());
            
            // Reset UI
            document.getElementById('totalCount').textContent = domains.length;
            document.getElementById('domainCount').textContent = '0';
            resultBody.innerHTML = '<tr><td colspan="3" class="text-center"><div class="loader"></div></td></tr>';
            completionMessage.textContent = '';
            domsExist.value = '';
            
            // Record start time
            const startTime = performance.now();
            document.getElementById('time_start').textContent = new Date().toLocaleTimeString();
            
            try {
                let resultsHTML = '';
                let filteredDomains = '';
                let processedCount = 0;
                
                for (const domain of domains) {
                    try {
                        const [spfRecord, dmarcRecord] = await Promise.all([
                            getDNSRecord(domain, 'TXT'),
                            getDNSRecord(`_dmarc.${domain}`, 'TXT')
                        ]);
                        
                        const spf = spfRecord.filter(r => r.includes('v=spf1')).join('\n') || 'No SPF record';
                        const dmarc = dmarcRecord.filter(r => r.includes('v=DMARC1')).join('\n') || 'No DMARC record';
                        
                        // Check if SPF matches any filter
                        const shouldInclude = spfFilters.some(filter => spf.includes(filter));
                        
                        if (shouldInclude) {
                            filteredDomains += `${domain}\n`;
                            
                            resultsHTML += `
                                <tr>
                                    <td rowspan="2" class="text-success fw-bold">${domain}</td>
                                    <td class="text-info">SPF</td>
                                    <td><pre class="text-white">${spf}</pre></td>
                                </tr>
                                <tr>
                                    <td class="text-info">DMARC</td>
                                    <td><pre class="text-white">${dmarc}</pre></td>
                                </tr>
                            `;
                        }
                        
                        processedCount++;
                        document.getElementById('domainCount').textContent = processedCount;
                        domsExist.value = filteredDomains;
                        resultBody.innerHTML = resultsHTML || '<tr><td colspan="3" class="text-center text-warning">No matching records found</td></tr>';
                    } catch (error) {
                        console.error(`Error processing ${domain}:`, error);
                    }
                }
                
                // Show completion message
                completionMessage.textContent = 'Lookup completed successfully!';
                completionMessage.className = 'alert alert-success mt-3';
            } catch (error) {
                console.error('Lookup failed:', error);
                completionMessage.textContent = 'Error during lookup!';
                completionMessage.className = 'alert alert-danger mt-3';
            } finally {
                // Record finish time
                const finishTime = performance.now();
                document.getElementById('time_fin').textContent = new Date().toLocaleTimeString();
                
                const seconds = (finishTime - startTime) / 1000;
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                document.getElementById('seconds').textContent = `${minutes} min, ${remainingSeconds} s`;
            }
        }
        
        // DNS lookup function
        async function getDNSRecord(domain, recordType) {
            const response = await fetch(`https://dns.google/resolve?name=${encodeURIComponent(domain)}&type=${recordType}`);
            const data = await response.json();
            return data.Answer ? data.Answer.map(r => r.data) : [];
        }
        
        // Export to Excel
        function exportToExcel() {
            const table = document.querySelector('table');
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Results');
            
            const filename = document.getElementById('name_file').value || 'spf_dmarc_results';
            XLSX.writeFile(wb, `${filename}.xlsx`);
        }
        
        // Event listeners
        lookupBtn.addEventListener('click', performLookup);
        exportBtn.addEventListener('click', exportToExcel);
    });
</script>

<style>
    .bg-teal {
        background-color: #008080 !important;
    }
    .text-teal {
        color: #20c997 !important;
    }
    .border-teal {
        border-color: #20c997 !important;
    }
    .loader {
        width: 60px;
        height: 40px;
        position: relative;
        display: inline-block;
        --base-color: #20c997;
    }
    .loader::before {
        content: '';
        left: 0;
        top: 0;
        position: absolute;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #343a40;
        background-image: radial-gradient(circle 8px at 18px 18px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 18px 0px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 0px 18px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 36px 18px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 18px 36px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 30px 5px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 30px 30px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 5px 30px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 4px at 5px 5px, var(--base-color) 100%, transparent 0);
        background-repeat: no-repeat;
        box-sizing: border-box;
        animation: rotationBack 3s linear infinite;
    }
    .loader::after {
        content: '';
        left: 35px;
        top: 15px;
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: #343a40;
        background-image: radial-gradient(circle 5px at 12px 12px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 12px 0px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 0px 12px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 24px 12px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 12px 24px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 20px 3px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 20px 20px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 3px 20px, var(--base-color) 100%, transparent 0), 
                          radial-gradient(circle 2.5px at 3px 3px, var(--base-color) 100%, transparent 0);
        background-repeat: no-repeat;
        box-sizing: border-box;
        animation: rotationBack 4s linear infinite reverse;
    }
    @keyframes rotationBack {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(-360deg); }
    }
    pre {
        white-space: pre-wrap;
        margin: 0;
        font-family: inherit;
        font-size: inherit;
    }
    .table-dark {
        background-color: #343a40;
    }
    .table-dark td, .table-dark th {
        border-color: #495057;
    }
</style>
@endsection