@extends('layouts.app')

@section('content')
<div class="m-auto" style="width: 98%">
    <div class="row justify-content-between mb-2 align-items-center">
        <h1 class="col-auto">List of Offers</h1>
        <div class="col-auto">
            <a href="{{ route('create') }}" class="btn btn-primary">Create New Offers</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Offer ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Revenue</th>
                <th>Lead</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offers as $offer)
            <tr data-id="{{ $offer->id }}">
                <td>{{ $offer->id }}</td>
                <td>{{ $offer->id_offer }}</td>
                <td>{{ $offer->category }}</td>
                <td>
                    <a href="{{ route('show', $offer->id) }}" 
                       class="text-decoration-none text-info">
                        {{ $offer->name }}
                    </a>
                </td>
                <td>$ {{ number_format($offer->rev, 2) }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <button onclick="updateLeadCount({{ $offer->id }}, 'decrement')" 
                                class="btn btn-sm btn-danger me-1" 
                                @if($offer->count_lead <= 0) disabled @endif>-</button>
                        <span class="lead-count">{{ $offer->count_lead }}</span>
                        <button onclick="updateLeadCount({{ $offer->id }}, 'increment')" 
                                class="btn btn-sm btn-success ms-1">+</button>
                    </div>
                </td>
                <td>
                    @if($offer->img && $offer->img !== 'not found')
                        <img src="{{ $offer->img }}" 
                             alt="{{ $offer->name }}" 
                             style="max-width: 100px; height: auto;" 
                             class="img-thumbnail"
                             onerror="this.onerror=null;this.src='https://via.placeholder.com/100?text=Image+Error'">
                    @else
                        <span class="badge bg-secondary">No Image</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('edit', $offer->id) }}" 
                       class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('destroy', $offer->id) }}" 
                          method="POST" 
                          style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger btn-sm" 
                                onclick="return confirm('Are you sure you want to delete this offer?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end my-4">
        {{ $offers->links() }}
    </div>
</div>

<!-- Error Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

<script>
    // Auto-dismiss success alert
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        }
    });

    // Unified function for both increment and decrement
    function updateLeadCount(offerId, action) {
        const button = event.target;
        const originalText = button.innerHTML;
        const endpoint = action === 'increment' 
            ? `/tools/offers/${offerId}/increment-lead` 
            : `/tools/offers/${offerId}/decrement-lead`;

        // Show loading state
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        button.disabled = true;

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update count display
                const row = document.querySelector(`tr[data-id="${offerId}"]`);
                if (row) {
                    const countElement = row.querySelector('.lead-count');
                    const decrementBtn = row.querySelector('.btn-danger');
                    
                    if (countElement) countElement.textContent = data.newCount;
                    if (decrementBtn) decrementBtn.disabled = data.newCount <= 0;
                }
                
                // Show success feedback
                button.innerHTML = '✓';
                setTimeout(() => {
                    button.innerHTML = action === 'increment' ? '+' : '-';
                    button.disabled = false;
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to update lead count');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Failed to update lead count');
            
            // Reset button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    function showToast(message) {
        const toastEl = document.getElementById('errorToast');
        const toastMessage = document.getElementById('toastMessage');
        if (toastEl && toastMessage) {
            toastMessage.textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }
</script>

<style>
    .btn-count {
        width: 30px;
        padding: 0.25rem;
    }

    .lead-count {
        min-width: 30px;
        text-align: center;
        display: inline-block;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    .img-thumbnail {
        max-height: 60px;
        object-fit: contain;
    }
</style>
@endsection