@extends('layouts.app')

@section('title', 'Text Multiplier Tool')

@section('content')
    <div class="m-auto py-4" style="width: 90%">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Text Multiplier Tool</h2>
            </div>
            <div class="card-body">
                <div class="row align-items-end mb-4 justify-content-between">
                    <!-- Text Input -->
                    <div class="col-md-8 mb-3 mb-md-0">
                        <label for="textInput" class="form-label fw-bold">Enter Text:</label>
                        <input type="text" id="textInput" class="form-control border-dark"
                            placeholder="Enter text to multiply">
                    </div>

                    <!-- Count Input -->
                    <div class="col-md-2 mb-3 mb-md-0">
                        <label for="countInput" class="form-label fw-bold">Count:</label>
                        <input type="number" id="countInput" class="form-control border-dark" min="1"
                            value="10">
                    </div>

                    <!-- Generate Button -->
                    <div class="col-md-auto">
                        <button id="generateBtn" class="btn btn-primary">
                            <i class="fas fa-cogs me-1"></i> Generate
                        </button>
                    </div>
                </div>

                <!-- Result Area -->
                <div class="mb-3">
                    <label for="resultText" class="form-label fw-bold">Result:</label>
                    <textarea id="resultText" class="form-control border-dark" rows="5" readonly
                        placeholder="Generated text will appear here"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button id="copyBtn" class="btn btn-success">
                        <i class="fas fa-copy me-1"></i> Copy
                    </button>
                    <button id="clearBtn" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Clear
                    </button>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Enter text and count to generate multiple copies of the text.
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate multiple copies of text
            document.getElementById('generateBtn').addEventListener('click', function() {
                const text = document.getElementById('textInput').value.trim();
                const count = parseInt(document.getElementById('countInput').value, 10);

                if (!text) {
                    alert('Please enter some text first');
                    return;
                }

                let result = '';
                for (let i = 0; i < count; i++) {
                    result += text + '\n';
                }
                document.getElementById('resultText').value = result.trim();
            });

            // Copy text to clipboard
            document.getElementById('copyBtn').addEventListener('click', function() {
                const textarea = document.getElementById('resultText');

                if (!textarea.value) {
                    alert('No text to copy!');
                    return;
                }

                textarea.select();
                document.execCommand('copy');

                // Show feedback
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });

            // Clear all fields
            document.getElementById('clearBtn').addEventListener('click', function() {
                document.getElementById('textInput').value = '';
                document.getElementById('countInput').value = 10;
                document.getElementById('resultText').value = '';
            });
        });
    </script>
@endsection
