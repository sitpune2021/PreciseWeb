@extends('layouts.header')

@section('content')
<div class="container text-center  "style="margin-top:150px;" >
    <h2 class="text-danger">Payment Failed</h2>
    <p>Your payment could not be processed.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
        Try Again
    </a>
</div>
@endsection