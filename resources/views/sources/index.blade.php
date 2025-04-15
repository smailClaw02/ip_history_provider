@extends('layouts.app')

@section('title', 'All Sources')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>IP History Sources</h1>
        <a href="{{ route('sources.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Source
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>IP</th>
                    <th>From</th>
                    <th>SPF</th>
                    <th>DKIM</th>
                    <th>Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sources as $source)
                <tr>
                    <td>{{ $source->id }}</td>
                    <td>{{ $source->ip }}</td>
                    <td>{{ Str::limit($source->from, 20) }}</td>
                    <td>
                        <span class="badge rounded-pill bg-{{ $source->spf === 'pass' ? 'success' : 'danger' }}">
                            {{ strtoupper($source->spf) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-{{ $source->dkim === 'pass' ? 'success' : 'warning' }}">
                            {{ strtoupper($source->dkim) }}
                        </span>
                    </td>
                    <td>{{ $source->date}}</td>
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
    </div>
    
    <div class="d-flex justify-content-center">
        {{ $sources->links() }}
    </div>
</div>
@endsection