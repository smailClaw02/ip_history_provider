@extends('layouts.app')

@section('title', 'X-Delay Calculator')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0">X-Delay Calculator</h2>
            <button id="themeToggle" class="btn btn-info btn-sm">
                <i id="themeIcon" class="fas fa-sun"></i>
            </button>
        </div>
        
        <div class="card-body">
            <!-- Current Time Row -->
            <div class="row align-items-end mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Current Time</label>
                    <input class="form-control" type="datetime-local" id="time_now" readonly />
                </div>
                <div class="col-md-2 text-center">
                    <button class="btn btn-info w-100" id="calculateTimeBtn">
                        <i class="fas fa-calculator me-1"></i> Calculate
                    </button>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">New Time:</label>
                    <input class="form-control" type="datetime-local" id="result_time" readonly />
                </div>
            </div>

            <!-- Calculation Section -->
            <div class="p-4 rounded mb-4">
                <h3 class="text-center mb-4">X-Delay Calculation</h3>

                <div class="row mb-4 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Number of Minutes</label>
                        <input type="number" class="form-control" id="number_minuteurs" value="10" min="1" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Number to Send</label>
                        <input type="number" class="form-control" id="number_send" value="10" min="1" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Number of IPs</label>
                        <input type="number" class="form-control" id="number_ips" value="10" min="1" />
                    </div>
                    <div class="col-md-3 text-center">
                        <button id="calculateButton" class="btn btn-primary w-100">
                            <i class="fas fa-calculator me-1"></i> Calculate
                        </button>
                    </div>
                </div>

                <!-- Result Display -->
                <div id="msg" class="mb-3"></div>
                
                <div class="input-group">
                    <input type="text" class="form-control" id="outputTextarea" placeholder="Calculation result..." readonly />
                    <button class="btn btn-success" id="copyButton" type="button">
                        <i class="fas fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Calculates optimal sending intervals based on your input parameters.
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
        }

        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');

            themeIcon.classList.replace(isDarkMode ? 'fa-sun' : 'fa-moon', 
                                      isDarkMode ? 'fa-moon' : 'fa-sun');
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        });

        // Time Calculation
        function updateTime() {
            const now = new Date();
            const formattedTime = now.toISOString().slice(0, 16);
            document.getElementById("time_now").value = formattedTime;
        }

        document.getElementById("calculateTimeBtn").addEventListener("click", function() {
            const timeInput = document.getElementById("time_now").value;
            const number_minuteurs = parseInt(document.getElementById("number_minuteurs").value, 10);

            if (!timeInput || isNaN(number_minuteurs)) {
                alert("Please enter a valid number of minutes.");
                return;
            }

            const currentTime = new Date(timeInput);
            currentTime.setMinutes(currentTime.getMinutes() + 60 + number_minuteurs);

            document.getElementById("result_time").value = currentTime.toISOString().slice(0, 16);
        });

        // X-Delay Calculation
        document.getElementById("calculateButton").addEventListener("click", function() {
            const number_minuteurs = Number(document.getElementById("number_minuteurs").value);
            const number_send = Number(document.getElementById("number_send").value);
            const number_ips = Number(document.getElementById("number_ips").value);

            if (isNaN(number_minuteurs) || isNaN(number_send) || isNaN(number_ips) ||
                number_minuteurs <= 0 || number_send <= 0 || number_ips <= 0) {
                document.getElementById("outputTextarea").value = "Please enter valid positive numbers.";
                return;
            }

            const result = ((number_minuteurs * 60) / (number_send / number_ips)) * 1000000;
            const formattedResult = `${number_ips}_${result.toFixed(0)}`;

            document.getElementById("outputTextarea").value = formattedResult;
            document.getElementById("msg").innerHTML = `
                <div class="alert alert-info p-2">
                    <i class="fas fa-info-circle me-2"></i>
                    <b>${number_ips} emails</b> will be sent every 
                    <b class="text-primary">${(result / 1000000).toFixed(2)} seconds</b>
                </div>`;
        });

        // Copy Functionality
        document.getElementById("copyButton").addEventListener("click", function() {
            const outputField = document.getElementById("outputTextarea");
            outputField.select();
            navigator.clipboard.writeText(outputField.value);
            
            // Show feedback
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 2000);
        });

        // Initialize time
        updateTime();
    });
</script>

<style>
    body.dark-mode {
        background-color: #121212;
        color: #ffffff;
        transition: all 0.5s ease;
    }
    .dark-mode .card,
    .dark-mode .input-group,
    .dark-mode .form-control {
        background-color: #222;
        border-color: #444;
        color: #fff;
    }
    .dark-mode .form-control:read-only {
        background-color: #333;
    }
    .dark-mode .alert-info {
        background-color: #003847;
        border-color: #004b5f;
        color: #e6f7ff;
    }
</style>
@endsection