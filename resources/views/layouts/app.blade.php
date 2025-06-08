<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" target="_blank">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IP History Provider - @yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .email-header,
        .email-body {
            white-space: pre-wrap;
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .badge-spf-pass {
            background-color: #28a745;
        }

        .badge-spf-fail {
            background-color: #dc3545;
        }

        .badge-spf-neutral {
            background-color: #6c757d;
        }

        .action-btns .btn {
            margin-right: 5px;
        }

        /* Dark Mode Styles */
        [data-bs-theme="dark"] {
            color-scheme: dark;
        }

        [data-bs-theme="dark"] body {
            background-color: #121212;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .navbar {
            background-color: #1a1a1a !important;
            border-bottom: 1px solid #333;
        }

        [data-bs-theme="dark"] .email-header,
        [data-bs-theme="dark"] .email-body {
            background-color: #2d2d2d;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .card {
            background-color: #2d2d2d;
            border-color: #444;
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #333;
            color: #e0e0e0;
            border-color: #555;
        }

        [data-bs-theme="dark"] .table {
            --bs-table-bg: #2d2d2d;
            --bs-table-striped-bg: #383838;
            --bs-table-striped-color: #fff;
            --bs-table-active-bg: #373b3e;
            --bs-table-active-color: #fff;
            --bs-table-hover-bg: #323539;
            --bs-table-hover-color: #fff;
            color: #e0e0e0;
            border-color: #444;
        }

        [data-bs-theme="dark"] .table-dark {
            --bs-table-bg: #1a1a1a;
            --bs-table-striped-bg: #2d2d2d;
            --bs-table-striped-color: #fff;
            --bs-table-active-bg: #373b3e;
            --bs-table-active-color: #fff;
            --bs-table-hover-bg: #323539;
            --bs-table-hover-color: #fff;
            color: #fff;
            border-color: #373b3e;
        }

        [data-bs-theme="dark"] .badge.bg-secondary {
            background-color: #555 !important;
        }

        [data-bs-theme="dark"] .text-dark {
            color: #e0e0e0 !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #a0a0a0 !important;
        }

        [data-bs-theme="dark"] .bg-light {
            background-color: #2d2d2d !important;
        }

        [data-bs-theme="dark"] .bg-white {
            background-color: #1a1a1a !important;
        }

        .theme-toggle-btn {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: transparent;
            border: 1px solid #6c757d;
            color: inherit;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-2 fixed-top">
        <div class="row w-100 justify-content-between align-items-center">
            <div class="col-auto">
                <a class="navbar-brand mx-3" href="{{ route('sources.index') }}"><b class="shado">IP History
                        Provider</b></a>
            </div>

            <div class="col-auto">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" 
                                href="{{ route('tools.header-processor') }}" target="_blank">Header</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" 
                                href="{{ route('tools.body-filter') }}" target="_blank">Body</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6"  
                                href="{{ route('tools.spf-dmarc') }}" target="_blank">SPF & DMARC</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" 
                                href="{{ route('tools.end-time-drop') }}" target="_blank">End Time Drop</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" 
                                href="{{ route('tools.x-delay') }}" target="_blank">X-delay</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6"  
                                href="{{ route('tools.copy-count') }}" target="_blank">Text Multiplier Tool</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" 
                                href="{{ route('tools.random') }}" target="_blank">Random</a>
                        </li>

                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" 
                                href="{{ route('index') }}" target="_blank">List Offers</a>
                        </li>

                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" 
                                href="{{ route('tools.cpanel-checker') }}" target="_blank">cPanel Checker</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-auto d-flex align-items-center">
                <button class="navbar-toggler mx-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <button id="themeToggle" class="btn btn-sm btn-outline-light px-3 p-2">
                    <i id="themeIcon" class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="m-auto" style="margin-top: 4rem !important;">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"> -->
    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        // Check for saved theme preference or use preferred color scheme
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
            htmlElement.setAttribute('data-bs-theme', 'dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        }

        themeToggle.addEventListener('click', () => {
            const isDark = htmlElement.getAttribute('data-bs-theme') === 'dark';
            htmlElement.setAttribute('data-bs-theme', isDark ? 'light' : 'dark');
            themeIcon.classList.replace(isDark ? 'fa-sun' : 'fa-moon',
                isDark ? 'fa-moon' : 'fa-sun');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
        });

        // Listen for changes in OS color scheme preference
        prefersDarkScheme.addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    htmlElement.setAttribute('data-bs-theme', 'dark');
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                } else {
                    htmlElement.setAttribute('data-bs-theme', 'light');
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
