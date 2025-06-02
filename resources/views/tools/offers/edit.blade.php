@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Offer: <b class="text-info">{{ $offer->name }}</b></h1>
    
    <form action="{{ route('update', $offer->id) }}" method="POST" class="row justify-content-between">
        @csrf
        @method('PUT')
        
        <div class="col-4 form-group mb-3">
            <label for="id_offer">Offer ID</label>
            <input type="text" class="form-control" id="id_offer" name="id_offer" value="{{ $offer->id_offer }}" required>
        </div>

        <div class="col-4 form-group mb-3">
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="{{ $offer->category }}" required>
        </div>
        
        <div class="col-4 form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $offer->name }}" required>
        </div>
        
        <div class="col-4 form-group mb-3">
            <label for="rev">Revenue</label>
            <input type="number" step="0.01" class="form-control" id="rev" name="rev" value="{{ $offer->rev }}">
        </div>

        <div class="col-4 form-group mb-3">
            <label for="img">Image URL</label>
            <input type="text" class="form-control" id="img" name="img" value="{{ $offer->img }}">
        </div>
        
        <div class="col-4 form-group mb-3">
            <label for="link_img">Link Image</label>
            <input type="text" class="form-control" id="link_img" name="link_img" value="{{ $offer->link_img }}">
        </div>
        
        <div class="col-4 form-group mb-3">
            <label for="link_uns">Link Uns</label>
            <input type="text" class="form-control" id="link_uns" name="link_uns" value="{{ $offer->link_uns }}">
        </div>
        
        <div class="col-4 form-group mb-3">
            <label for="from">From</label>
            <input type="text" class="form-control" id="from" name="from" value="{{ $offer->from }}">
        </div>
        
        <div class="col-4 form-group mb-3">
            <label for="sub">Sub</label>
            <input type="text" class="form-control" id="sub" name="sub" value="{{ $offer->sub }}">
        </div>
        
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Update Offer</button>
        </div>
    </form>
</div>
@endsection