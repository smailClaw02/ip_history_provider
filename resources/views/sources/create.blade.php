@extends('layouts.app')

@section('title', isset($source) ? 'Edit Source' : 'Create New Source')
@section('content')
<div class="container">
    <h1>{{ isset($source) ? 'Edit Source' : 'Create New Source' }}</h1>

    <form action="{{ isset($source) ? route('sources.update', $source->id) : route('sources.store') }}" method="POST">
        @csrf
        @if(isset($source))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Basic Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="ip" class="form-label">IP Address</label>
                            <input type="text" class="form-control" id="ip" name="ip" 
                                   value="{{ old('ip', $source->ip ?? '') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="provider_ip" class="form-label">Provider IP</label>
                            <input type="text" class="form-control" id="provider_ip" name="provider_ip" 
                                   value="{{ old('provider_ip', $source->provider_ip ?? '') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="from" class="form-label">From Email</label>
                            <input type="email" class="form-control" id="from" name="from" 
                                   value="{{ old('from', $source->from ?? '') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Authentication Results</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="spf" class="form-label">SPF Result</label>
                            <select class="form-select" id="spf" name="spf" required>
                                @foreach(['pass', 'fail', 'softfail', 'neutral', 'none', 'permerror', 'temperror'] as $option)
                                    <option value="{{ $option }}" {{ (old('spf', $source->spf ?? '') == $option) ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dkim" class="form-label">DKIM Result</label>
                            <select class="form-select" id="dkim" name="dkim" required>
                                @foreach(['pass', 'fail', 'none', 'permerror', 'temperror', 'policy'] as $option)
                                    <option value="{{ $option }}" {{ (old('dkim', $source->dkim ?? '') == $option) ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">Message Content</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="header" class="form-label">Message Header</label>
                    <textarea class="form-control" id="header" name="header" rows="3">{{ old('header', $source->header ?? '') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="body" class="form-label">Message Body</label>
                    <textarea class="form-control" id="body" name="body" rows="5">{{ old('body', $source->body ?? '') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="{{ route('sources.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ isset($source) ? 'Update' : 'Create' }} Source
            </button>
        </div>
    </form>
</div>
@endsection