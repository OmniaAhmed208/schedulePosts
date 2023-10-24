@extends('layouts.layoutAdminSocial')

@section('content')

@php
$timeThink = App\Models\time_think::where('creator_id', Auth::user()->id)->first();
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <section class="content">

                <h4 class="my-4" style="font-weight: bold">Check for pending posts</h4>

                    <p class="my-4" style="font-size: 20px">
                        if you want check there exist any posts are pending or not
                        <br><br>

                        @if ($timeThink)
                            Click here  <i class="far fa-hand-point-right"></i>
                            <a href="{{ route('checkPostStatus') }}"  class="btn btn-danger mx-3">Check Post Stauts</a> {{-- cron --}}

                        @else

                            Register difference of time first then back to here  <i class="far fa-hand-point-right"></i>
                            <a href="{{ route('timeThink') }}"  class="btn btn-info mx-3">Think time</a>
                
                        @endif

                    </p>
                
            </section>
        </div>
    </div>
</div>
@endsection


