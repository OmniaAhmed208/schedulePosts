@extends('layouts.layoutAdminSocial')

@section('content')

@php
$timeThink = App\Models\time_think::where('creator_id', Auth::user()->id)->count();
$data = App\Models\time_think::where('creator_id', Auth::user()->id)->first();
$timeServer = Carbon\Carbon::now()->format('Y-m-d H:i');
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <section class="content">

                <h4 class="my-4" style="font-weight: bold">Update Posts Now</h4>

                <p class="my-4" style="font-size: 20px">
                    If you want to get the new posts which uploaded on your app now
                    <br><br>
                    Click here  <i class="far fa-hand-point-right"></i>
                   <span class="btn btn-success mx-3 checkPosts">check new posts</span>
                </p>

            </section>
        </div>
    </div>
</div>

<script>
    let checkPosts = document.querySelector('.checkPosts');
    checkPosts.onclick = function(){
        fetchData();
        alert('Posts updated successfully');
    }
</script>
@endsection


