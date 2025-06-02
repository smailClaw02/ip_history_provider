@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Offer Details: <span class="text-info">{{ $offer->name }}</span></h1>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID:</th>
                    <td>{{ $offer->id }}</td>
                </tr>
                <tr>
                    <th>Offer ID:</th>
                    <td>{{ $offer->id_offer }}</td>
                </tr>
                <tr>
                    <th>Category:</th>
                    <td>{{ $offer->category }}</td>
                </tr>
                <tr>
                    <th>Revenue:</th>
                    <td>$ {{ number_format($offer->rev, 2) }}</td>
                </tr>
                <tr>
                    <th>Image:</th>
                    <td>
                        @if($offer->img && $offer->img !== 'not found')
                            <img src="{{ $offer->img }}" alt="{{ $offer->name }}" style="max-width: 200px;">
                        @else
                            <span class="badge bg-secondary">No Image</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Link Image:</th>
                    <td>{{ $offer->link_img }}</td>
                </tr>
                <tr>
                    <th>Link Uns:</th>
                    <td>{{ $offer->link_uns }}</td>
                </tr>
                <tr>
                    <th>From:</th>
                    <td>{{ $offer->from }}</td>
                </tr>
                <tr>
                    <th>Sub:</th>
                    <td>{{ $offer->sub }}</td>
                </tr>
                <tr>
                    <th>Lead Count:</th>
                    <td>{{ $offer->count_lead }}</td>
                </tr>
            </table>
            <a href="{{ route('index') }}" class="btn btn-primary">Back to List</a>
        </div>
    </div>
</div>
@endsection