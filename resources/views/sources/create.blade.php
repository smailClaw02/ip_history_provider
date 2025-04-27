@extends('layouts.app')

@section('content')
    <div class="m-auto" style="width: 98%">

        <form method="POST" action="{{ route('sources.store') }}" enctype="multipart/form-data" id="emailSourceForm">
            @csrf

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white justify-content-between d-flex">
                            <h2 class="">Create New Email Source</h2>
                            <button type="submit" id="processBtn" class="col-auto btn border btn-lg">
                                <i class="fas fa-save me-2"></i>Save Email Record
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Header Textarea and File Upload -->
                            <div class="form-group mb-3">
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
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6 row">
                                    <div class="form-group mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="datetime-local" class="form-control" id="date" name="date"
                                            readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="colonne" class="form-label">Colonne</label>
                                        <input type="text" class="form-control" id="colonne" name="colonne">
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

                                <div class="" style="width: 98.4%;">
                                    <label for="redirect_link" class="form-label">Redirect Link</label>
                                    <input type="text" class="form-control" id="redirect_link" name="redirect_link"
                                        value="https://...">
                                </div>

                                <hr class="my-4 w-75 m-auto border-3 text-info">

                                <!-- Text Areas -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="header_display" class="form-label">Email Header</label>
                                            <textarea class="form-control" id="header_display" name="header" rows="15"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="body" class="form-label">Email Body</label>
                                            <textarea class="form-control" id="body" name="body" rows="15"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    <script>
        function showDate(date) {
            const dates = new Date(date);
            const formatted = dates.toISOString().slice(0, 16);
            return formatted;
        }

        // DOM Elements
        const headerTextarea = document.getElementById('header_text');
        const fileInput = document.getElementById('email_file');

        // File Upload Handler
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                headerTextarea.value = e.target.result;
                parseHeaders();
            };
            reader.readAsText(file);
        });

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

        function extractEmail(input) {
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
            document.getElementById("resultDisplay").textContent = "Parsing headers...";
            document.getElementById("parseResults").style.display = 'block';

            // Process header (keep this for internal processing)
            const header_find = extractEmailBody(headerText)[0]

            let result = header_find
                .replace(/Return-Path:\s*(.+)/i, (_, email) => `Return-Path: ${email}`)
                .replace(/Message-Id:\s*(<.+)/i, (_, email) => `Message-Id: ${email}`)
                .replace(/Message-ID:\s*(<.+)/i, (_, email) => `Message-ID: ${email}`)
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
            const filteredAndSortedHeaders = headersArray
                .filter(header => filterKeys.includes(header.key))
                .sort((a, b) => a.key.localeCompare(b.key));

            const filteredData = filteredAndSortedHeaders.filter(item =>
                item.key === "Received" && item.value.includes("from localhost") || item.key !== "Received"
            );

            const rankedData = filteredData.sort((a, b) => a.id - b.id);

            function extractEmailInfo(input) {
                const match = input.match(/(.*)<([^>]+)>/);
                const name = match ? match[1].trim() : null;
                const email = match ? match[2].trim() : null;
                return {
                    name,
                    email
                };
            }

            // Extract From and Return-Path
            const returnPathItem = rankedData.find(item => item.key === "Return-Path");
            if (returnPathItem) {
                document.getElementById("return_path").value = returnPathItem.value;
            }

            const fromItem = rankedData.find(item => item.key === "From");
            if (fromItem) {
                const fromInfo = extractEmailInfo(fromItem.value);
                document.getElementById("from").value = fromInfo.email || fromItem.value;
                if (fromInfo.name) {
                    document.getElementById("colonne").value = fromInfo.name;
                }
            }

            // Display parsed header
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
            document.getElementById('email').value = extractEmail(authResults.email) || '';
            document.getElementById('spf').value = authResults.spf?.toLowerCase() || 'pass';
            document.getElementById('dkim').value = authResults.dkim?.toLowerCase() || 'pass';
            document.getElementById('dmarc').value = authResults.dmarc?.toLowerCase() || 'pass';
            document.getElementById('message_path').value = authResults.message_path || 'inbox';

            // Extract email body
            const emailBody = extractEmailBody(headerText)[1];
            document.getElementById('body').value = emailBody || '';

            // Display results
            const resultText = `IP: ${authResults.client_ip || 'N/A'}
Provider: ${providerIp}
VMTA: ${authResults.helo || 'N/A'}
SPF: ${authResults.spf || 'N/A'}
DKIM: ${authResults.dkim || 'N/A'}
DMARC: ${authResults.dmarc || 'N/A'}
Date: ${authResults.date || 'N/A'}
Email: ${extractEmail(authResults.email) || 'N/A'}
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
                9: "spam"
            };

            const sclValue = sclMatch ? sclMatch[1] : null;
            messagePathValue = sclValue in sclMapping ? `${sclMatch[1]} --> ${sclMapping[sclValue]}` : 'inbox';
            const messagePath = sclValue in sclMapping ? sclMapping[sclValue] : 'inbox';

            return {
                spf: authResultsMatch?.[1],
                dkim: authResultsMatch?.[2],
                dmarc: authResultsMatch?.[3],
                client_ip: clientIpMatch?.[1],
                helo: heloMatch?.[1],
                date: dateMatch?.[1],
                email: toMatch?.[1],
                message_path: messagePath,
            };
        }

        function extractEmailBody(source) {
            try {
                const parts = source.split("MIME-Version: 1.0");

                if (parts.length > 1) {
                    const head = parts[0].trim() + "\nMIME-Version: 1.0";
                    const body = parts.slice(1).join("MIME-Version: 1.0")
                        .trim(); // Join the rest, in case there are multiple
                    //  console.log(head);

                    return [head, body];
                }
                return source;
            } catch (e) {
                console.error("Error extracting email body:", e);
                return "Could not extract email body";
            }
        }

        function resetFields() {
            document.getElementById('header_text').value = '';
            document.getElementById('email_file').value = '';
            document.getElementById('header_display').value = '';
            document.getElementById('parseResults').style.display = 'none';
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

        #processBtn {
            transition: all 0.5s ease;
            border: 1px solid;
            color: white;
            background: linear-gradient(#0047ab 60%, #eee 95%, #0047ab 95%);
        }

        #processBtn:hover {
            transform: translateY(-2px);
            color: #0047ab;
            background: linear-gradient(#eee 60%, #0047ab 95%, #eee 95%);
        }
    </style>
@endsection
