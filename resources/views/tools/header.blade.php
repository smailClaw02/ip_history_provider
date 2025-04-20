@extends('layouts.app')

@section('title', 'Header Processor Tool')

@section('content')
    <div class="py-4 m-auto" style="width: 98%">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h2 class="mb-0 text-center"><i class="fas fa-envelope-open-text me-2"></i>Email Header Processor</h2>
            </div>

            <div class="card-body">
                <!-- Main Header Processing Section -->
                <div class="row g-4">
                    <!-- Original Header Input -->
                    <div class="col-lg-6">
                        <div class="card h-100 border-primary">
                            <div
                                class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-paste me-2"></i>Your Source</span>
                                <button id="processBtn" class="btn btn-primary btn-lg px-4 py-2 shadow-sm">
                                    <i class="fas fa-cogs me-2"></i> Process Header
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <textarea id="header" class="form-control border-0 h-100" rows="15"
                                    placeholder="Paste the complete source here..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Domain and Security Info -->
                    <div class="col-lg-6">
                        <div class="card h-100 border-secondary">
                            <div class="card-header bg-secondary text-white fw-bold">
                                <i class="fas fa-shield-alt me-2"></i>Domain & Security
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="result_domains_for_source" class="form-label fw-bold small">
                                        <i class="fas fa-globe me-1"></i>Domains Found
                                    </label>
                                    <textarea id="result_domains_for_source" class="form-control border-2 border-primary" rows="9"></textarea>
                                </div>

                                <div class="mb-3">
                                    <div class="fw-bold d-flex justify-content-between align-items-center mb-2">
                                        <span class="form-label fw-bold small"><i class="fas fa-lock me-1"></i>SPF/DMARC
                                            Records</span>
                                        <button id="checkSpfBtn" class="btn btn-warning btn-sm">
                                            <i class="fas fa-search me-1"></i> Verify DNS Records
                                        </button>
                                    </div>
                                    <textarea id="result_spf_dmarc" class="form-control border-2 border-warning" rows="9"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Results Section -->
                <div class="row mt-1 g-4">
                    <!-- Processed Header Output -->
                    <div class="col-md-6">
                        <div class="card h-100 border-success">
                            <div
                                class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-code me-2"></i>Processed Header</span>
                                <button id="copyProcessedBtn" class="btn btn-sm btn-outline-light">
                                    <i class="fas fa-copy me-1"></i> Copy
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <textarea id="result_header" class="form-control border-0 h-100" rows="11"
                                    placeholder="Processed header will appear here..."></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Original Header -->
                    <div class="col-md-6">
                        <div class="card h-100 border-info">
                            <div
                                class="card-header bg-info text-white fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clipboard-check me-2"></i>Original Header</span>
                                <button id="copyOriginalBtn" class="btn btn-sm btn-outline-light">
                                    <i class="fas fa-copy me-1"></i> Copy
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <textarea id="result_origin" class="form-control border-0 h-100" rows="11"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const processBtn = document.getElementById('processBtn');
            const checkSpfBtn = document.getElementById('checkSpfBtn');
            const copyOriginalBtn = document.getElementById('copyOriginalBtn');
            const copyProcessedBtn = document.getElementById('copyProcessedBtn');

            // Copy functions
            function copyToClipboard(textareaId) {
                const textarea = document.getElementById(textareaId);
                textarea.select();
                document.execCommand('copy');

                // Show temporary feedback
                const btn = textareaId === 'result_origin' ? copyOriginalBtn : copyProcessedBtn;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 2000);
            }

            copyOriginalBtn.addEventListener('click', () => copyToClipboard('result_origin'));
            copyProcessedBtn.addEventListener('click', () => copyToClipboard('result_header'));

            // SPF/DMARC Lookup Functions
            async function lookup(domainsArray) {
                if (!domainsArray || domainsArray.length === 0) {
                    console.log("Please enter at least one domain.");
                    return [];
                }
                const domains = domainsArray.map(d => d.trim()).filter(d => d);

                try {
                    let results = [];

                    for (const domain of domains) {
                        // Fetch SPF and DMARC records
                        const spfRecord = await getDNSRecord(domain, "TXT");
                        const dmarcRecord = await getDNSRecord(`_dmarc.${domain}`, "TXT");

                        // Extract relevant records
                        const spf = spfRecord.filter(record => record.includes("v=spf1")).join("\n") ||
                            "No SPF record found❗";
                        const dmarc = dmarcRecord.filter(record => record.includes("v=DMARC1")).join("\n") ||
                            "No DMARC record found❗";

                        results.push({
                            domain,
                            spf,
                            dmarc
                        });
                    }

                    return results;
                } catch (error) {
                    console.log("An error occurred:", error.message);
                    return [];
                }
            }

            async function getDNSRecord(domain, recordType) {
                const url = `https://dns.google/resolve?name=${domain}&type=${recordType}`;
                const response = await fetch(url);
                const data = await response.json();
                if (data.Status !== 0) {
                    return [];
                }
                return data.Answer ? data.Answer.map(record => record.data) : [];
            }

            // Check SPF and DMARC
            async function checkSpfDmarc() {
                const result_domains_for_source = document.getElementById("result_domains_for_source").value;
                const result_spf_dmarc = document.getElementById("result_spf_dmarc");

                result_spf_dmarc.value = "Loading...⚙";

                // Extract all domains from the textarea (both "Domain: " lines and standalone domains)
                const domains = [...new Set(
                    result_domains_for_source
                    .split('\n')
                    .map(line => line.trim())
                    .filter(line => line)
                    .flatMap(line => {
                        if (line.includes("Domain:")) {
                            return line.split("Domain: ")[1];
                        }
                        // Also match standalone domains in the format "domain.com"
                        const domainMatch = line.match(/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/);
                        return domainMatch ? domainMatch[0] : [];
                    })
                    .filter(domain => domain)
                )];

                if (domains.length === 0) {
                    result_spf_dmarc.value = "No valid domains found to check";
                    return;
                }

                try {
                    const results = await lookup(domains);

                    let lookupResults = results.map((result, id) =>
                        `${id + 1}. Domain: ${result.domain}\nSPF:\n${result.spf}\nDMARC:\n${result.dmarc}\n`
                    ).join("\n");

                    result_spf_dmarc.value = lookupResults || "No results found❗";
                } catch (error) {
                    result_spf_dmarc.value = "Error fetching DNS records";
                    console.error("Domain lookup failed:", error);
                }
            }

            // Extract domain from email
            function extractDomainFromEmail(email) {
                if (!email) return null;
                const match = email.match(/@([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/);
                return match ? match[1] : null;
            }

            // Message-Id processing
            function random_message_id(input) {
                const cleanedInput = input.slice(1, -1);
                const [value, domain] = cleanedInput.split('@');
                const splitValue = value.split(/([._\-=\+\$\%\#])/);

                const transformedArray = splitValue.map((str) => {
                    if (/[._\-=\+\$\%\#]/.test(str)) {
                        return str;
                    }

                    let category = '';
                    if (/^[a-z]+$/.test(str)) category = 'CL';
                    else if (/^[A-Z]+$/.test(str)) category = 'CLU';
                    else if (/^[0-9]+$/.test(str)) category = 'N';
                    else if (/^[a-zA-Z]+$/.test(str)) category = 'C';
                    else if (/^[a-z0-9]+$/.test(str)) category = 'L';
                    else if (/^[A-Z0-9]+$/.test(str)) category = 'LU';
                    else if (/^[a-zA-Z0-9]+$/.test(str)) category = 'A';
                    else category = 'C';

                    return `[Random${category}/${str.length}]`;
                });

                const transformedValue = transformedArray.join('');
                return `<${transformedValue}@${domain}>`;
            }

            // Return-Path processing
            function random_Return_Path(input) {
                const [value, domain] = input.split('@');
                const splitValue = value.split(/([._\-=\+\$\%\#])/);

                const transformedArray = splitValue.map((str) => {
                    if (/[._\-=\+\$\%\#]/.test(str)) {
                        return str;
                    }

                    let category = '';
                    if (/^[a-z]+$/.test(str)) category = 'CL';
                    else if (/^[A-Z]+$/.test(str)) category = 'CLU';
                    else if (/^[0-9]+$/.test(str)) category = 'N';
                    else if (/^[a-zA-Z]+$/.test(str)) category = 'C';
                    else if (/^[a-z0-9]+$/.test(str)) category = 'L';
                    else if (/^[A-Z0-9]+$/.test(str)) category = 'LU';
                    else if (/^[a-zA-Z0-9]+$/.test(str)) category = 'A';
                    else category = 'C';

                    return `[Random${category}/${str.length}]`;
                });

                const transformedValue = transformedArray.join('');
                return [`${transformedValue}@[R_dkim]`, domain];
            }

            // Main processing function
            function processHeader() {
                const headerText = document.getElementById("header").value;
                if (!headerText.trim()) {
                    alert("Please paste an email header to process");
                    return;
                }

                // Process header
                let result = headerText
                    .replace(/Return-Path:\s*(.+)/i, (_, email) => `Return-Path: ${email}`)
                    .replace(/Message-Id:\s*(<.+)/i, (_, email) => `Message-Id: ${email}`)
                    .replace(/Message-ID:\s*(<.+)/i, (_, email) => `Message-ID: ${email}`)
                    .replace(/Content-Type:\s*(<.+)\n(<.+)/i, (_, email_01, email_02) =>
                        `Content-Type: ${email_01} ${email_02}`)
                    .replace(/Content-Type: (.+)\s*(boundary=.*)/i, (_, name, email) => email ?
                        `Content-Type: ${name} ${email}` : `Content-Type: ${name}`)
                    .replace(/\n\s+/g, " ")
                    .replace(/(Received: from .+?)\s+(by .+?)\s+(with .+?)\s+(id .+?)\s+(for .+?);\s+(.+)/gi,
                        (_, part1, part2, part3, part4, part5, part6) =>
                        `${part1}  ${part2}  ${part3}  ${part4}  ${part5}; ${part6}`);

                // Parse headers
                const filterKeys = [
                    'Received', 'Content-Type', 'Return-Path', 'Message-Id', 'Message-ID',
                    'Subject', 'sender', 'Sender', 'From', 'Date', 'CC', 'Cc', 'To'
                ];

                const parseHeaders = header =>
                    header.trim().split('\n').map((line, index) => {
                        const [key, ...valueParts] = line.split(':');
                        return {
                            id: index + 1,
                            key: key.trim(),
                            value: valueParts.join(':').trim()
                        };
                    });

                const headersArray = parseHeaders(result);
                const filterAndSortHeaders = (headers, keys) =>
                    headers.filter(header => keys.includes(header.key))
                    .sort((a, b) => a.key.localeCompare(b.key));

                const filteredAndSortedHeaders = filterAndSortHeaders(headersArray, filterKeys);
                const filteredData = filteredAndSortedHeaders.filter(item =>
                    item.key === "Received" && item.value.includes("from localhost") || item.key !== "Received"
                );

                const rankById = (data) => data.sort((a, b) => a.id - b.id);
                const rankedData = rankById(filteredData);

                let return_path = "";
                let return_path_random = "";
                let domain_from = "";
                let domain_Sender = "";
                let domain_ReturnPath = "";
                let domain_MessageId = "";

                function splitEmail(email) {
                    let [username, domain] = email.split('@');
                    return {
                        username,
                        domain
                    };
                }

                const data_01 = rankedData.map(item => {
                    if (item.key === "Message-Id" || item.key === "Message-ID") {
                        const domain = extractDomainFromEmail(item.value);
                        if (domain) domain_MessageId = domain;
                        return {
                            ...item,
                            value: random_message_id(item.value)
                        }
                    }
                    if (item.key === "Return-Path") {
                        const domain = extractDomainFromEmail(item.value);
                        if (domain) domain_ReturnPath = domain;
                        return_path = item.value;
                        return_path_random = random_Return_Path(item.value)[0];
                        return {
                            ...item,
                            value: return_path_random
                        }
                    }
                    return item;
                });

                function cleanHeaders(headers) {
                    let returnPathIndex = headers.findIndex(item => item.key === "Return-Path");
                    return headers.filter((item, index) =>
                        item.key !== "Content-Type" || index <= returnPathIndex
                    );
                }

                const data = cleanHeaders(data_01);
                const origin_header = rankedData.map(header => `${header.key}: ${header.value}`).join('\n');
                document.getElementById("result_origin").value = origin_header;

                if (data.length > 0) {
                    const resultContent = data.map(header => `${header.key}: ${header.value}`).join('\n');

                    const new_header = resultContent
                        .replace(/From: (.+)<(.+)>/i, (_, name, email) => {
                            let resultv = splitEmail(email);
                            domain_from = resultv.domain;
                            return `From: [RandomC/5] <${random_Return_Path(email)[0]}>`
                        })
                        .replace(/Received:\s+by\s+([\w.-]+)/, 'by [R_host]')
                        .replace(/by\s+([\w.-]+)/, (_, dom) => {
                            if (domain_from === dom) {
                                return 'by [R_dkim]';
                            } else {
                                return 'by [R_host]';
                            }
                        })
                        .replace(/for\s+<([^>]+)>/, 'for <[*to]>')
                        .replace(/;\s*([\w\s,:-]+)\s*\(envelope-from\s+<([^>]+)>\)/,
                            `;[*date] (envelope-from <${return_path_random}>)`)
                        .replace(/\bid\s+([\w]+)\b/, (_, str) => {
                            let category;
                            if (/^[a-z]+$/.test(str)) category = 'CL';
                            else if (/^[A-Z]+$/.test(str)) category = 'CLU';
                            else if (/^[0-9]+$/.test(str)) category = 'N';
                            else if (/^[a-zA-Z]+$/.test(str)) category = 'C';
                            else if (/^[a-z0-9]+$/.test(str)) category = 'L';
                            else if (/^[A-Z0-9]+$/.test(str)) category = 'LU';
                            else if (/^[a-zA-Z0-9]+$/.test(str)) category = 'A';
                            else category = 'C';

                            return `id [Random${category}/${str.length}]`;
                        })
                        .replace(/Sender: (.+)<(.+)>/gi, (_, name, email) => {
                            let resultv = splitEmail(email);
                            domain_Sender = resultv.domain;
                            return `Sender: [RandomC/5] <${random_Return_Path(email)[0]}>`;
                        })
                        .replace(/To: (.*)/, 'To: <[*to]>')
                        .replace(/CC: (.*)/, 'CC: <[*to]>')
                        .replace(/Cc: (.*)/, 'Cc: <[*to]>')
                        .replace(/Subject: (.*)/, 'Subject: Re:[sr]-@@-[ip]---')
                        .replace(/Date: (.*)/, 'Date: [*date]');

                    // Update result fields
                    document.getElementById("result_header").value = new_header;

                    // Collect all found domains
                    const domainsFound = [];
                    if (domain_from) domainsFound.push(`From Domain: ${domain_from}`);
                    if (domain_Sender) domainsFound.push(`Sender Domain: ${domain_Sender}`);
                    if (domain_ReturnPath) domainsFound.push(`Return-Path Domain: ${domain_ReturnPath}`);
                    if (domain_MessageId) domainsFound.push(`Message-ID Domain: ${domain_MessageId}`);

                    // Display domains or fallback to simple domain if no specific domains found
                    if (domainsFound.length > 0) {
                        document.getElementById("result_domains_for_source").value = domainsFound.join('\n');
                    } else {
                        // Try to extract any domain from the header as fallback
                        const fallbackDomain = extractDomainFromEmail(headerText);
                        if (fallbackDomain) {
                            document.getElementById("result_domains_for_source").value = fallbackDomain;
                        } else {
                            document.getElementById("result_domains_for_source").value = "No domains found";
                        }
                    }

                    document.getElementById("result_spf_dmarc").value = "Click 'Check SPF/DMARC' to verify records";
                }
            }

            // Event listeners
            processBtn.addEventListener('click', processHeader);
            checkSpfBtn.addEventListener('click', checkSpfDmarc);
        });
    </script>

    <style>
        textarea {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 0.85rem;
            line-height: 1.4;
            resize: none;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
        }

        .border-2 {
            border-width: 2px !important;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            letter-spacing: 0.5px;
        }

        #processBtn {
            transition: all 0.3s ease;
            border: 1px solid;
            color: white;
            background: linear-gradient(135deg, #3a7bd5 0%, #01b6df 100%);
        }

        #processBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #checkSpfBtn {
            transition: all 0.2s ease;
        }

        #checkSpfBtn:hover {
            background-color: #ffc107;
            color: #212529;
        }

        #copyOriginalBtn,
        #copyProcessedBtn {
            transition: all 0.2s ease;
        }

        #copyOriginalBtn:hover,
        #copyProcessedBtn:hover {
            transform: scale(1.05);
        }
    </style>
@endsection
