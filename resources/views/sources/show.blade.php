@extends('layouts.app')

@section('title', 'Source Details')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="border border-secondary border-2 rounded-3">
            <a href="{{ route('sources.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a> <b class="fs-5 p-2 ">Source #{{ $source->id }}</b>
        </div>
        
        <div class="action-btns">
            <a href="{{ route('sources.edit', $source->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('sources.destroy', $source->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                    onclick="return confirm('Are you sure you want to delete this source?')">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Message Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyable" data-value="{{ $source->ip }}"><strong>IP Address:</strong> <code>{{ $source->ip }}</code></p>
                    <p class="copyable" data-value="{{ $source->provider_ip }}"><strong>Provider IP:</strong> <code>{{ $source->provider_ip }}</code></p>
                    <p class="copyable" data-value="{{ $source->vmta }}"><strong>VMTA:</strong> {{ $source->vmta }}</p>
                    <p class="copyable" data-value="{{ $source->from }}"><strong>From:</strong> {{ $source->from }}</p>
                    <p class="copyable" data-value="{{ $source->return_path }}"><strong>Return-Path:</strong> {{ $source->return_path }}</p>
                </div>
                <div class="col-md-6">
                    <p class="copyable" data-value="{{ $source->email }}"><strong>Email:</strong> {{ $source->email }}</p>
                    <p class="copyable" data-value="{{ $source->date }}"><strong>Date:</strong> {{ $source->date }}</p>
                    <p class="copyable" data-value="{{ $source->redirect_link }}"><strong>Redirect Link:</strong> 
                        <a href="#">{{ $source->redirect_link }}</a>
                    </p>
                    <div class="mt-3">
                        <strong>Domains:</strong>
                        <textarea class="form-control domains-textarea" rows="5" readonly>
@foreach(json_decode($source->domains) as $domain)
{{ $domain }}
@endforeach
                        </textarea>
                        <button class="btn btn-sm btn-secondary mt-2 copy-domains">Copy Domains</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Authentication Results</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p><strong>Message Path:</strong> 
                        <span class="badge fs-6 bg-{{ $source->message_path === 'inbox' ? 'success' : 'warning' }}">
                            {{ strtoupper($source->message_path) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-3">
                    <p><strong>SPF:</strong> 
                        <span class="badge fs-6 bg-{{ $source->spf === 'pass' ? 'success' : 'danger' }}">
                            {{ strtoupper($source->spf) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-3">
                    <p><strong>DKIM:</strong> 
                        <span class="badge fs-6 bg-{{ $source->dkim === 'pass' ? 'success' : 'warning' }}">
                            {{ strtoupper($source->dkim) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-3">
                    <p><strong>DMARC:</strong> 
                        <span class="badge fs-6 bg-{{ $source->dmark === 'pass' ? 'success' : 'danger' }}">
                            {{ strtoupper($source->dmark) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Email Content</h4>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Header:</h5>
                    <button class="btn btn-sm btn-primary copy-header">Copy Header</button>
                </div>
                <textarea rows="5" class="form-control email-header" readonly>{{ $source->header }}</textarea>
            </div>
            
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Body:</h5>
                    <button class="btn btn-sm btn-primary copy-body">Copy Body</button>
                </div>
                <textarea rows="5" class="form-control email-body" readonly>{{ $source->body }}</textarea>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy individual fields on click
    document.querySelectorAll('.copyable').forEach(element => {
        element.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            copyToClipboard(value);
            showCopiedTooltip(this);
        });
    });

    // Copy domains
    document.querySelector('.copy-domains').addEventListener('click', function() {
        const textarea = document.querySelector('.domains-textarea');
        copyToClipboard(textarea.value);
        showCopiedTooltip(this);
    });

    // Copy header
    document.querySelector('.copy-header').addEventListener('click', function() {
        const textarea = document.querySelector('.email-header');
        copyToClipboard(textarea.value);
        showCopiedTooltip(this);
    });

    // Copy body
    document.querySelector('.copy-body').addEventListener('click', function() {
        const textarea = document.querySelector('.email-body');
        copyToClipboard(textarea.value);
        showCopiedTooltip(this);
    });

    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }

    function showCopiedTooltip(element) {
        const originalText = element.innerHTML;
        element.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(() => {
            element.innerHTML = originalText;
        }, 2000);
    }
});
</script>

<style>
.copyable {
    cursor: pointer;
    padding: 5px;
    border-radius: 3px;
    transition: background-color 0.2s;
}

.copyable:hover {
    background-color: #f0f0f0;
}

.copyable:active {
    background-color: #e0e0e0;
}

.email-header, .email-body, .domains-textarea {
    font-family: monospace;
    white-space: pre;
    overflow-x: auto;
}
</style>
@endsection