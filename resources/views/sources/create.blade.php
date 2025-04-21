@extends('layouts.app')

@section('content')
<div class="py-4 m-auto" style="width: 98%">
    <div class="row justify-content-center">
        <div class="col-md-">
            <div class="card">
                <div class="card-header bg-primary text-white">Create New Email Source</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('emails.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Header Textarea and File Upload -->
                        <div class="form-group mb-4">
                            <label for="header_text" class="form-label">Email Source</label>
                            <textarea class="form-control border-primary border-2" id="header_text" name="header_text" rows="15" placeholder="Paste email headers here..."></textarea>
                            <div class="mt-2">
                                <label for="email_file" class="form-label text-primary">Or upload Email Source file:</label>
                                <input type="file" id="email_file" name="email_file" class="form-control border p-2 rounded" accept=".eml,.txt,.msg" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mb-4 justify-content-between">
                            <div class="col-md-8">
                                <button type="button" onclick="parseHeaders()" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-filter me-2"></i>Filter & Parse Headers
                                </button>
                            </div>
                            <div class="col-md-4 mt-2 mt-md-0">
                                <button type="button" onclick="resetFields()" class="btn btn-danger btn-lg w-100">
                                    <i class="fas fa-trash-alt me-2"></i>Clear All
                                </button>
                            </div>
                        </div>

                        <!-- Parsed Results Display -->
                        <div class="alert alert-info mb-4" id="parseResults" style="display:none;">
                            <h5 class="alert-heading">Parsed Results</h5>
                            <pre id="resultDisplay" class="mb-0"></pre>
                        </div>

                        <!-- Form Fields in Two Columns -->
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ip" class="form-label">IP</label>
                                    <input type="text" class="form-control" id="ip" name="ip">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="provider_ip" class="form-label">Provider IP</label>
                                    <input type="text" class="form-control" id="provider_ip" name="provider_ip">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="vmta" class="form-label">VMTA</label>
                                    <input type="text" class="form-control" id="vmta" name="vmta">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="from" class="form-label">From</label>
                                    <input type="text" class="form-control" id="from" name="from">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="return_path" class="form-label">Return Path</label>
                                    <input type="text" class="form-control" id="return_path" name="return_path">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="domains" class="form-label">Domains</label>
                                    <textarea class="form-control" id="domains" name="domains" rows="10"></textarea>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6 row">
                                <div class="form-group mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" class="form-control" id="date" name="date">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="colonne" class="form-label">Colonne</label>
                                    <input type="text" class="form-control" id="colonne" name="colonne">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="redirect_link" class="form-label">Redirect Link</label>
                                    <input type="text" class="form-control" id="redirect_link" name="redirect_link">
                                </div>


                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="spf" class="form-label">SPF</label>
                                    <select class="form-select" id="spf" name="spf">
                                        <option value="pass">pass</option>
                                        <option value="fail">fail</option>
                                        <option value="softfail">softfail</option>
                                        <option value="neutral">neutral</option>
                                        <option value="none">none</option>
                                        <option value="permerror">permerror</option>
                                        <option value="temperror">temperror</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="dkim" class="form-label">DKIM</label>
                                    <select class="form-select" id="dkim" name="dkim">
                                        <option value="pass">pass</option>
                                        <option value="fail">fail</option>
                                        <option value="policy">policy</option>
                                        <option value="none">none</option>
                                        <option value="permerror">permerror</option>
                                        <option value="temperror">temperror</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="dmarc" class="form-label">DMARC</label>
                                    <select class="form-select" id="dmarc" name="dmarc">
                                        <option value="pass">pass</option>
                                        <option value="fail">fail</option>
                                        <option value="permerror">permerror</option>
                                        <option value="temperror">temperror</option>
                                        <option value="none">none</option>
                                        <option value="bestguesspass">bestguesspass</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="message_path" class="form-label">Message Path</label>
                                    <select class="form-select" id="message_path" name="message_path">
                                        <option value="inbox">Inbox</option>
                                        <option value="spam">Spam</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Text Areas -->
                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="header_display" class="form-label">Email Header</label>
                                    <textarea class="form-control" id="header_display" name="header" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="body" class="form-label">Email Body</label>
                                    <textarea class="form-control" id="body" name="body" rows="10"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-save me-2"></i>Save Email Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // DOM Elements
    const headerTextarea = document.getElementById('header_text');
    const fileInput = document.getElementById('email_file');
    const parseResultsDiv = document.getElementById('parseResults');
    const resultDisplay = document.getElementById('resultDisplay');
    
    // Known TLDs for domain extraction
    const knownTlds = ["com", "org", "net", "edu", "gov", "co", "io", "uk"]; // Simplified for example

    // File Upload Handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            headerTextarea.value = e.target.result;
            parseHeaders(); // Auto-parse after upload
        };
        reader.readAsText(file);
    });

    // Main Parsing Function
    async function parseHeaders() {
        const emailSource = headerTextarea.value;
        if (!emailSource.trim()) {
            alert('Please paste email headers or upload a file first');
            return;
        }

        try {
            // Show loading state
            resultDisplay.textContent = "Parsing headers...";
            parseResultsDiv.style.display = 'block';

            // Extract authentication results
            const authResults = extractAuthResults(emailSource);
            
            // Extract domains
            const allDomains = extractAllDomains(emailSource);
            
            // Extract email body
            const emailBody = extractEmailBody(emailSource);
            
            // Get ISP information
            let providerIp = "Not available";
            if (authResults.client_ip) {
                providerIp = await fetchISP(authResults.client_ip);
            }

            // Update form fields
            document.getElementById('ip').value = authResults.client_ip || '';
            document.getElementById('provider_ip').value = providerIp;
            document.getElementById('vmta').value = authResults.helo || '';
            document.getElementById('from').value = extractFrom(emailSource);
            document.getElementById('return_path').value = extractReturnPath(emailSource) || '';
            document.getElementById('date').value = authResults.date || '';
            document.getElementById('email').value = authResults.email || '';
            
            // Set select fields
            document.getElementById('spf').value = authResults.spf?.toLowerCase() || 'pass';
            document.getElementById('dkim').value = authResults.dkim?.toLowerCase() || 'pass';
            document.getElementById('dmarc').value = authResults.dmarc?.toLowerCase() || 'pass';
            
            // Set message path
            if (authResults.message_path) {
                const path = authResults.message_path.split(' => ')[1];
                document.getElementById('message_path').value = path || 'inbox';
            }

            // Set domains textarea with all found domains
            document.getElementById('domains').value = allDomains.join("\n");
            
            // Set email body
            document.getElementById('body').value = emailBody || '';
            document.getElementById('header_display').value = emailSource;

            // Display results
            const resultText = `IP: ${authResults.client_ip || 'N/A'}
Provider: ${providerIp}
VMTA: ${authResults.helo || 'N/A'}
SPF: ${authResults.spf || 'N/A'}
DKIM: ${authResults.dkim || 'N/A'}
DMARC: ${authResults.dmarc || 'N/A'}
Date: ${authResults.date || 'N/A'}
To: ${authResults.email || 'N/A'}
Message Path: ${authResults.message_path || 'N/A'}
`;

            resultDisplay.textContent = resultText;

        } catch (error) {
            resultDisplay.textContent = `Error parsing headers: ${error.message}`;
            console.error(error);
        }
    }

    // Improved Return-Path extraction
    function extractReturnPath(headers) {
        // Try different patterns for Return-Path
        const patterns = [
            /Return-Path:\s*<?([^>\s]+)>?/i,
            /Return-path:\s*<?([^>\s]+)>?/i,
            /Envelope-Return:\s*<?([^>\s]+)>?/i,
            /X-Original-Return-Path:\s*<?([^>\s]+)>?/i
        ];

        for (const pattern of patterns) {
            const match = headers.match(pattern);
            if (match && match[1]) {
                // Clean up the return path
                let path = match[1].trim();
                
                // Remove angle brackets if present
                path = path.replace(/^</, '').replace(/>$/, '');
                
                // Extract domain if it's an email address
                if (path.includes('@')) {
                    return path.split('@')[1];
                }
                return path;
            }
        }
        
        // Fallback to looking for Received headers
        const receivedHeaders = headers.match(/Received:.*?from\s+[^\s]+\s+\(([^)]+)\)/gi);
        if (receivedHeaders) {
            for (const header of receivedHeaders) {
                const domainMatch = header.match(/\(([^)]+)\)/);
                if (domainMatch && domainMatch[1]) {
                    return domainMatch[1].trim();
                }
            }
        }
        
        return null;
    }

    function extractAuthResults(emailHeaders) {
        const authResultsMatch = emailHeaders.match(/Authentication-Results:.*?spf=(\w+).*?dkim=(\w+).*?dmarc=(\w+)/s);
        const clientIpMatch = emailHeaders.match(/client-ip=([\d\.]+)/);
        const heloMatch = emailHeaders.match(/helo=([\w\.-]+)/);
        const dateMatch = emailHeaders.match(/(?:^|\n)Date: (.+?)\r?\n/);
        const toMatch = emailHeaders.match(/(?:^|\n)To: (.+?)\r?\n/);
        const sclMatch = emailHeaders.match(/X-MS-Exchange-Organization-SCL: (.*?)\r?\n/);

        const sclMapping = {
            "-1": "inbox",
            1: "inbox",
            2: "inbox",
            3: "inbox",
            4: "spam",
            5: "spam",
            6: "spam",
            7: "spam",
            8: "spam",
            9: "spam",
        };
        
        const sclResult = sclMatch ? `${sclMatch[1]} => ${sclMapping[sclMatch[1]]}` : null;
        
        return {
            spf: authResultsMatch ? authResultsMatch[1] : null,
            dkim: authResultsMatch ? authResultsMatch[2] : null,
            dmarc: authResultsMatch ? authResultsMatch[3] : null,
            client_ip: clientIpMatch ? clientIpMatch[1] : null,
            helo: heloMatch ? heloMatch[1] : null,
            date: dateMatch ? dateMatch[1] : null,
            email: toMatch ? toMatch[1] : null,
            message_path: sclResult,
        };
    }

    function extractDomainFromHeaders(headers, field) {
        const regex = new RegExp(`(?:^|\\n)${field}:.*?@([^\\s>]+)`);
        const match = headers.match(regex);
        return match ? match[1] : 'Not Found';
    }

    function extractFrom(headers) {
        const match = headers.match(/From:.*?<(.+?)>/);
        return match ? match[1] : headers.match(/From:\s*(.+)/)?.[1] || '';
    }

    function extractDomainFromMessageId(headers) {
        const match = headers.match(/Message-ID:.*?@([^>]+)/i) || 
                     headers.match(/Message-Id:.*?@([^>]+)/i);
        return match ? match[1] : 'Not Found';
    }

    function extractAllDomains(source) {
        const domainRegex = new RegExp(`\\b[a-zA-Z0-9.-]+\\.(${knownTlds.join("|")})\\b`, "gi");
        const domains = source.match(domainRegex) || [];
        
        const emailRegex = /\b[A-Za-z0-9._%+-]+@([A-Za-z0-9.-]+\.[A-Za-z]{2,})\b/gi;
        let emailMatches;
        const emailDomains = [];
        
        while ((emailMatches = emailRegex.exec(source)) !== null) {
            emailDomains.push(emailMatches[1]);
        }
        
        return [...new Set([...domains, ...emailDomains])];
    }

    function extractEmailBody(source) {
        try {
            const parts = source.split("MIME-Version: 1.0");
            if (parts.length > 1) {
                let body = parts[1].trim();
                const headerEnd = body.indexOf("\n\n");
                if (headerEnd > -1) {
                    body = body.substring(headerEnd).trim();
                }
                
                const filterKeys = [
                    'Content-Type', 'Content-Transfer-Encoding', 
                    'Content-Disposition', 'Content-ID', 'Content-Description'
                ];
                
                filterKeys.forEach(key => {
                    const regex = new RegExp(`^${key}:.*?\\n`, 'gmi');
                    body = body.replace(regex, '');
                });
                
                return body;
            }
            return source;
        } catch (e) {
            console.error("Error extracting email body:", e);
            return "Could not extract email body";
        }
    }

    async function fetchISP(ip) {
        try {
            const response = await fetch(`https://ipapi.co/${ip}/json/`);
            const data = await response.json();
            if (data.error) throw new Error(data.reason);
            return data.org || data.asn || 'Unknown ISP';
        } catch (error) {
            console.error('ISP lookup failed:', error);
            return 'ISP lookup failed';
        }
    }

    function resetFields() {
        const form = document.querySelector('form');
        form.reset();
        document.getElementById('header_text').value = '';
        document.getElementById('body').value = '';
        document.getElementById('domains').value = '';
        document.getElementById('header_display').value = '';
        parseResultsDiv.style.display = 'none';
        fileInput.value = '';
    }
</script>

<style>
    textarea.form-control {
        font-family: monospace;
        font-size: 0.9rem;
    }
    #resultDisplay {
        white-space: pre-wrap;
        word-wrap: break-word;
        font-family: monospace;
    }
</style>
@endsection