@extends('layouts.app')

@section('title', 'All Sources')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>IP History Provider</h1>

            <div class="row">
                <!-- Hidden Date Filter Form -->
                <div class="col-auto row" id="dateFilterContainer" style="display: none;">
                    <div class="col-auto">
                        <form method="GET" action="{{ route('sources.index') }}" class="input-group">
                            <input type="date" name="date_filter" class="form-control"
                                value="{{ request('date_filter') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if (request('date_filter'))
                                <a href="{{ route('sources.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="col-auto">
                    <button id="toggleDateFilter" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-calendar-alt"></i> Filter by Date
                    </button>
                    <a href="{{ route('sources.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Source
                    </a>
                </div>

            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>
                        <a href="{{ route('sources.index', array_merge(request()->except('sort'), ['sort' => request('sort') === 'asc' ? 'desc' : 'asc'])) }}"
                            class="text-white text-decoration-none">
                            ID
                            @if (request('sort') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @else
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <form method="GET" action="{{ route('sources.index') }}" class="d-flex">
                            <input type="text" name="ip_search" class="form-control form-control-sm"
                               placeholder="Search IP..." value="{{ request('ip_search') }}">
                            <button type="submit" class="btn btn-sm btn-outline-light">
                                <i class="fas fa-search"></i>
                            </button>
                            @if (request('ip_search'))
                                <a href="{{ route('sources.index') }}" class="btn btn-sm btn-outline-danger ms-2">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </form>
                    </th>
                    <th>
                        <div class="">
                            <input class="form-check-input" type="checkbox" id="highlightSimilar">
                            <label class="form-check-label" for="highlightSimilar">Similar</label>
                        </div>
                    </th>
                    <th>From</th>
                    <th>Return-path</th>
                    <th>Domains</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sources as $source)
                    @php
                        $ipParts = explode('.', $source->ip);
                        $ipPrefix = count($ipParts) >= 2 ? implode('.', array_slice($ipParts, 0, 2)) : $source->ip;
                        $similarCount = $ipGroups[$ipPrefix] ?? 1;
                        $hue = abs(crc32($ipPrefix)) % 360;
                        $color = "hsl({$hue}, 80%, 80%)";

                        // Extract domains from from and return_path
                        $domains = [];
                        if (!empty($source->from)) {
                            $fromParts = explode('@', $source->from);
                            if (count($fromParts) > 1) {
                                $domains[] = trim($fromParts[1]);
                            }
                        }
                        if (!empty($source->return_path)) {
                            $returnParts = explode('@', $source->return_path);
                            if (count($returnParts) > 1) {
                                $domain = trim($returnParts[1]);
                                if (!in_array($domain, $domains)) {
                                    $domains[] = $domain;
                                }
                            }
                        }
                    @endphp
                    <tr class="ip-row" data-ip-prefix="{{ $ipPrefix }}"
                        data-similar-count="{{ $similarCount }}" data-highlight-color="{{ $color }}"
                        data-ip="{{ $source->ip }}">
                        <td>{{ $source->id }}</td>
                        <td class="text-center">
                            <span class="ip-address text-center">{{ $source->ip }}</span>
                        </td>
                        <td class="text-center">
                            @if ($similarCount > 1)
                                <span class="badge bg-primary">{{ $similarCount }}</span>
                            @else
                                <span class="badge bg-secondary">1</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sources.show', $source->id) }}" class="text-decoration-none text-from-show">
                                <b>{{ Str::limit($source->from, 30) }}</b>
                            </a>
                        </td>
                        <td>
                            {{ Str::limit($source->return_path, 30) }}
                        </td>
                        <td>
                            @if (!empty($domains))
                                @foreach ($domains as $domain)
                                    {{ $domain }}<br>
                                @endforeach
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge fs-6 bg-{{ $source->message_path === 'inbox' ? 'success' : 'warning' }}">
                                {{-- {{ $source->message_path === 'inbox' ? 'In' : 'Sp' }} --}}
                                {{ $source->message_path }}
                            </span>
                        </td>
                        <td>{{ $source->date->format('m-d-Y H:i') }}</td>
                        <td class="text-end action-btns">
                            <a href="{{ route('sources.edit', $source->id) }}" class="btn btn-sm btn-warning"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('sources.destroy', $source->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this source?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end my-4">
            <nav aria-label="Pagination navigation" class="pagination-dark">
                <ul class="pagination">
                    {{ $sources->appends(request()->query())->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle date filter visibility
            const toggleDateFilter = document.getElementById('toggleDateFilter');
            const dateFilterContainer = document.getElementById('dateFilterContainer');

            toggleDateFilter.addEventListener('click', function() {
                dateFilterContainer.style.display = dateFilterContainer.style.display === 'none' ? 'block' :
                    'none';
            });

            // Highlight similar IPs
            const toggle = document.getElementById('highlightSimilar');

            function applyHighlighting(enable) {
                document.querySelectorAll('.ip-row').forEach(row => {
                    const count = parseInt(row.dataset.similarCount);
                    if (count > 1) {
                        if (enable) {
                            const color = row.dataset.highlightColor;
                            row.style.backgroundColor = color;
                            row.querySelector('.ip-address').style.backgroundColor = color;
                            row.querySelector('.ip-address').style.color = "black";
                        } else {
                            row.style.backgroundColor = '';
                            row.querySelector('.ip-address').style.backgroundColor = '';
                            row.querySelector('.ip-address').style.color = "";
                        }
                    }
                });
            }

            applyHighlighting(toggle.checked);
            toggle.addEventListener('change', function() {
                applyHighlighting(this.checked);
            });
        });
    </script>
@endsection
