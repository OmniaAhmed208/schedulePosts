@extends('layouts.layoutAdminSocial')

@section('content')

@php
    $userApps = App\Models\Api::where('creator_id', Auth::user()->id)->distinct()->pluck('social_type'); // App of user regesterd in
    $channels = App\Models\Api::all()->where('social_type', 'youtube')->where('creator_id', Auth::user()->id);
    $timeThink = App\Models\time_think::where('creator_id', Auth::user()->id)->first();
    $youtubeCategories = App\Models\youtube_category::all();
@endphp


<div class="content-wrapper publishPostContainer">
    <div class="content-header">
        <div class="container">
            <section class="content">
                
                {{-- @if (\Session::has('postCreated'))
                    <div class="alert alert-success my-4">
                        {!! \Session::get('postCreated') !!}
                    </div>
                @endif --}}

                {{-- <video style="width:200px;height:200px" src="{{ url('storage/app/uploads/vecteezy_square-shape-tech-background-hud-small-squares-shape-loop_13449649_472.mp4') }}"></video> --}}
                

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Create Post</h3>
                        <div class="card-tools socialIcons" id="socialIcons"></div>
                    </div>

                    <form class="form-horizontal" action="{{ url('storePosts')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        <div class="card-body">
                            @if (!$timeThink)
                                <h5>Register difference of time first then back to here <i class="far fa-hand-point-right text-info mx-2"></i> 
                                    <a href="{{ route('timeThink') }}"  class="btn btn-info">Think time</a>
                                </h5>
                            @endif

                            @if ($userApps->isNotEmpty())
                                <div class="row container">
                                    <div class="form-group col-md-6 col-lg-6 col-sm-6">
                                        <label>Choose app</label>
                                        <select class="select2" multiple="multiple" data-placeholder="choose app" style="width: 100%;" name="apps[]" id="appSelect" required>
                                            @foreach ($userApps as $app)
                                                <option value="{{ $app }}">{{ $app }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <h5>You should login first form here <i class="far fa-hand-point-right text-info mx-2"></i> 
                                    <a href="{{ route('socialAccounts') }}" class="btn btn-info">Login</a>
                                </h5>
                            @endif
                            
                            @if ($userApps->contains('facebook'))
                                <div class="form-group container" id="facebookPageSelectBlock" style="display: none;">
                                    <label>Choose page on facebook</label>
                                    <select class="form-control select2 w-100" name="page">
                                        @foreach($pages as $page)
                                            <option value="{{$page['name']}}">{{$page['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($userApps->contains('youtube'))
                                <div class="form-group container" id="youtubePageSelectBlock" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="mb-2">Choose channel on youtube</label>
                                            <select class="form-control select2 w-100" name="channel">
                                                @foreach($channels as $channel)
                                                    <option value="{{$channel['user_account_id']}}">{{$channel['user_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-2">Choose privacy status on youtube</label>
                                            <select class="form-control select2 w-100" name="status">
                                                <option value="public">Public</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 my-3">
                                            <select class="form-control select2 w-100" name="youtubeCategory">
                                                <option selected disabled>Choose category of video on youtube</option>
                                                @foreach($youtubeCategories as $category)
                                                    <option value="{{$category['category_id']}}">{{$category['category_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 my-3">
                                            <input type="text" class="form-control mb-3" name="youtubeTags" placeholder="Tag1,Tag2,...">
                                        </div>
                                        <div class="col-md-6 my-3">
                                            <input type="text" class="form-control mb-3" name="videoTitle" placeholder="Enter video title for youtube" required>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="card-body">
                                <div class="form-group row mb-3 flex-column">
                                    <div class="form-group border border-gray rounded">
                                        <textarea id="" cols="30" rows="4" class="form-control border-0" name="postData" placeholder="Whta's on your mind?"></textarea>
                                        <div class="container">
                                            <div class="photoSec previewSec pb-4"></div>
                                            
                                            <div class="card py-2 px-4 mt-2 d-flex flex-row justify-content-between align-items-center">
                                                <p class="m-0">Add to your post</p>
                                                <div class="d-flex position-relative">
                                                    <div class="file position-absolute" id="imgFile">
                                                        <input type="file" class="form-control position-absolute" name="image" onchange="getImagePreview(event)" accept=".jpg, .jpeg, .png, .gif" multiple>
                                                        <i class="fas fa-photo-video text-success px-2"></i>
                                                    </div>
                                                    <div class="file position-absolute" id="videoFile">
                                                        <input type="file" class="form-control position-absolute" name="video" onchange="getVideoPreview(event)" accept="video/*">
                                                        <i class="fas fa-video text-primary px-2"></i>
                                                    </div>
                                                    <i class="fas fa-link text-info mx-1 mt-1 postLink" data-toggle="modal" data-target="#modal-default"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        <i class="far fa-calendar-times text-info mr-2"></i>
                                        <label class="pt-1">schedule your post</label> 
                                        <i class="far fa-hand-point-right text-info mx-2"></i>
                                        <input type="checkbox" id="checkDate" data-bootstrap-switch onchange="statusChange()">
                                    </div>

                                    <div class="form-group w-25 schedule" style="display: none">
                                        <div class="row">
                                            <input type="datetime-local" id="scheduledTime" class="form-control mb-0" name="scheduledTime">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer rounded">
                                @if (($userApps->isNotEmpty()) && $timeThink)
                                    <button type="submit" class="btn btn-info publishBtn">Publish</button>
                                @endif
                                <a href="{{ route('adminSocail') }}" class="btn btn-secondary float-right">Cancel</a>
                            </div>
                        </div>

                        <div class="modal fade" id="modal-default">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header bg-info">
                                  <h4 class="modal-title">Add Link</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" class="form-control" name="link" placeholder="link">
                                    <br>
                                    <p>
                                        This link for facebook only.... <br>
                                        if you have an iamge, the link not allowed and recommended to put it in your text.
                                    </p>
                                </div>
                                <div class="modal-footer justify-content-end">
                                    <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close">Ok</button>
                                </div>
                              </div>
                            </div>
                        </div>
                          <!-- /.modal -->

                    </form>
                </div>

            </section>
        </div>
    </div>
</div>


<script>
    // show image after choose it 
    function getImagePreview(event){
        // console.log(event.target.files[0]);
        for(let i = 0; i<event.target.files.length; i++)
        {
            var img = URL.createObjectURL(event.target.files[i]);
            var container = document.querySelector('.previewSec');
            // container.innerHTML = '';
            var html = `<img src="${img}">
            <span aria-hidden="true" style="cursor:pointer;margin-right: 6px;" onclick="closeFile(this)">&times;</span>`;
            container.innerHTML += html;
        }  
    }

    function getVideoPreview(event){
        for(let i = 0; i<event.target.files.length; i++)
        {
            var video = URL.createObjectURL(event.target.files[i]);
            var container = document.querySelector('.previewSec');
            var html = `<video src="${video}"></video>
            <span aria-hidden="true" style="cursor:pointer;margin-right: 6px;" onclick="closeFile(this)">&times;</span>`;
            container.innerHTML += html;
        }  
    }
    
    function closeFile(closeButton) {
        var container = document.querySelector('.previewSec'); // Find the parent div
        var file = closeButton.previousElementSibling; // Find the img element next to the clicked span (close button)
        if (file) {
            container.removeChild(file);
            container.removeChild(closeButton);
        }
    }

    function statusChange(){
        if (document.getElementById("checkDate").checked) {
            document.querySelector('.schedule').style.display = 'block';
            document.querySelector('.publishBtn').innerHTML = 'schedule';
        }
        else {
            document.querySelector('.schedule').style.display = 'none';
            document.querySelector('.publishBtn').innerHTML = 'Publish';
        }
    }
</script>


@endsection





