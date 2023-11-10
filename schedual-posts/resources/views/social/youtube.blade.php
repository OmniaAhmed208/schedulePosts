@extends('layouts.app')

@section('content')

@php
    $userInfo = App\Models\Api::where('creator_id', Auth::user()->id)->where('account_id',$channelId)->first(); 
    $videosData = App\Models\Api::find($userInfo->id)->social_posts()->get();
@endphp

{{-- @dd($videosData); --}}
<div class="container">
    <div class="container mt-5">

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"  data-masonry='{"percentPosition": true,  "itemSelector": ".col" }'>

            @foreach ($videosData as $index => $video)
                    <div class="col">
                        <div class="card">
                            <div class="user-block d-flex m-3">
                                <img class="img-circle img-bordered-sm rounded-pill" src="{{ $userInfo->account_pic }}" alt="User Image">
                                <span class="username mx-3 d-flex flex-column justify-content-center">
                                    <a href="{{ $userInfo->account_link }}" target="_blank">{{ $userInfo->account_name }}</a>
                                    <span class="description m-0">{{ $userInfo->account_type }}</span>
                                </span>
                            </div>

                            <div class="card-body">
                                {{-- <p>{{ \Illuminate\Support\Str::limit($video['post_caption'], $limit = 50, $end = '...') }}</p> --}}
                                <p>{{ $video['content'] }}</p>

                                <iframe src="https://www.youtube.com/embed/{{ $video['post_id'] }}" allowfullscreen></iframe>
                                {{-- <img src="{{ $video['post_img'] }}" class="img-fluid mb-2" alt="" style="padding: 8px 8px;border: 1px solid #ddd;"> --}}
                                
                                <br>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ $video['post_link']}}">View on youtube</a>
                                    {{-- <p>{{ date('d M Y', strtotime($video['post_date'])) }}</p> --}}
                                </div>
                            </div>
                            
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
