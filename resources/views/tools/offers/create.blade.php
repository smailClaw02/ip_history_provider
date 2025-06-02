@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Offers</h1>
    
    <form action="{{ route('store') }}" method="POST">
        @csrf
        
        <div class="form-group mb-3">
            <label for="offers_text">Offers (one per line)</label>
            <textarea class="form-control" id="offers_text" name="offers_text" rows="10" 
                      placeholder="Enter All offers : Example: N700NameIdUS" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Add Offers</button>
    </form>
</div>
@endsection