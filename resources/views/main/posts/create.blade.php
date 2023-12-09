@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content publishPostContainer py-4">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                {{-- <video style="width:200px;height:200px" src="{{ url('storage/app/uploads/vecteezy_square-shape-tech-background-hud-small-squares-shape-loop_13449649_472.mp4') }}"></video> --}}

                <div class="card p-3 my-2">
                    <div class="card-header border-0">
                        <h3 class="card-title my-3"><b>Create Post</b></h3>
                        <div class="card-tools socialIcons" id="socialIcons"></div>
                    </div>

                    @if ($userAccounts->isNotEmpty())
                        <form class="form-horizontal" id="createPostForm" action="{{ route('posts.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <section class="socialAccounts pl-3">
                                @foreach ($userAccounts as $account)
                                    <input type="checkbox" name="accounts_id[]" checked value="{{ $account['account_id'] }}" id="{{ $account['account_id'] }}" hidden>
                                    <label for="{{ $account['account_id'] }}" style="width:50px;height:50px" class="user-info position-relative me-3" title="{{ $account['account_name'] }}">
                                        @if ($account['account_pic'])
                                            <img src="{{ asset($account['account_pic']) }}" class="rounded-circle p-1 w-100 {{ $account['account_type'] }}App-border" alt="User Image">
                                        @else
                                            <img src="{{ asset('tools/dist/img/user.png') }}" class="rounded-circle p-1 w-100 {{ $account['account_type'] }}App-border" alt="User Image">
                                        @endif
                                        <span class="rounded position-absolute {{ $account['account_type'] }}App" style="background: transparent;left: 33px;top: 29px;">
                                            <i class="bx bxl-{{ $account['account_type'] }} fs-large rounded-circle" style="font-size: 15px;"></i>
                                        </span>
                                    </label>
                                @endforeach

                                <textarea cols="30" rows="15" maxlength="5000" class="form-control mt-3" name="content" placeholder="Whta's on your mind?" style="resize: none">{{ old('content') }}</textarea>
                                
                                <div class="container">
                                    <div class="photoSec previewSec pb-5 pt-4 d-flex align-items-center"></div>

                                    <progress id="uploadProgress" value="0" max="100"></progress>
                                    <span id="progressPercentage"></span>
                                    <i class="bx bx-trash stopUploading text-danger" style="cursor:pointer"></i>
                                    
                                    <div class="card py-2 px-4 d-flex flex-row justify-content-between align-items-center">
                                        <p class="m-0">Add to your post</p>
                                        <div class="d-flex position-relative">
                                            <div class="file position-absolute" id="imgFile">
                                                {{-- <input type="file" class="form-control position-absolute" id="postImage" name="images[]" 
                                                onchange="getImagePreview(event)" accept=".jpg, .jpeg, .png, .gif" multiple> --}}
                                                <input type="file" class="form-control position-absolute" id="postImage" name="images[]" 
                                                accept=".jpg, .jpeg, .png, .gif" multiple>
                                                <div class="postImagesArray"></div>
                                                <div class="postVideo"></div>
                                                <i class="bx bx-image text-success px-2 fs-5"></i>
                                            </div>
                                            <div class="file position-absolute" id="videoFile">
                                                {{-- <input type="file" class="form-control position-absolute" name="video" id="postVideo" onchange="getVideoPreview(event)" accept="video/*"> --}}
                                                <input type="file" class="form-control position-absolute" name="video" id="postVideo" accept="video/*">

                                                <i class="bx bx-video text-primary px-2 fs-5"></i>
                                            </div>
                                            <i class="bx bx-link text-info mx-1 mt-1 postLink fs-5" data-toggle="modal" data-target="#postData_link"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group container mt-4" id="youtubeSelectBlock" style="display: none;">
                                    <div class="row">
                                        <div class="col-2 d-flex align-items-center mb-3">
                                            <span><b>Video title</b></span>
                                        </div>
                                        <div class="col-10 mb-3">
                                            <input type="text" class="form-control" name="videoTitle" id="videoTitle" value="{{ old('videoTitle') }}" placeholder="Enter video title for youtube">
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <div class="col-4"><span><b>Category</b></span></div>
                                                <div class="col-8">
                                                    <select class="form-select"  name="youtubeCategory" aria-label="Default select example">
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
                                                    <select class="form-select" name="youtubePrivacy" aria-label="Default select example">
                                                      <option value="public">Public</option>
                                                      <option value="Private">Private</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-2 d-flex align-items-center mb-3">
                                            <span><b>Tags</b></span>
                                        </div>
                                        <div class="col-10 mb-3">
                                            <input type="text" class="form-control mb-3" name="youtubeTags" value="{{ old('youtubeTags') }}" placeholder="Tag1,Tag2,...">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center form-check form-switch my-3">
                                    <i class="bx bx-calendar text-info mx-2"></i>
                                    <label class="pt-1" for="checkDate"> schedule your post </label>
                                    <input type="checkbox" class="form-check-input mx-1" id="checkDate" onchange="statusChange()">
                                </div>

                                <div class="form-group w-25 schedule" style="display: none">
                                    <div class="row">
                                        <input type="datetime-local" id="scheduledTime" class="form-control mb-0 ms-5" name="scheduledTime">
                                    </div>
                                </div>
                            </section>

                            <div class="rounded d-flex justify-content-end">
                                @if ($userApps->isNotEmpty())
                                    <button type="submit" class="btn publishBtn float-right border border-info px-4">Publish</button>
                                @endif
                            </div>

                            <div class="modal fade" id="postData_link">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body pb-0">
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="data-link" class="form-label">Link</label>
                                                    <input type="text" id="data-link" class="form-control" value="{{ old('link') }}" name="link"/>
                                                    <br>
                                                    <p>
                                                        This link for <b>facebook</b> only.... <br>
                                                        if you have an <b>image</b>, the link not allowed and <b>recommended</b> to put it in your text.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    @else
                        <a href="{{ route('users.show',Auth::user()->id) }}" class="ms-2">Login to your account first</a>    
                    @endif

                </div>

            </section>
        </div>
    </div>
