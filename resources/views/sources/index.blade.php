@extends('layouts.app')

@section('title', 'All Sources')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>IP History Provider</h1>
        <a href="{{ route('sources.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Source
        </a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>IP</th>
		<th>Similar</th>
                <th>Provider IP</th>
                <th>VMTA</th>
                <th>From</th>
                <th>Return-path</th>
                <th>Message Path</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
           @foreach($sources as $source)
            @php
                // Get first 3 octets of IP
                $ipParts = explode('.', $source->ip);
                $ipPrefix = count($ipParts) >= 3 ? implode('.', array_slice($ipParts, 0, 3)) : $source->ip;
                $similarCount = $ipGroups[$ipPrefix] ?? 1;
                
                // Generate consistent color based on IP prefix
                $hue = abs(crc32($ipPrefix)) % 360;
                $color = "hsl({$hue}, 80%, 85%)";
            @endphp
            <tr class="ip-row" 
                data-ip-prefix="{{ $ipPrefix }}"
                data-similar-count="{{ $similarCount }}"
                data-highlight-color="{{ $color }}">
                <td>{{ $source->id }}</td>
                <td>
                    <span class="ip-address">{{ $source->ip }}</span>
                </td>
                <td class="text-center">
                    @if ($similarCount > 1)
    <span class="badge bg-success">{{ $similarCount }}</span>
@elseif ($similarCount == 1)
    <span class="badge bg-primary">1</span>
@endif
                </td>
                <td>{{ $source->provider_ip }}</td>
                <td>{{ $source->vmta }}</td>
                <td>{{ $source->from }}</td>
                <td>{{ $source->return_path }}</td>
                <td class="text-center">
                    <span class="badge fs-6 text-dark bg-{{ $source->message_path === 'inbox' ? 'success' : 'warning' }}">
                        {{ $source->message_path }}
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
    
    function applyHighlighting(enable) {
        document.querySelectorAll('.ip-row').forEach(row => {
            const count = parseInt(row.dataset.similarCount);
            if (count > 1) {
                if (enable) {
                    const color = row.dataset.highlightColor;
                    row.style.backgroundColor = color;
                    row.querySelector('.ip-address').style.backgroundColor = color;
                } else {
                    row.style.backgroundColor = '';
                    row.querySelector('.ip-address').style.backgroundColor = '';
                }
            }
        });
    }
    
    // Initialize
    applyHighlighting(toggle.checked);
    
    // Toggle event
    toggle.addEventListener('change', function() {
        applyHighlighting(this.checked);
    });
});
</script>
@endsection
@endsection