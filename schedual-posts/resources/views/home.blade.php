@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <div class="container">

        @if (Auth::user()->user_type === 'admin')
            <a href="{{ route('adminSocail') }}" class="btn btn-success">Admin Social Media</a>

        @else
            {{ __('Dashboard') }}
        @endif
    </div>

</div>
@endsection
