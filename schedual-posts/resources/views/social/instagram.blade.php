@extends('layouts.app')

@section('content')

<div class="container">
    
    @php
        $apiCount = App\Models\Instagram::count();
        $apiData = App\Models\Instagram::all()->last();
    @endphp

    @if ($apiCount === 0 || $apiData->insta_token == null || $httpCode != 200)
        @if ($httpCode != 200 && $httpCode != '')
            <div class="alert alert-danger">Access token is invalid or caused an error HTTP {{ $httpCode }} in instagram.'</div>
        @endif
        <a href="{{route('insta_create')}}">you should enter your page of Instagram first to show your posts</a>
    @else

    <div class="container mt-5">
      <div class="row row-cols-1 row-cols-md-3 g-4"  data-masonry='{"percentPosition": true,  "itemSelector": ".col" }'>
        @foreach ($fullPictures as $index => $photo)
          @php
              $link = $fullLinks[$index];
          @endphp
              <div class="col">
                      <div class="card">

                          {{-- <div class="user-block m-3">
                              <img class="img-circle img-bordered-sm" src="{{ $photo }}" alt="User Image">
                              <span class="username">
                                <a href="" target="_blank"></a>
                              </span>
                              <span class="description"></span>
                          </div> --}}

                          <div class="card-body">

                                   <img src="{{ $photo }}" class="img-fluid mb-2" alt="" style="padding: 8px 8px;border: 1px solid #ddd;">
                              <br>
                              <a href="{{ $link }}">View on Instagram</a>
                          </div>
                          
                      </div>
              </div>
          @endforeach
      </div>
  </div>


        {{-- <div class="card card-info m-4">
            <div class="card-header">
              <h3 class="card-title">Instagram</h3>
            </div>

            <div class="card-body socialApi">
                
                <div class="row">
                    @foreach ($fullPictures as $index => $photo)
                        @php
                            $link = $fullLinks[$index];
                        @endphp

                        <div class="col-sm-3 position-relative">
                            <a href="{{ $link }}" class="position-relative d-flex justify-content-center align-items-center w-100">
                                <img src="{{ $photo }}" class="img-fluid" alt="">
                                <span class="position-absolute text-white justify-content-center align-items-center">
                                    <i class="fa-brands fa-instagram"></i>
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div> 

            </div>

        </div> --}}

    @endif
</div>

@endsection

{{-- <div id="instafeed" class="owl-carousel"></div> --}}

{{-- 
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Instagram Feeds</title>
</head>
<body>
<!-- title -->
<div class="text-center p-10">
    <h1 class="font-bold text-4xl mb-4">Result Get Data From Instagram Posts</h1>
  </div>
  <section id="Projects" class="w-fit mx-auto grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 justify-items-center justify-center gap-y-20 gap-x-14 mt-10 mb-5">
    @foreach ($instagram_posts as $post)
    <div class="w-72 bg-white shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl">
      <a href="#">
        <img src={{ $post->path }} alt="Product" class="h-80 w-72 object-cover rounded-t-xl" />
        <div class="px-4 py-3 w-72">
          <span class="text-gray-400 mr-3 text-xs">{{  $post->caption }}</span>
        </div>
      </a>
    </div>
    @endforeach
  </section>
</body>
</html> --}}