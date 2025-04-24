@extends('layouts.app')

@section('title', 'Domain Filter Tool')

@section('content')
<div class="py-4 m-auto" style="width: 98%">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0 text-center">Domain Filter Tool</h2>
        </div>
        
        <div class="card-body">
            <!-- Input Section -->
            <div class="mb-4 pb-3 border-bottom">
                <div class="mb-3">
                    <label for="inputText" class="form-label fw-bold">Email Body Content</label>
                    <textarea id="inputText" class="form-control  " rows="8" 
                        placeholder="Paste your email body content here..."></textarea>
                </div>
                
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="text_change" class="form-label fw-bold">Text to Replace</label>
                        <input type="text" class="form-control" id="text_change" 
                            placeholder="Text you want to replace">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="change" class="form-label fw-bold">Replacement Text</label>
                        <input type="text" class="form-control" id="change" 
                            placeholder="New replacement text">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="r_dkim" class="form-label fw-bold">Domain Replacement</label>
                        <input type="text" class="form-control" id="r_dkim" 
                            value="[R_dkim]" placeholder="e.g. [R_dkim]">
                    </div>
                    
                    <div class="col-md-2">
                        <button id="filterBtn" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Results Section -->
            <div class="row">
                <!-- Processed Body -->
                <div class="col-12 mb-3">
                    <label for="outputText" class="form-label fw-bold">Processed Body</label>
                    <small class="text-muted">Default: mailto: → mail, All Domains → [R_dkim]</small>
                    <textarea id="outputText" class="form-control border-success" rows="10" 
                        placeholder="Processed content will appear here..."></textarea>
                </div>
                
                <!-- Found Domains -->
                <div class="col-md-4 mb-3">
                    <label for="foundDomains" class="form-label fw-bold">Extracted Domains</label>
                    <textarea id="foundDomains" class="form-control border-success" rows="10" 
                        placeholder="Found domains will appear here..." readonly></textarea>
                </div>
                
                <!-- DNS Records -->
                <div class="col-md-8 mb-3">
                    <label for="foundSPFDMARC" class="form-label fw-bold">DNS Records Lookup</label>
                    <textarea id="foundSPFDMARC" class="form-control border-success" rows="10" 
                        placeholder="SPF and DMARC records will appear here..." readonly></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtn = document.getElementById('filterBtn');
        const knownTlds = ["com", "org", "info", "edu", "gov", "net", "co", "io", "uk", "jp", "au", "ca", "de", "fr", "it", "es", "cn", "in", "br", "ru", "nl", "se", "dk", "no", "fi", "ch", "at", "be", "pl", "ie", "nz", "sg", "kr", "tw", "hk", "my", "za", "il", "mx", "tr", "id", "th", "vn", "ph", "gr", "cz", "hu", "pt", "ro", "sk", "si", "bg", "hr", "lt", "lv", "ee", "is", "li", "lu", "mc", "mt", "cy", "sm", "va", "ad", "ae", "af", "al", "am", "ao", "ar", "az", "ba", "bd", "bf", "bh", "bi", "bj", "bn", "bo", "bw", "by", "bz", "cd", "cf", "cg", "cl", "cm", "co", "cr", "cu", "cv", "cy", "cz", "dj", "dk", "dm", "do", "dz", "ec", "ee", "eg", "er", "et", "fj", "fm", "ga", "ge", "gg", "gh", "gi", "gl", "gm", "gn", "gp", "gq", "gr", "gt", "gu", "gw", "gy", "hk", "hn", "hr", "ht", "hu", "id", "ie", "il", "in", "iq", "ir", "is", "it", "je", "jm", "jo", "jp", "ke", "kg", "kh", "ki", "km", "kn", "kp", "kr", "kw", "kz", "la", "lb", "lc", "li", "lk", "lr", "ls", "lt", "lu", "lv", "ly", "ma", "mc", "md", "me", "mg", "mh", "mk", "ml", "mm", "mn", "mo", "mp", "mq", "mr", "ms", "mt", "mu", "mv", "mw", "mx", "my", "mz", "na", "nc", "ne", "nf", "ng", "ni", "nl", "no", "np", "nr", "nu", "nz", "om", "pa", "pe", "pf", "pg", "ph", "pk", "pl", "pm", "pn", "pr", "ps", "pt", "pw", "py", "qa", "re", "ro", "rs", "ru", "rw", "sa", "sb", "sc", "sd", "se", "sg", "sh", "si", "sk", "sl", "sm", "sn", "so", "sr", "ss", "st", "sv", "sx", "sy", "sz", "tc", "td", "tg", "th", "tj", "tk", "tl", "tm", "tn", "to", "tr", "tt", "tv", "tw", "tz", "ua", "ug", "uk", "us", "uy", "uz", "va", "vc", "ve", "vg", "vi", "vn", "vu", "wf", "ws", "ye", "yt", "za", "zm", "zw"];
        
        // DNS Lookup Functions
        async function getDNSRecord(domain, recordType) {
            try {
                const response = await fetch(`https://dns.google/resolve?name=${encodeURIComponent(domain)}&type=${recordType}`);
                const data = await response.json();
                return data.Answer ? data.Answer.map(record => record.data) : [];
            } catch (error) {
                console.error('DNS lookup failed:', error);
                return [];
            }
        }
        
        async function lookupDomains(domainsArray) {
            if (!domainsArray || domainsArray.length === 0) return [];
            
            const results = [];
            
            for (const domain of domainsArray) {
                try {
                    const cleanDomain = domain.replace(/^www\./, '');
                    const [spfRecords, dmarcRecords] = await Promise.all([
                        getDNSRecord(cleanDomain, "TXT"),
                        getDNSRecord(`_dmarc.${cleanDomain}`, "TXT")
                    ]);
                    
                    const spf = spfRecords.filter(r => r.includes("v=spf1")).join("\n") || "No SPF record";
                    const dmarc = dmarcRecords.filter(r => r.includes("v=DMARC1")).join("\n") || "No DMARC record";
                    
                    results.push({ domain: cleanDomain, spf, dmarc });
                } catch (error) {
                    console.error(`Error processing ${domain}:`, error);
                    results.push({ domain, spf: "Lookup failed", dmarc: "Lookup failed" });
                }
            }
            
            return results;
        }
        
        // Main Filter Function
        async function filterContent() {
            const inputText = document.getElementById("inputText").value;
            const textToReplace = document.getElementById("text_change").value;
            const replacementText = document.getElementById("change").value;
            const domainReplacement = document.getElementById("r_dkim").value;
            
            if (!inputText) {
                alert("Please enter some text to process");
                return;
            }
            
            // Initial processing
            let processedText = inputText;
            
            // Replace specified text if provided
            if (textToReplace) {
                const regex = new RegExp(textToReplace, "gi");
                processedText = processedText.replace(regex, replacementText);
            }
            
            // Always replace mailto: with mail
            processedText = processedText.replace(/mailto:/gi, "mail");
            
            // Extract domains
            const domainRegex = new RegExp(`\\b[a-zA-Z0-9.-]+\\.(${knownTlds.join("|")})\\b`, "gi");
            const foundDomains = [...new Set(inputText.match(domainRegex) || [])];
            
            // Replace domains if replacement specified
            if (domainReplacement) {
                processedText = processedText.replace(domainRegex, domainReplacement);
            }
            
            // Update output fields
            document.getElementById("outputText").value = processedText;
            document.getElementById("foundDomains").value = foundDomains.join("\n");
            document.getElementById("foundSPFDMARC").value = "Looking up DNS records...";
            
            // Perform DNS lookups
            if (foundDomains.length > 0) {
                try {
                    const results = await lookupDomains(foundDomains);
                    const formattedResults = results.map((result, index) => 
                        `${index + 1}. Domain: ${result.domain}\nSPF:\n${result.spf}\nDMARC:\n${result.dmarc}\n`
                    ).join("\n");
                    
                    document.getElementById("foundSPFDMARC").value = formattedResults;
                } catch (error) {
                    console.error("Domain lookup failed:", error);
                    document.getElementById("foundSPFDMARC").value = "Error fetching DNS records";
                }
            } else {
                document.getElementById("foundSPFDMARC").value = "No domains found in text";
            }
        }
        
        // Event Listeners
        filterBtn.addEventListener('click', filterContent);
    });
</script>

<style>
    textarea {
        font-family: monospace;
    }
    .border-success {
        border-color: #28a745 !important;
    }
    . {
        border-color: #343a40 !important;
    }
</style>
@endsection