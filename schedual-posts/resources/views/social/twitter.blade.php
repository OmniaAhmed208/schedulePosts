@extends('layouts.app')

@section('content')

@php
    $userInfo = App\Models\Api::all()->where('creator_id', Auth::user()->id)->where('user_account_id',$twitterId); 
    $posts = App\Models\social_posts::all()->where('page_id',$twitterId);
@endphp
<div class="container">
    <div class="container mt-5">

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"  data-masonry='{"percentPosition": true,  "itemSelector": ".col" }'>
            @foreach ($posts as $index => $post)
                    <div class="col">
                        <div class="card">
                            <div class="user-block d-flex m-3">
                                @foreach ($userInfo as $user)
                                    <img class="img-circle img-bordered-sm rounded-pill" src="{{ $user['user_pic'] }}" alt="User Image">
                                    <span class="username mx-3 d-flex flex-column justify-content-center">
                                        <a href="{{ $post['page_link'] }}" target="_blank">{{ $user['user_name'] }}</a>
                                        <span class="description m-0">{{ $user['social_type'] }}</span>
                                    </span>
                                @endforeach
                            </div>

                            <div class="card-body">
                                {{-- <p>{{ \Illuminate\Support\Str::limit($post['post_caption'], $limit = 50, $end = '...') }}</p> --}}
                                <p>{{ $post['post_caption'] }}</p>

                                <iframe src="https://www.youtube.com/embed/{{ $post['post_id'] }}" allowfullscreen></iframe>
                                {{-- <img src="{{ $post['post_img'] }}" class="img-fluid mb-2" alt="" style="padding: 8px 8px;border: 1px solid #ddd;"> --}}
                                
                                <br>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ $post['post_link']}}">View on youtube</a>
                                    {{-- <p>{{ date('d M Y', strtotime($post['post_date'])) }}</p> --}}
                                </div>
                            </div>
                            
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</div>


@endsection
