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

                        <div class="mb-3">
                            <label for="vmta" class="form-label">VMTA</label>
                            <input type="text" class="form-control" id="vmta" name="vmta" 
                                   value="{{ old('vmta', $source->vmta) }}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="from" class="form-label">From Email</label>
                            <input type="text" class="form-control" id="from" name="from" 
                                   value="{{ old('from', $source->from) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="return_path" class="form-label">Return Path</label>
                            <input type="text" class="form-control" id="return_path" name="return_path" 
                                   value="{{ old('return_path', $source->return_path) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Recipient Email</label>
                            <input type="text" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $source->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="datetime-local" class="form-control" id="date" name="date" 
                                   value="{{ old('date', $source->date->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="colonne" class="form-label">Colonne</label>
                            <input type="text" class="form-control" id="colonne" name="colonne" 
                                   value="{{ old('colonne', $source->colonne) }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="redirect_link" class="form-label">Redirect Link</label>
                    <input type="text" class="form-control" id="redirect_link" name="redirect_link" 
                           value="{{ old('redirect_link', $source->redirect_link) }}">
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
                            <label for="dmarc" class="form-label">DMARC Result</label>
                            <select class="form-select" id="dmarc" name="dmarc" required>
                                @foreach(['pass', 'fail', 'none', 'permerror', 'temperror', 'bestguesspass'] as $option)
                                    <option value="{{ $option }}" {{ $source->dmarc == $option ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="message_path" class="form-label">Message Path</label>
                    <select class="form-select" id="message_path" name="message_path" required>
                        <option value="inbox" {{ $source->message_path == 'inbox' ? 'selected' : '' }}>Inbox</option>
                        <option value="spam" {{ $source->message_path == 'spam' ? 'selected' : '' }}>Spam</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Message Content</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="header" class="form-label">Message Header</label>
                    <textarea class="form-control" id="header" name="header" rows="5" required>{{ old('header', $source->header) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="body" class="form-label">Message Body</label>
                    <textarea class="form-control" id="body" name="body" rows="10" required>{{ old('body', $source->body) }}</textarea>
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