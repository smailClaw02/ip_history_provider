@extends('layouts.app')

@section('title', 'Source Details')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Source Details #{{ $source->id }}</h1>
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
                    <p><strong>IP Address:</strong> <code>{{ $source->ip }}</code></p>
                    <p><strong>Provider IP:</strong> <code>{{ $source->provider_ip }}</code></p>
                    <p><strong>VMTA:</strong> {{ $source->vmta }}</p>
                    <p><strong>From:</strong> {{ $source->from }}</p>
                    <p><strong>Return Path:</strong> {{ $source->return_path }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Email:</strong> {{ $source->email }}</p>
                    <p><strong>Date:</strong> {{ $source->date }}</p>
                    <p><strong>Redirect Link:</strong> 
                        <a href="{{ $source->redirect_link }}" target="_blank">{{ $source->redirect_link }}</a>
                    </p>
                    <div class="mt-3">
                        <strong>Domains:</strong>
                        <ul class="list-inline">
                            @foreach(json_decode($source->domains) as $domain)
                            <li class="list-inline-item"><span class="badge bg-secondary">{{ $domain }}</span></li>
                            @endforeach
                        </ul>
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
                <div class="col-md-4">
                    <p><strong>SPF:</strong> 
                        <span class="badge rounded-pill bg-{{ $source->spf === 'pass' ? 'success' : 'danger' }}">
                            {{ strtoupper($source->spf) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><strong>DKIM:</strong> 
                        <span class="badge rounded-pill bg-{{ $source->dkim === 'pass' ? 'success' : 'warning' }}">
                            {{ strtoupper($source->dkim) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><strong>DMARC:</strong> 
                        <span class="badge rounded-pill bg-{{ $source->dmark === 'pass' ? 'success' : 'danger' }}">
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
            <h5>Message Header:</h5>
            <div class="email-header mb-4">{{ $source->header }}</div>
            
            <h5>Message Body:</h5>
            <div class="email-body">{{ $source->body }}</div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('sources.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection