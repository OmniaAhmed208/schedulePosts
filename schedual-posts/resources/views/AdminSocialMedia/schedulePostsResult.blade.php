@extends('layouts.layoutAdminSocial')

@section('content')

@php
$timeThink = App\Models\time_think::count();
$data = App\Models\time_think::all();
$timeServer = Carbon\Carbon::now()->format('Y-m-d H:i');
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <section class="content">

                <h4 class="my-4" style="font-weight: bold">schedule Posts</h4>

                <p class="my-4" style="font-size: 20px">
                   Result
                </p>
            </section>
        </div>
    </div>
</div>
@endsection


