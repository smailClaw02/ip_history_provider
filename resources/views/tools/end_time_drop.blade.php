@extends('layouts.app')

@section('title', 'Time Calculator')

@section('content')
    <div class="py-4 m-auto" style="width: 90%">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0 text-center">Time Calculator</h2>
            </div>

            <div class="card-body">
                <form id="timeForm" class="mb-4">
                    <div class="row g-3 justify-content-around">
                        <!-- Fraction -->
                        <div class="col-md-3">
                            <label for="fraction" class="form-label fw-bold">Fraction <span class="text-warning"
                                    id="fractionNum"></span></label>
                            <div class="input-group">
                                <input type="number" class="form-control border-success" style="border-left: 0.5rem solid"
                                    id="fraction" value="1" min="1">
                                <button type="button" class="btn btn-success" id="calcFractionBtn">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Loop Count -->
                        <div class="col-md-3">
                            <label for="loop" class="form-label fw-bold text-success">Loop <span class="text-warning"
                                    id="loopNum"></span></label>
                            <div class="input-group">
                                <input type="number" class="form-control border-success" style="border-left: 0.5rem solid"
                                    id="loop" value="500" min="1">
                                <button type="button" class="btn btn-success" id="calcLoopBtn">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Sleep Time (seconds) -->
                        <div class="col-md-3">
                            <label for="sleep" class="form-label fw-bold text-success">Sleep <span class="text-warning"
                                    id="sleepNum"></span></label>
                            <div class="input-group">
                                <input type="number" class="form-control border-success" style="border-left: 0.5rem solid"
                                    id="sleep" value="60" min="1">
                                <button type="button" class="btn btn-success" id="calcSleepBtn">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                        </div>

                        <!-- IPs -->
                        <div class="col-md-3">
                            <label for="ips" class="form-label fw-bold text-success">IPs</label>
                            <input type="number" class="form-control border-success" style="border-left: 0.5rem solid"
                                id="ips" value="1" min="1">
                        </div>

                        <!-- Data -->
                        <div class="col-md-3">
                            <label for="data" class="form-label fw-bold text-info">Data</label>
                            <input type="number" class="form-control border-info" style="border-left: 0.5rem solid"
                                id="data" value="10000" min="1">
                        </div>

                        <!-- emails/min -->
                        <div class="col-md-3">
                            <label for="emails_min" class="form-label fw-bold text-info">Number emails (emails/min)</label>
                            <input type="number" class="form-control border-info" style="border-left: 0.5rem solid"
                                id="emails_min" value="1" min="1">
                        </div>

                        <!-- Minutes -->
                        <div class="col-md-3">
                            <label for="minutes" class="form-label fw-bold text-success">Minutes (for Email
                                Calculation)</label>
                            <input type="number" class="form-control border-success" style="border-left: 0.5rem solid"
                                id="minutes" value="15" min="1">
                        </div>
                        {{-- Start Time --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-success">Current Time / Start Time</label>
                            <input class="form-control border-success" style="border-left: 0.5rem solid"
                                type="datetime-local" id="time_now">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">
                        <i class="fas fa-calculator me-2"></i> Calculate
                    </button>
                </form>

                <!-- Results Section -->
                <div class="result-box card border-success mt-4" id="resultBox" style="display: none;">
                    <div class="card-body">
                        <table class="table table-striped table-hover w-75 m-auto">
                            <tr>
                                <th class="fw-bold text-warning">Total Time</th>
                                <th class="fw-bold">
                                    <span class="text-warning fs-6" id="totalTime"></span>
                                </th>

                            </tr>
                            <tr>
                                <th class="fw-bold text-info">Email Calculation</th>
                                <th class="fw-bold">
                                    <span class="text-info fs-6" id="emailResult"></span>
                                </th>
                            </tr>
                            <tr>
                                <th class="fw-bold text-info">All Data</th>
                                <th class="fw-bold">
                                    <span class="text-info fs-6" id="fractionResult"></span>
                                </th>
                            </tr>
                            <tr>
                                <th class="fw-bold text-info">End Time</th>
                                <th>
                                    <div class="col-md-auto">
                                        <input class="form-control" type="datetime-local" id="result_time" readonly>
                                    </div>
                                </th>

                            </tr>

                        </table>

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
                let sleepNum = document.getElementById('sleepNum');
                sleepNum.textContent = `{ Result: ${sleepTime} }`;
            }

            // Calculate loop count based on data, fraction and IPs
            function calculateLoopCount() {
                const data = parseFloat(document.getElementById('data').value);
                const fraction = parseFloat(document.getElementById('fraction').value) || 1;
                const ips = parseFloat(document.getElementById('ips').value) || 1;

                if (data <= 0 || fraction <= 0 || ips <= 0) {
                    alert('Please enter valid positive numbers for data, fraction, and IPs');
                    return;
                }

                // Calculate loop count: data / (fraction × IPs)
                const loopCount = data / (fraction * ips);
                let loopNum = document.getElementById('loopNum');
                loopNum.textContent = `{ Result: ${loopCount} }`;
            }

            // Calculate fraction based on data, loop count and IPs
            function calculateFraction() {
                const data = parseFloat(document.getElementById('data').value);
                const loopCount = parseFloat(document.getElementById('loop').value) || 1;
                const ips = parseFloat(document.getElementById('ips').value) || 1;

                if (data <= 0 || loopCount <= 0 || ips <= 0) {
                    alert('Please enter valid positive numbers for data, loop count, and IPs');
                    return;
                }

                // Calculate fraction: data / (loopCount × IPs)
                const fraction = data / (loopCount * ips);
                let fractionNum = document.getElementById('fractionNum');
                fractionNum.textContent = `{ Result: ${fraction} }`;
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
                if (!document.getElementById('emails_min').value || document.getElementById('emails_min')
                    .value == "0") {
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

                document.getElementById('emails_min').value = emailsPerMinute;
                document.getElementById('data').value = loopCount * ips * fraction;

                document.getElementById('emailResult').textContent =
                    `${totalEmails.toFixed(0)} emails ⇒ ${minutes} minute(s) at ${emailsPerMinute} emails/1min`;

                document.getElementById('fractionResult').textContent =
                    `${(loopCount * ips * fraction).toFixed(0)} Emails ⇒ ${Math.floor(totalSeconds / 60)} minute`;

                // Calculate and show end time
                calculateNewTime(totalSeconds / 60);

                // Show results
                document.getElementById('resultBox').style.display = 'block';
            });

            // Add event listeners for calculation buttons
            document.getElementById('calcSleepBtn').addEventListener('click', calculateSleepTime);
            document.getElementById('calcLoopBtn').addEventListener('click', calculateLoopCount);
            document.getElementById('calcFractionBtn').addEventListener('click', calculateFraction);

            // Initialize
            updateTime();
        });
    </script>
@endsection
