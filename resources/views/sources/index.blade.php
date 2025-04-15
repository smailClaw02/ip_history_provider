@extends('layouts.app')

@section('title', 'All Sources')
@section('content')
<div class="m-auto" style="width: 98%;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>IP History Provider</h1>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="highlightSimilar">
            <label class="form-check-label" for="highlightSimilar">Highlight similar IPs</label>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>IP</th>
                <th>Provider IP</th>
                <th>VMTA</th>
                <th>From</th>
                <th>Return-path</th>
                <th class="text-center">SPF</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sources as $source)
            @php
                $ipPrefix = implode('.', array_slice(explode('.', $source->ip), 0, 3));
                $similarCount = $ipGroups[$ipPrefix] ?? 1;
                $color = $similarCount > 1 ? 'hsl('. (abs(crc32($ipPrefix)) % 360 .', 70%, 90%') : '';
            @endphp
            <tr class="ip-row" 
                data-ip-prefix="{{ $ipPrefix }}"
                data-similar-count="{{ $similarCount }}"
                @if($similarCount > 1) data-highlight-color="{{ $color }}" @endif>
                <td>{{ $source->id }}</td>
                <td>
                    <span class="ip-address">{{ $source->ip }}</span>
                    @if($similarCount > 1)
                    <span class="badge bg-dark ms-2">{{ $similarCount }}</span>
                    @endif
                </td>
                <td>{{ $source->provider_ip }}</td>
                <td>{{ $source->vmta }}</td>
                <td>{{ $source->from }}</td>
                <td>{{ $source->return_path }}</td>
                <td class="text-center">
                    <span class="badge fs-6 bg-{{ $source->spf === 'pass' ? 'success' : 'danger' }}">
                        {{ $source->spf }}
                    </span>
                </td>
                <td>{{ $source->date }}</td>
                <td class="text-end action-btns">
                    <a href="{{ route('sources.show', $source->id) }}" class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('sources.edit', $source->id) }}" class="btn btn-sm btn-warning" title="Edit">
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
    
    {{ $sources->links() }}
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('highlightSimilar');
    
    function applyHighlighting(shouldHighlight) {
        document.querySelectorAll('.ip-row').forEach(row => {
            const similarCount = parseInt(row.dataset.similarCount);
            const color = row.dataset.highlightColor;
            
            if (shouldHighlight && similarCount > 1) {
                row.style.backgroundColor = color;
                row.querySelector('.ip-address').style.backgroundColor = color;
            } else {
                row.style.backgroundColor = '';
                row.querySelector('.ip-address').style.backgroundColor = '';
            }
        });
    }
    
    // Initial state
    applyHighlighting(toggle.checked);
    
    // Toggle event
    toggle.addEventListener('change', function() {
        applyHighlighting(this.checked);
    });
});
</script>
@endsection
@endsection