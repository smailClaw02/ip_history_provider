@extends('layouts.app')

@section('title', 'Time Calculator')

@section('content')
<div class="py-4 m-auto" style="width: 98%">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0 text-center">Time Calculator</h2>
        </div>
        
        <div class="card-body">
            <form id="timeForm" class="mb-4">
                <div class="row g-3">
                    <!-- Sleep Time (seconds) -->
                    <div class="col-md-5">
                        <label for="sleep" class="form-label fw-bold">Sleep Time (seconds)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="sleep" value="60" min="1" required>
                            <button type="button" class="btn btn-secondary" id="calcSleepBtn">
                                <i class="fas fa-calculator"></i> Calculate Sleep
                            </button>
                        </div>
                    </div>
                    
                    <!-- Loop Count -->
                    <div class="col-md-7">
                        <label for="loop" class="form-label fw-bold">Loop Count</label>
                        <input type="number" class="form-control" id="loop" value="500" min="1" required>
                    </div>
                    
                    <!-- IPs -->
                    <div class="col-md-3">
                        <label for="ips" class="form-label fw-bold">IPs</label>
                        <input type="number" class="form-control" id="ips" value="1" min="1" required>
                    </div>
                    
                    <!-- Fraction -->
                    <div class="col-md-3">
                        <label for="fraction" class="form-label fw-bold">Fraction</label>
                        <input type="number" class="form-control" id="fraction" value="1" min="1">
                    </div>
 
                    <!-- emails/min -->
                    <div class="col-md-3">
                        <label for="emails_min" class="form-label fw-bold">Number emails (emails/min)</label>
                        <input type="number" class="form-control" id="emails_min" value="1" min="1">
                    </div>
                   
                    <!-- Minutes -->
                    <div class="col-md-3">
                        <label for="minutes" class="form-label fw-bold">Minutes (for Email Calculation)</label>
                        <input type="number" class="form-control" id="minutes" value="15" min="1">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mt-3">
                    <i class="fas fa-calculator me-2"></i> Calculate
                </button>
            </form>
            
            <!-- Results Section -->
            <div class="result-box card border-primary mt-4" id="resultBox" style="display: none;">
                <div class="card-body">
                    <!-- Total Time -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-success">Total Time:</span>
                        <span class="badge bg-success fs-6" id="totalTime" style="color:black !important;"></span>
                    </div>
                    
                    <!-- Email Calculation -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-info">Email Calculation:</span>
                        <span class="badge bg-info fs-6" id="emailResult" style="color:black !important;"></span>
                    </div>
                    
                    <!-- All Data -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-warning">All Data:</span>
                        <span class="badge bg-warning fs-6" id="fractionResult" style="color:black !important;"></span>
                    </div>
                    
                    <!-- Time Calculation -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Current Time</label>
                            <input class="form-control" type="datetime-local" id="time_now">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">End Time:</label>
                            <input class="form-control" type="datetime-local" id="result_time" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle me-2"></i>
                Calculate total processing time and email sending rates based on your parameters.
                <br>Enter either sleep time or emails per minute and click the corresponding calculate button.
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update current time
        function updateTime() {
            const now = new Date();
            document.getElementById("time_now").value = now.toISOString().slice(0, 16);
        }
        
        // Calculate end time
        function calculateNewTime(minutes) {
            const timeInput = document.getElementById("time_now").value;
            if (!timeInput || isNaN(minutes)) return;
            
            const currentTime = new Date(timeInput);
            currentTime.setMinutes(currentTime.getMinutes() + 60 + minutes);
            document.getElementById("result_time").value = currentTime.toISOString().slice(0, 16);
        }
        
        // Calculate sleep time based on emails per minute
        function calculateSleepTime() {
            const emailsPerMinute = parseFloat(document.getElementById('emails_min').value);
            const fraction = parseFloat(document.getElementById('fraction').value) || 1;
            const ips = parseFloat(document.getElementById('ips').value) || 1;
            
            if (emailsPerMinute <= 0 || fraction <= 0 || ips <= 0) {
                alert('Please enter valid positive numbers for emails per minute, fraction, and IPs');
                return;
            }
            
            // Calculate sleep time: (60 seconds) / (emails per minute / (fraction * IPs))
            const sleepTime = 60 / (emailsPerMinute / (fraction * ips));
            document.getElementById('sleep').value = sleepTime;
            
            // Show a message about the calculation
            // alert(`Calculated sleep time: ${sleepTime} seconds to achieve ${emailsPerMinute} emails/minute with ${ips} IP(s) and fraction ${fraction}`);
        }
        
        // Form submission handler
        document.getElementById('timeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get input values with defaults
            const sleepTime = parseFloat(document.getElementById('sleep').value) || 60;
            const loopCount = parseFloat(document.getElementById('loop').value) || 500;
            const fraction = parseFloat(document.getElementById('fraction').value) || 1;
            const minutes = parseFloat(document.getElementById('minutes').value) || 15;
            const ips = parseFloat(document.getElementById('ips').value) || 1;
            const emailsPerMinute = (60 / sleepTime) * fraction * ips;
            
            // Update the emails/min field if it was empty or zero
            if (!document.getElementById('emails_min').value || document.getElementById('emails_min').value == "0") {
                document.getElementById('emails_min').value = emailsPerMinute;
            }
            
            // Validate inputs
            if (sleepTime <= 0 || loopCount <= 0 || ips <= 0) {
                alert('Please enter valid positive numbers');
                return;
            }
            
            // Calculate total time
            const totalSeconds = sleepTime * loopCount;
            const hoursTotal = Math.floor(totalSeconds / 3600);
            const minutesTotal = Math.floor((totalSeconds % 3600) / 60);
            const secondsTotal = Math.floor(totalSeconds % 60);
            
            // Calculate email rates (now using minutes instead of hours)
            const totalEmails = emailsPerMinute * minutes;
            
            // Display results
            document.getElementById('totalTime').textContent = 
                `${hoursTotal}h ${minutesTotal}m ${secondsTotal}s`;
            
            document.getElementById('emailResult').textContent = 
                `${totalEmails.toFixed(0)} emails in ${minutes} minute(s) at ${emailsPerMinute} emails/minute`;
            
            document.getElementById('fractionResult').textContent = 
                `Data Send: ${(loopCount * ips * fraction).toFixed(0)} → ${totalSeconds}s`;
            
            // Calculate and show end time
            calculateNewTime(totalSeconds / 60);
            
            // Show results
            document.getElementById('resultBox').style.display = 'block';
        });
        
        // Add event listener for the calculate sleep button
        document.getElementById('calcSleepBtn').addEventListener('click', calculateSleepTime);
        
        // Initialize
        updateTime();
    });
</script>
@endsection