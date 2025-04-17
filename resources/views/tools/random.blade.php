@extends('layouts.app')

@section('title', 'Random')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Random</h2>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="inputText" class="form-label fw-bold">Text:</label>
                <textarea class="form-control" id="inputText" rows="5" 
                    placeholder="Enter your Text..."></textarea>
            </div>
            
            <button type="button" class="btn btn-success mb-3" id="randomizeBtn">
                <i class="fas fa-random me-2"></i> Randomize
            </button>
            
            <div class="mb-3">
                <label for="outputText" class="form-label fw-bold">Result Random:</label>
                <textarea class="form-control" id="outputText" rows="5" readonly
                    placeholder="Randomized Result"></textarea>
            </div>

            <!-- Added pattern information section -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Random Pattern Formats</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><code>[RandomA/N]</code> : [A-Z a-z 0-9] (Alphanumeric)</li>
                        <li class="list-group-item"><code>[RandomL/N]</code> : [a-z 0-9] (Lowercase + Numbers)</li>
                        <li class="list-group-item"><code>[RandomLU/N]</code> : [A-Z 0-9] (Uppercase + Numbers)</li>
                        <li class="list-group-item"><code>[RandomN/N]</code> : [0-9] (Numbers only)</li>
                        <li class="list-group-item"><code>[RandomC/N]</code> : [A-Z a-z] (Letters only)</li>
                        <li class="list-group-item"><code>[RandomCL/N]</code> : [a-z] (Lowercase letters)</li>
                        <li class="list-group-item"><code>[RandomCLU/N]</code> : [A-Z] (Uppercase letters)</li>
                        <li class="list-group-item"><code>[RandomCS/N]</code> : Special characters</li>
                        <li class="list-group-item"><code>[random]</code> : 18 random characters (default)</li>
                    </ul>
                    <p class="mt-3 mb-0"><strong>Note:</strong> Replace <code>N</code> with the desired length of the random string.</p>
                </div>
            </div>
        </div>
    </div>
</div>

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