@extends('layouts.app')

@section('title', 'Edit Source')
@section('content')
<div class="container">
    <h1>Edit Source #{{ $source->id }}</h1>

    <form action="{{ route('sources.update', $source->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header">Source Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ip" class="form-label">IP Address</label>
                            <input type="text" class="form-control" id="ip" name="ip" 
                                   value="{{ old('ip', $source->ip) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="provider_ip" class="form-label">Provider IP</label>
                            <input type="text" class="form-control" id="provider_ip" name="provider_ip" 
                                   value="{{ old('provider_ip', $source->provider_ip) }}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="from" class="form-label">From Email</label>
                            <input type="email" class="form-control" id="from" name="from" 
                                   value="{{ old('from', $source->from) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Recipient Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $source->email) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Authentication Results</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="spf" class="form-label">SPF Result</label>
                            <select class="form-select" id="spf" name="spf" required>
                                @foreach(['pass', 'fail', 'softfail', 'neutral', 'none', 'permerror', 'temperror'] as $option)
                                    <option value="{{ $option }}" {{ $source->spf == $option ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="dkim" class="form-label">DKIM Result</label>
                            <select class="form-select" id="dkim" name="dkim" required>
                                @foreach(['pass', 'fail', 'none', 'permerror', 'temperror', 'policy'] as $option)
                                    <option value="{{ $option }}" {{ $source->dkim == $option ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="dmark" class="form-label">DMARC Result</label>
                            <select class="form-select" id="dmark" name="dmark" required>
                                @foreach(['pass', 'fail', 'none', 'permerror', 'temperror'] as $option)
                                    <option value="{{ $option }}" {{ $source->dmark == $option ? 'selected' : '' }}>
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
                    <textarea class="form-control" id="header" name="header" rows="3" required>{{ old('header', $source->header) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="body" class="form-label">Message Body</label>
                    <textarea class="form-control" id="body" name="body" rows="5" required>{{ old('body', $source->body) }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('sources.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Source
            </button>
        </div>
    </form>
</div>
@endsection