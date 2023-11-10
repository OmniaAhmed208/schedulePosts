@extends('layouts.layoutAdminSocial')

@section('content')

<div class="content-wrapper publishPostContainer">
    <div class="content-header">
        <div class="container">
            <section class="content">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                
                <div class="card p-3">
                    <div class="card-header border-0">
                        <div class="card-tools socialIcons" id="socialIcons"></div>
                    </div>
                
                    <form class="form-horizontal" action="{{ route('posts.update',$post->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <section class="socialAccounts pl-3">
                            <label for="{{ $post['account_id'] }}" style="width:50px;height:50px" class="user-info position-relative mr-3" title="{{ $post['account_name'] }}">
                                @if ($post['account_pic'])
                                    <img src="{{ asset($post['account_pic']) }}" class="img-circle p-1 w-100 {{ $post['account_type'] }}App-border" alt="User Image">
                                @else
                                    <img src="{{ asset('tools/dist/img/user.png') }}" class="img-circle p-1 w-100 {{ $post['account_type'] }}App-border" alt="User Image">          
                                @endif
                                <span class="rounded position-absolute {{ $post['account_type'] }}App" style="background: transparent;left: 33px;top: 29px;">
                                    <i class="fab fa-{{ $post['account_type'] }} rounded" style="font-size: 15px;"></i>
                                </span>
                            </label>

                            <textarea cols="30" rows="15" maxlength="5000" class="form-control mt-3" name="postData" placeholder="Whta's on your mind?" style="resize: none">{{ $post->content }}</textarea>
                            <div class="container">
                                <div class="photoSec previewSec pb-4">
                                    @if (!empty($images))
                                        @foreach ($images as $oldImage)
                                            <input type="checkbox" name="oldImages[]" checked value="{{ $oldImage['id'] }}" id="image-{{ $oldImage['id'] }}" hidden>
                                            <label for="image-{{ $oldImage['id'] }}" style="width:150px;height:150px" class="user-info position-relative mr-3">
                                                <img src="{{ url($oldImage['image']) }}" class="img-circle p-1 w-100 image{{ $oldImage['id'] }}App-border" alt="User Image">
                                            </label>
                                        @endforeach
                                    @endif

                                    @if (!empty($videos))
                                        @foreach ($videos as $oldVideo)
                                            <input type="checkbox" name="oldVideos[]" checked value="{{ $oldVideo['id'] }}" id="video-{{ $oldVideo['id'] }}" hidden>
                                            <label for="image-{{ $oldVideo['id'] }}" style="width:150px;height:150px" class="user-info position-relative mr-3">
                                                <video src="{{ url($oldVideo['video']) }}" class="img-circle p-1 w-100 video{{ $oldVideo['id'] }}App-border" alt="User Image"></video>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                                
                                <div class="card py-2 px-4 d-flex flex-row justify-content-between align-items-center">
                                    <p class="m-0">Add to your post</p>
                                    <div class="d-flex position-relative">
                                        <div class="file position-absolute" id="imgFile">
                                            <input type="file" class="form-control position-absolute" name="images[]" onchange="getImagePreview(event)" accept=".jpg, .jpeg, .png, .gif" multiple>
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

                            <div class="form-group container" id="youtubeSelectBlock" style="display: none;">
                                <div class="row">
                                    <div class="col-2 d-flex align-items-center mb-3">
                                        <span><b>Video title</b></span>
                                    </div>
                                    <div class="col-10 mb-3">
                                        <input type="text" class="form-control" name="videoTitle" id="videoTitle" value="{{ $post->videoTitle }}" placeholder="Enter video title for youtube">
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <div class="col-4"><span><b>Category</b></span></div>
                                            <div class="col-8">
                                                <select class="form-control select2 w-100" name="youtubeCategory">
                                                    <option selected disabled>Choose category of video on youtube</option>
                                                    @foreach($youtubeCategories as $category)
                                                        <option value="{{$category['category_id']}}">{{$category['category_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <div class="col-4"><span><b>Visibility</b></span></div>
                                            <div class="col-8">
                                                <select class="form-control select2 w-100" name="youtubePrivacy">
                                                    <option value="{{ $post->privacy }}"></option>
                                                    <option value="public">Public</option>
                                                    <option value="private">Private</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-2 d-flex align-items-center mb-3">
                                        <span><b>Tags</b></span>
                                    </div>
                                    <div class="col-10 mb-3">
                                        <input type="text" class="form-control mb-3" name="youtubeTags" value="{{ $post->youtbe_tags }}" placeholder="Tag1,Tag2,...">
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

                            <input type="text" value="{{ $post->scheduledTime }}" disabled>

                            <button type="submit" class="btn publishBtn float-right border border-info px-4">Update</button>
                        </section>

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
                                    <input type="text" class="form-control" name="link" value="{{ old('link') }}" placeholder="link">
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
                    </form>
                </div>

            </section>
        </div>
    </div>
</div>

<script>
    
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        var datePosts = document.getElementById('scheduledTime');
        var today = new Date().toISOString().split('T')[0];
        datePosts.setAttribute("min", today);

        const accountType = <?php echo json_encode($post['account_type']); ?>;
        if(accountType == 'youtube'){
            youtubeSelectBlock.style.display = 'block';
            youtubeSelectBlock.querySelector('#videoTitle').setAttribute('required', 'required');
            // document.querySelector('input[name="video"]').setAttribute('required', 'required');
        }
    });


    // show image after choose it 
    function getImagePreview(event){
        // console.log(event.target.files[0]);
        for(let i = 0; i<event.target.files.length; i++)
        {
            var img = URL.createObjectURL(event.target.files[i]);
            var container = document.querySelector('.previewSec');
            // container.innerHTML = '';
            var html = `<img src="${img}" style='width:100px;height:100px'>
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





