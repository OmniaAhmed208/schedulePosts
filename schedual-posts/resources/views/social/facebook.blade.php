@extends('layouts.app')

@section('content')

<div class="container">
    @php
        $social_posts = App\Models\social_posts::count();
    @endphp
    @if ($social_posts === 0)
        <a href="{{route('facebook_create')}}">you should choose your page of facebook first to show your posts</a>
    @else

    <div class="container mt-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"  data-masonry='{"percentPosition": true,  "itemSelector": ".col" }'>
            @foreach ($data as $index => $data)
                <div class="col">
                        <div class="card">

                            <div class="user-block d-flex m-3">
                                <img class="img-circle img-bordered-sm rounded-pill" src="{{ $data['page_img'] }}" alt="User Image">
                                <span class="username mx-3 d-flex flex-column justify-content-center">
                                    <a href="{{ $data['page_link'] }}" target="_blank">{{ $data['page_name'] }}</a>
                                    <span class="description">{{ $data['type'] }}</span>
                                </span>
                            </div>

                            <div class="card-body">
                                @if ($data['post_caption'] != null)
                                    {{-- <p>{{ \Illuminate\Support\Str::limit($data['post_caption'], $limit = 50, $end = '...') }}</p> --}}
                                    <p>{{ $data['post_caption'] }}</p>
                                @endif

                                @if ($data['post_img'] != null)
                                     <img src="{{ $data['post_img'] }}" class="img-fluid mb-2" alt="" style="padding: 8px 8px;border: 1px solid #ddd;">
                                @endif
                                <br>
                                <a href="{{ $data['post_link'] }}">view on facebook</a>
                            </div>
                            
                        </div>
                </div>
            @endforeach
        </div>
    </div>

    @endif
</div>

@endsection
