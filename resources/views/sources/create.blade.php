@extends('layouts.app')

@section('content')
    <div class="py-4 m-auto" style="width: 98%">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">Create New Email Source</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('sources.store') }}" enctype="multipart/form-data"
                            id="emailSourceForm">
                            @csrf

                            <!-- Header Textarea and File Upload -->
                            <div class="form-group mb-4">
                                <label for="header_text" class="form-label">Email Source</label>
                                <textarea class="form-control border-primary border-2" id="header_text" name="header_text" rows="15"
                                    placeholder="Paste email headers here..."></textarea>
                                <div class="mt-2">
                                    <label for="email_file" class="form-label text-primary">Or upload Email Source
                                        file:</label>
                                    <input type="file" id="email_file" name="email_file"
                                        class="form-control border p-2 rounded" accept=".eml,.txt,.msg" />
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
                            <div class="row justify-content-around">
                            	<div class="col-md-5 alert alert-info mb-4" id="parseResults" style="display:none;">
                                	<h5 class="alert-heading">Parsed Results</h5>
                                	<pre id="resultDisplay" class="mb-0"></pre>
                            	</div>

                            	<!-- Parsed Results Display -->
                            	<div class="col-md-5 alert alert-warning mb-4" id="parseResults" style="display:none;">
                                	<h5 class="alert-heading">Parsed DKIM-Signature:</h5>
                                	<pre id="resultDisplayH" class="mb-0"></pre>
                            	</div>
                            </div>

                            <!-- Form Fields in Two Columns -->
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ip" class="form-label">IP</label>
                                        <input type="text" class="form-control" id="ip" name="ip" required>
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
                                        <input type="text" class="form-control" id="from" name="from" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="return_path" class="form-label">Return Path</label>
                                        <input type="text" class="form-control" id="return_path" name="return_path"
                                            required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="domains" class="form-label">Domains</label>
                                        <textarea class="form-control" id="domains" name="domains" rows="3" required></textarea>
                                        <small class="text-muted">Automatically formatted as JSON array. Example:
                                            ["example.com","test.org"]</small>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6 row">
                                    <div class="form-group mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="datetime-local" class="form-control" id="date" name="date"
                                            required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="colonne" class="form-label">Colonne</label>
                                        <input type="text" class="form-control" id="colonne" name="colonne">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="redirect_link" class="form-label">Redirect Link</label>
                                        <input type="text" class="form-control" id="redirect_link"
                                            name="redirect_link">
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="spf" class="form-label">SPF</label>
                                            <select class="form-select" id="spf" name="spf" required>
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
                                            <select class="form-select" id="dkim" name="dkim" required>
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
                                            <select class="form-select" id="dmarc" name="dmarc" required>
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
                                            <select class="form-select" id="message_path" name="message_path" required>
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
                                            <textarea class="form-control" id="header_display" name="header" rows="10" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="body" class="form-label">Email Body</label>
                                            <textarea class="form-control" id="body" name="body" rows="10" required></textarea>
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
        function showDate(date) {
            const dates = new Date(date);
            // Format to YYYY-MM-DDTHH:MM
            const formatted = dates.toISOString().slice(0, 16);
            return formatted;
        }

        // DOM Elements
        const headerTextarea = document.getElementById('header_text');
        const fileInput = document.getElementById('email_file');
        const form = document.getElementById('emailSourceForm');

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

        // Known TLDs for domain validation
        const knownTlds = ["com", "org", "info", "edu", "gov", "net", "co", "io", "uk", "jp", "au", "ca", "de", "fr", "it",
            "es", "cn", "in", "br", "ru", "nl", "se", "dk", "no", "fi", "ch", "at", "be", "pl", "ie", "nz", "sg", "kr",
            "tw", "hk", "my", "za", "il", "mx", "tr", "id", "th", "vn", "ph", "gr", "cz", "hu", "pt", "ro", "sk", "si",
            "bg", "hr", "lt", "lv", "ee", "is", "li", "lu", "mc", "mt", "cy", "sm", "va", "ad", "ae", "af", "al", "am",
            "ao", "ar", "az", "ba", "bd", "bf", "bh", "bi", "bj", "bn", "bo", "bw", "by", "bz", "cd", "cf", "cg", "cl",
            "cm", "co", "cr", "cu", "cv", "cy", "cz", "dj", "dk", "dm", "do", "dz", "ec", "ee", "eg", "er", "et", "fj",
            "fm", "ga", "ge", "gg", "gh", "gi", "gl", "gm", "gn", "gp", "gq", "gr", "gt", "gu", "gw", "gy", "hk", "hn",
            "hr", "ht", "hu", "id", "ie", "il", "in", "iq", "ir", "is", "it", "je", "jm", "jo", "jp", "ke", "kg", "kh",
            "ki", "km", "kn", "kp", "kr", "kw", "kz", "la", "lb", "lc", "li", "lk", "lr", "ls", "lt", "lu", "lv", "ly",
            "ma", "mc", "md", "me", "mg", "mh", "mk", "ml", "mm", "mn", "mo", "mp", "mq", "mr", "ms", "mt", "mu", "mv",
            "mw", "mx", "my", "mz", "na", "nc", "ne", "nf", "ng", "ni", "nl", "no", "np", "nr", "nu", "nz", "om", "pa",
            "pe", "pf", "pg", "ph", "pk", "pl", "pm", "pn", "pr", "ps", "pt", "pw", "py", "qa", "re", "ro", "rs", "ru",
            "rw", "sa", "sb", "sc", "sd", "se", "sg", "sh", "si", "sk", "sl", "sm", "sn", "so", "sr", "ss", "st", "sv",
            "sx", "sy", "sz", "tc", "td", "tg", "th", "tj", "tk", "tl", "tm", "tn", "to", "tr", "tt", "tv", "tw", "tz",
            "ua", "ug", "uk", "us", "uy", "uz", "va", "vc", "ve", "vg", "vi", "vn", "vu", "wf", "ws", "ye", "yt", "za",
            "zm", "zw"
        ];

        // ISP Lookup Function
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

        // Domain Lookup Function
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

                    results.push({
                        domain: cleanDomain,
                        spf,
                        dmarc
                    });
                } catch (error) {
                    console.error(`Error processing ${domain}:`, error);
                    results.push({
                        domain,
                        spf: "Lookup failed",
                        dmarc: "Lookup failed"
                    });
                }
            }

            return results;
        }

        // Helper function to extract domains from text
        function extractDomainsFromText(text) {
            const domainRegex = /\b(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9-]+\.(?:[a-zA-Z0-9-]+\.)*[a-zA-Z]{2,})\b/g;
            const domains = new Set();
            let match;

            while ((match = domainRegex.exec(text)) !== null) {
                const domain = match[1].toLowerCase();
                const tld = domain.split('.').pop();
                if (knownTlds.includes(tld)) {
                    domains.add(domain);
                }
            }

            return Array.from(domains);
        }

        function extractEmail_01(input) {
            const match = input.match(/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/);
            return match ? match[0] : 'not found email';
        }

        // Main Processing Function
        let messagePathValue = "";
        async function parseHeaders() {
            const headerText = document.getElementById("header_text").value;
            if (!headerText.trim()) {
                alert("Please paste an email header to process");
                return;
            }

            // Show loading state
            document.getElementById("resultDisplay").textContent = "Parsing History...";
            document.getElementById("resultDisplayH").textContent = "Parsing DKIM-Signature:...";
            document.getElementById("parseResults").style.display = 'block';

            // Process header (keep this for internal processing)
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

            // Parse headers (internal processing)
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
            let domain_from = "";
            let domain_Sender = "";
            let domain_ReturnPath = "";
            let domain_MessageId = "";

            function extractDomainFromEmail(email) {
                const match = email.match(/@([^>]+)/);
                return match ? match[1] : null;
            }

            // Extract original information
            const returnPathItem = rankedData.find(item => item.key === "Return-Path");
            if (returnPathItem) {
                return_path = returnPathItem.value;
                domain_ReturnPath = extractDomainFromEmail(returnPathItem.value);
                document.getElementById("return_path").value = return_path;
            }

            const fromItem = rankedData.find(item => item.key === "From");

            function extractEmail(input) {
                const match = input.match(/(.*)<([^>]+)>/);
                const name = match ? match[1].trim() : null;
                const email = match ? match[2].trim() : null;
                return {
                    name,
                    email
                };
            }
            if (fromItem) {
                domain_from = extractDomainFromEmail(extractEmail(fromItem.value).email);
                document.getElementById("from").value = extractEmail(fromItem.value).email;
                document.getElementById("colonne").value = extractEmail(fromItem.value).name;
            }

            const senderItem = rankedData.find(item => item.key === "Sender" || item.key === "sender");
            if (senderItem) {
                domain_Sender = extractDomainFromEmail(senderItem.value);
            }

            const messageIdItem = rankedData.find(item => item.key === "Message-Id" || item.key === "Message-ID");
            if (messageIdItem) {
                domain_MessageId = extractDomainFromEmail(messageIdItem.value);
            }

            // Display parsed header (keep this for the header_display textarea)
            const origin_header = rankedData.map(header => `${header.key}: ${header.value}`).join('\n');
            document.getElementById("header_display").value = origin_header;

            // Extract authentication results
            const authResults = extractAuthResults(headerText);

            // Get ISP information
            let providerIp = "Not available";
            if (authResults.client_ip) {
                providerIp = await fetchISP(authResults.client_ip);
            }

            // Update form fields
            document.getElementById('ip').value = authResults.client_ip || '';
            document.getElementById('provider_ip').value = providerIp;
            document.getElementById('vmta').value = authResults.helo || '';
            document.getElementById('date').value = showDate(authResults.date) || '';
            document.getElementById('email').value = extractEmail_01(authResults.email) || '';
            document.getElementById('spf').value = authResults.spf?.toLowerCase() || 'pass';
            document.getElementById('dkim').value = authResults.dkim?.toLowerCase() || 'pass';
            document.getElementById('dmarc').value = authResults.dmarc?.toLowerCase() || 'pass';
            document.getElementById('message_path').value = authResults.message_path || 'inbox';

            // Extract email body
            const emailBody = extractEmailBody(headerText);
            document.getElementById('body').value = emailBody || '';

            // Extract domains from body and perform lookups
            const domainsFound = extractDomainsFromText(emailBody);
            let domainResults = [];

            if (domainsFound.length > 0) {
                domainResults = await lookupDomains(domainsFound);
            } else {
                // Try to extract any domain from the header as fallback
                const fallbackDomains = [];
                if (domain_from) fallbackDomains.push(domain_from);
                if (domain_Sender) fallbackDomains.push(domain_Sender);
                if (domain_ReturnPath) fallbackDomains.push(domain_ReturnPath);
                if (domain_MessageId) fallbackDomains.push(domain_MessageId);

                if (fallbackDomains.length > 0) {
                    domainResults = await lookupDomains(fallbackDomains);
                }
            }

            // Display domain results
            if (domainResults.length > 0) {
                document.getElementById("domains").value = JSON.stringify(domainResults.map(d => d.domain));
            } else {
                document.getElementById("domains").value = JSON.stringify([]);
            }

            // Display results in the parseResults div
            const resultText = `IP: ${authResults.client_ip || 'N/A'}
Provider: ${providerIp}
VMTA: ${authResults.helo || 'N/A'}
SPF: ${authResults.spf || 'N/A'}
DKIM: ${authResults.dkim || 'N/A'}
DMARC: ${authResults.dmarc || 'N/A'}
Date: ${authResults.date || 'N/A'}
Email: ${extractEmail_01(authResults.email) || 'N/A'}
Message Path: ${messagePathValue || 'N/A'}`;

            document.getElementById("resultDisplay").textContent = resultText;
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

            const sclValue = sclMatch ? sclMatch[1] : null;
            messagePathValue = sclValue in sclMapping ? `${sclMatch[1]} --> ${sclMapping[sclValue]}` : 'inbox';
            const messagePath = sclValue in sclMapping ? sclMapping[sclValue] : 'inbox';

            return {
                spf: authResultsMatch ? authResultsMatch[1] : null,
                dkim: authResultsMatch ? authResultsMatch[2] : null,
                dmarc: authResultsMatch ? authResultsMatch[3] : null,
                client_ip: clientIpMatch ? clientIpMatch[1] : null,
                provider_ip: clientIpMatch ? clientIpMatch[1] : null, // Same as client_ip for now
                helo: heloMatch ? heloMatch[1] : null,
                date: dateMatch ? dateMatch[1] : null,
                email: toMatch ? toMatch[1] : null,
                message_path: messagePath,
            };
        }

        function extractEmailBody(source) {
            try {
                const parts = source.split("MIME-Version: 1.0");
                if (parts.length > 1) {
                    let body = parts[1].trim();
                    return body;
                }
                return source;
            } catch (e) {
                console.error("Error extracting email body:", e);
                return "Could not extract email body";
            }
        }

        // DNS Record Lookup (placeholder - you'll need to implement this)
        async function getDNSRecord(domain, type) {
            // This is a placeholder - you'll need to implement actual DNS lookup
            console.log(`Looking up ${type} record for ${domain}`);
            return [];
        }

        function resetFields() {
            document.getElementById('header_text').value = '';
            document.getElementById('email_file').value = '';
            document.getElementById('header_display').value = '';
            document.getElementById('parseResults').style.display = 'none';
            document.getElementById('domains').value = '';
            document.getElementById('return_path').value = '';
            document.getElementById('ip').value = '';
            document.getElementById('provider_ip').value = '';
            document.getElementById('vmta').value = '';
            document.getElementById('from').value = '';
            document.getElementById('date').value = '';
            document.getElementById('email').value = '';
            document.getElementById('colonne').value = '';
            document.getElementById('redirect_link').value = '';
            document.getElementById('spf').value = 'pass';
            document.getElementById('dkim').value = 'pass';
            document.getElementById('dmarc').value = 'pass';
            document.getElementById('message_path').value = 'inbox';
            document.getElementById('body').value = '';
        }
    </script>

    <style>
        textarea.form-control {
            font-family: monospace;
            font-size: 0.9rem;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }
    </style>
@endsection
