@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">cPanel Checker</h4>
                    <button class="btn btn-outline-secondary" onclick="toggleDarkMode()">
                        <i class="bi {{ $darkMode ? 'bi-sun' : 'bi-moon' }}"></i>
                    </button>
                </div>

                <div class="card-body">
                    @if(!$isRunning)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Input cPanel List</h5>
                        </div>
                        <div class="card-body">
                            <form id="uploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <div id="fileUploadArea" class="rounded" onclick="document.getElementById('fileInput').click()" style="border: 2px dashed #dee2e6; padding: 10px; text-align: center; cursor: pointer;">
                                        <i class="bi bi-upload fs-1"></i>
                                        <p class="mt-2">Click to upload file or drag and drop</p>
                                        <input type="file" id="fileInput" class="d-none" onchange="handleFileSelect(event)">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="fileContent" class="form-label">Or paste cPanel list (format: url|username|password)</label>
                                    <textarea class="form-control" id="fileContent" rows="5"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="threads" class="form-label">Threads</label>
                                        <input type="number" class="form-control" id="threads" value="10" min="1" max="50">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="outputFile" class="form-label">Output File</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="outputFile" value="success_cpanels.txt">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="startChecking()">
                                    <i class="bi bi-play-fill"></i> Start Checking
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                    
                    @if($isRunning)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card stats-card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Total Tests</h5>
                                    <p class="display-4">{{ $stats['total'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stats-card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Success</h5>
                                    <p class="display-4 text-success">{{ $stats['success'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stats-card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Failed</h5>
                                    <p class="display-4 text-danger">{{ $stats['failed'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="progress mb-4" style="height: 30px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($stats['total']) > 0 ? ($stats['success'] / $stats['total'] * 100) : 0 }}%">
                            {{ $stats['total'] > 0 ? number_format($stats['success'] / $stats['total'] * 100, 1) : 0 }}%
                        </div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($stats['total']) > 0 ? ($stats['failed'] / $stats['total'] * 100) : 0 }}%">
                            {{ $stats['total'] > 0 ? number_format($stats['failed'] / $stats['total'] * 100, 1) : 0 }}%
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Timing Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Start Time:</strong> {{ $stats['start_time'] ? $stats['start_time']->format('Y-m-d H:i:s') : 'Not started' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>End Time:</strong> {{ $stats['end_time'] ? $stats['end_time']->format('Y-m-d H:i:s') : 'In progress...' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Duration:</strong> 
                                        @if($stats['start_time'] && $stats['end_time'])
                                            {{ number_format($stats['end_time']->diffInSeconds($stats['start_time']), 2) }} seconds
                                        @elseif($stats['start_time'])
                                            {{ number_format(now()->diffInSeconds($stats['start_time']), 2) }} seconds (ongoing)
                                        @else
                                            Not started
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(!empty($results))
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Test Results</h5>
                            <div>
                                <button class="btn btn-sm btn-success me-2" onclick="filterResults('Success')">
                                    <i class="bi bi-check-circle"></i> Success ({{ $stats['success'] }})
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="filterResults('Failed')">
                                    <i class="bi bi-x-circle"></i> Failed ({{ $stats['failed'] }})
                                </button>
                                <button class="btn btn-sm btn-secondary ms-2" onclick="filterResults('All')">
                                    <i class="bi bi-list-ul"></i> All
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="resultsTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Domains</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)
                                    <tr class="status-{{ strtolower($result['status']) }}">
                                        <td>{{ $result['url'] }}</td>
                                        <td>{{ $result['username'] }}</td>
                                        <td>
                                            @if($result['status'] == 'Success')
                                                <span class="badge bg-success">{{ $result['status'] }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $result['status'] }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $result['domains'] }}</td>
                                        <td>{{ $result['error'] ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Then load Bootstrap bundle (which includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Then load DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    let dataTable;
    let darkMode = localStorage.getItem('darkMode') === 'true';
    
    $(document).ready(function() {
        // Initialize DataTable
        dataTable = $('#resultsTable').DataTable({
            order: [[4, 'desc']],
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            pageLength: 10
        });
        
        // Set up drag and drop
        const fileUploadArea = document.getElementById('fileUploadArea');
        if (fileUploadArea) {
            fileUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileUploadArea.classList.add('bg-primary', 'bg-opacity-10');
            });
            
            fileUploadArea.addEventListener('dragleave', () => {
                fileUploadArea.classList.remove('bg-primary', 'bg-opacity-10');
            });
            
            fileUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                fileUploadArea.classList.remove('bg-primary', 'bg-opacity-10');
                if (e.dataTransfer.files.length) {
                    document.getElementById('fileInput').files = e.dataTransfer.files;
                    handleFileSelect({ target: document.getElementById('fileInput') });
                }
            });
        }
        
        // Update stats periodically if running
        @if($isRunning)
            setInterval(updateStats, 2000);
        @endif
    });
    
    function toggleDarkMode() {
        darkMode = !darkMode;
        localStorage.setItem('darkMode', darkMode);
        applyDarkMode();
    }
    
    function applyDarkMode() {
        if (darkMode) {
            $('html').attr('data-bs-theme', 'dark');
            $('body').addClass('dark-mode bg-dark').removeClass('bg-light');
            $('.btn-dark-mode i').removeClass('bi-moon').addClass('bi-sun');
        } else {
            $('html').attr('data-bs-theme', 'light');
            $('body').addClass('bg-light').removeClass('dark-mode bg-dark');
            $('.btn-dark-mode i').removeClass('bi-sun').addClass('bi-moon');
        }
    }
    
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fileContent').value = e.target.result;
        };
        reader.readAsText(file);
    }
    
    function filterResults(status) {
        if (status === 'All') {
            dataTable.column(2).search('').draw();
        } else {
            dataTable.column(2).search('^' + status + '$', true, false).draw();
        }
    }
    
    function startChecking() {
        const content = document.getElementById('fileContent').value.trim();
        if (!content) {
            alert('Please provide cPanel list either by uploading a file or pasting in the textarea.');
            return;
        }
        
        const threads = document.getElementById('threads').value;
        const outputFile = document.getElementById('outputFile').value;
        
        $.ajax({
            url: '{{ route("tools.cpanel-checker.start") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                content: content,
                threads: threads,
                outputFile: outputFile
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    }
    
    function updateStats() {
        $.get('{{ route("tools.cpanel-checker.stats") }}', function(data) {
            // Update counters
            $('.display-4').eq(0).text(data.total);
            $('.display-4').eq(1).text(data.success);
            $('.display-4').eq(2).text(data.failed);
            
            // Update progress bars
            const successPercent = data.total > 0 ? (data.success / data.total * 100) : 0;
            const failedPercent = data.total > 0 ? (data.failed / data.total * 100) : 0;
            
            $('.progress-bar').eq(0)
                .css('width', successPercent + '%')
                .text(successPercent.toFixed(1) + '%');
            $('.progress-bar').eq(1)
                .css('width', failedPercent + '%')
                .text(failedPercent.toFixed(1) + '%');
            
            // Update timing info
            if (data.start_time) {
                const startDate = new Date(data.start_time);
                $('strong:contains("Start Time:")').parent().text('Start Time: ' + startDate.toLocaleString());
            }
            if (data.end_time) {
                const endDate = new Date(data.end_time);
                $('strong:contains("End Time:")').parent().text('End Time: ' + endDate.toLocaleString());
            }
            
            // Update duration
            if (data.start_time && data.end_time) {
                const duration = (new Date(data.end_time) - new Date(data.start_time)) / 1000;
                $('strong:contains("Duration:")').parent().text('Duration: ' + duration.toFixed(2) + ' seconds');
            } else if (data.start_time) {
                const duration = (new Date() - new Date(data.start_time)) / 1000;
                $('strong:contains("Duration:")').parent().text('Duration: ' + duration.toFixed(2) + ' seconds (ongoing)');
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .status-success { background-color: #d4edda !important; }
    .status-failed { background-color: #f8d7da !important; }
    .stats-card { margin-bottom: 20px; }
    .progress { height: 30px; }
    .dark-mode .card { background-color: #2c3034; color: #f8f9fa; }
    .dark-mode .table { color: #f8f9fa; }
    .dark-mode .table-striped>tbody>tr:nth-of-type(odd) { --bs-table-accent-bg: rgba(255,255,255,0.05); }
    .dark-mode .form-control, .dark-mode .form-select { background-color: #2c3034; color: #f8f9fa; border-color: #495057; }
    .dark-mode .input-group-text { background-color: #495057; color: #f8f9fa; border-color: #495057; }
    .dark-mode .modal-content { background-color: #2c3034; color: #f8f9fa; }
    .dark-mode .btn-close { filter: invert(1); }
    #fileUploadArea { border: 2px dashed #dee2e6; padding: 10px; text-align: center; cursor: pointer; }
    .dark-mode #fileUploadArea { border-color: #495057; }
    #fileContent { height: 150px; }
</style>
@endpush