</div>

{{-- upload video or image with progress bar in database --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $userId = Auth::user()->id;
@endphp
<script>
    $(document).ready(function () {
        var xhr; // Declare xhr globally
        $('.stopUploading').hide();
        $('#postImage, #postVideo').change(function () {
            var formData = new FormData();
            var isImage = $(this).attr('id') === 'postImage';

            if (isImage) {
                var images = $('#postImage')[0].files;
                for (var i = 0; i < images.length; i++) {
                    formData.append('images[]', images[i]);
                }
            } else {
                formData.append('video', $('#postVideo')[0].files[0]);
            }

            xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function (event) {
                if (event.lengthComputable) {
                    var percentComplete = (event.loaded / event.total) * 100;
                    $('#uploadProgress').val(percentComplete);
                    document.getElementById('progressPercentage').innerHTML = Math.round(percentComplete) + '%';
                    $('#postImage, #postVideo').attr('disabled','disabled');
                }
            });

            xhr.addEventListener('load', function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var response = JSON.parse(xhr.responseText);
                    console.log('xhr.responseText',xhr.responseText);
                    console.log('response',response);
                    if(response.images){
                        displayUploadedImages(response.images);
                        collectUploadedImages(response.images);
                        document.querySelector('#postImage').value = '';
                    }
                    if(response.video){
                        displayUploadedVideo(response.video);
                        collectUploadedVideo(response.video);
                        document.querySelector('#postVideo').value = '';
                    }
                    $('#postImage, #postVideo').removeAttr('disabled');
                    $('.stopUploading').hide();
                } else {
                    console.error('Request failed with status:', xhr.status);
                }
            });

            xhr.addEventListener('error', function () {
                console.error('Upload failed');
            });

            xhr.open('POST', '{{ url('uploadFiles') }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            xhr.send(formData);
            $('.stopUploading').show();
        });

        $('.stopUploading').click(function () {
            if (xhr) {
                xhr.abort();
                $('#uploadProgress').val(0);
                document.getElementById('progressPercentage').innerHTML = '0 %';
                $('.stopUploading').hide();
                $('#postImage, #postVideo').val(''); // Clear the file input value
                $('#postImage, #postVideo').removeAttr('disabled');
                console.log('File upload aborted');
            } else {
                console.log('No active file upload to abort');
            }
        });


        // Function to handle file destroy
        function destroyFile() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'DELETE',
                url: '{{ url('removeFiles') }}',
                data: { file: 'filename_to_be_deleted' }, // Pass the filename or data needed for deletion
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.error('Delete request failed:', error);
                }
            });
        }

        function displayUploadedImages(imageNames) {
            var container = document.querySelector('.previewSec');
            for (var i = 0; i < imageNames.length; i++) {
                var img = "{{ asset('storage/user') }}" + "<?php echo $userId?>" +'/postImages/' + imageNames[i]; 
                var html = `<img src="${img}" alt="Uploaded Image">
                <span aria-hidden="true" style="cursor:pointer;margin: 0 10px;" onclick="closeFile(this)">&times;</span>`;
                container.innerHTML += html;
            }
        }

        var uploadedImages = [];
        function collectUploadedImages(imageNames) {
            var postImagesArray = document.querySelector('.postImagesArray');
            if (imageNames && Array.isArray(imageNames)) {
                uploadedImages = uploadedImages.concat(imageNames);
            }

            postImagesArray.innerHTML = '';
            uploadedImages.forEach(function (imageName) {
                var html = `<input type="hidden" class="form-control" name="images[]" value="${imageName}">`;
                postImagesArray.innerHTML += html;
            });
        }

        function displayUploadedVideo(videoName) {
            var container = document.querySelector('.previewSec');
            var video = "{{ asset('storage/user') }}" + "<?php echo $userId?>" +'/postVideo/' + videoName; 
            var html = `<video src="${video}"></video>
            <span aria-hidden="true" style="cursor:pointer;margin: 0 10px;" onclick="closeFile(this)">&times;</span>`;
            container.innerHTML += html;
        }

        var uploadedVideo = '';
        function collectUploadedVideo(videoName) {
            var postVideo = document.querySelector('.postVideo');
            if (videoName) {
                postVideo.innerHTML = '';
                var html = `<input type="hidden" class="form-control" name="video" value="${videoName}">`;
                postVideo.innerHTML += html;
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
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var datePosts = document.getElementById('scheduledTime');
        var today = new Date().toISOString().split('T')[0];
        datePosts.setAttribute("min", today);

        const allApps = Array.from(document.querySelectorAll('.publishPostContainer input[type="checkbox"]'));
        const youtubeSelectBlock = document.getElementById('youtubeSelectBlock');
        var userAccounts = <?php echo json_encode($userAccounts); ?>;

        // Convert userAccounts into an array of its values
        userAccounts = Object.values(userAccounts);

        checkAppType();

        allApps.forEach(app => {
            app.addEventListener('click', () => {
                checkAppType();
            });
        });

        function checkAppType() {
            const anyChecked = allApps.some(app => app.checked);
            if (anyChecked) {
                const youtubeAccounts = userAccounts.filter(account =>
                    account.account_type === 'youtube' && allApps.some(app => app.checked && app.id === account.account_id)
                );

                if (youtubeAccounts.length > 0) {
                    youtubeSelectBlock.style.display = 'block';
                    youtubeSelectBlock.querySelector('#videoTitle').setAttribute('required', 'required');
                    // document.querySelector('#postVideo').setAttribute('required', 'required');
                } else {
                    youtubeSelectBlock.style.display = 'none';
                    youtubeSelectBlock.querySelector('#videoTitle').removeAttribute('required');
                    // document.querySelector('#postVideo').removeAttribute('required');
                }
            } else {
                youtubeSelectBlock.style.display = 'none';
            }
        }
    });

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





