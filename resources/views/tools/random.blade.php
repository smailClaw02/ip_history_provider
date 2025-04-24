@extends('layouts.app')

@section('title', 'Random')

@section('content')
    <div class="container py-4 m-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Random</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="inputText" class="form-label fw-bold">Text:</label>
                    <textarea class="form-control  dark-mode-border" id="inputText" rows="5"
                        placeholder="Enter your Text..."></textarea>
                </div>

                <button type="button" class="btn btn-success mb-3" id="randomizeBtn">
                    <i class="fas fa-random me-2"></i> Randomize
                </button>

                <div class="mb-3">
                    <label for="outputText" class="form-label fw-bold">Result Random:</label>
                    <textarea class="form-control  dark-mode-border" id="outputText" rows="5" readonly
                        placeholder="Randomized Result"></textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- <style>
        .dark-mode {
            background-color: #1a1a1a;
            color: #f8f9fa;
        }

        .dark-mode .card {
            background-color: #2d2d2d;
            border-color: #444;
        }

        .dark-mode .card-header {
            background-color: #1e3a8a !important;
            border-color: #444;
        }

        .dark-mode .form-control {
            background-color: #333;
            color: #f8f9fa;
            border-color: #555;
        }

        .dark-mode .form-control:focus {
            background-color: #333;
            color: #f8f9fa;
            border-color: #555;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .dark-mode .form-label {
            color: #f8f9fa;
        }

        .dark-mode-border {
            border-color: #555;
        }

        .dark-mode .list-group-item {
            background-color: #333;
            color: #f8f9fa;
            border-color: #444;
        }

        .dark-mode code {
            color: #f8f9fa;
            background-color: #444;
        }
    </style> --}}

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            // Function to randomize input
            function randomizeInput(input) {
                // Check if input contains @ (email-like)
                if (input.includes('@')) {
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
                    return [transformedArray.join(''), domain];
                } else {
                    // Handle non-email input
                    const splitValue = input.split(/([._\-=\+\$\%\#])/);
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
                    return [transformedArray.join(''), null];
                }
            }

            // Function to process all input
            function processAllInput() {
                const inputText = document.getElementById('inputText').value;
                const lines = inputText.split('\n').filter(line => line.trim() !== '');
                const outputTextarea = document.getElementById('outputText');
                let output = '';

                lines.forEach(line => {
                    try {
                        const [randomized, domain] = randomizeInput(line.trim());
                        output += randomized;
                        if (domain) output += `@${domain}`;
                        output += '\n';
                    } catch (e) {
                        console.error('Error processing line:', line, e);
                        output += `[Error processing: ${line}]\n`;
                    }
                });

                outputTextarea.value = output.trim();
            }

            // Add event listener to button
            const btn = document.getElementById('randomizeBtn');
            btn.addEventListener('click', processAllInput);

            // For debugging
            console.log('Randomizer tool initialized');
        });
    </script>
@endsection
