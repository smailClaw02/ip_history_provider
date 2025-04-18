<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-2">
        <div class="row w-100 justify-content-between">
            <div class="col-auto">
                <a class="navbar-brand mx-3" href="{{ route('sources.index') }}"><b class="shado">IP History Provider</b></a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="col-auto ">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6"
                                href="{{ route('sources.create') }}">Header</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6"
                                href="{{ route('sources.create') }}">Body</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" href="{{ route('tools.spf-dmarc') }}">SPF &
                                DMARC</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" href="{{ route('tools.end-time-drop') }}">End
                                Time Drop</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6"
                                href="{{ route('tools.x-delay') }}">X-delay</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6" href="{{ route('tools.copy-count') }}">Text Multiplier Tool</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link badge bg-secondary p-2 fs-6"
                                href="{{ route('tools.random') }}">Random</a>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </nav>

    <div class="m-auto">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
