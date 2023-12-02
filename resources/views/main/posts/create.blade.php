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

                                <textarea cols="30" rows="15" maxlength="5000" class="form-control mt-3" name="postData" placeholder="Whta's on your mind?" style="resize: none">{{ old('postData') }}</textarea>
                                
                                <div class="container">
                                    <div class="photoSec previewSec pb-4"></div>

                                    {{-- <div class="progress my-3">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0 %</div>
                                    </div> --}}

                                    {{-- <div class="row">
                                        <h5 class="card-header">Add to your post</h5>
                                        <div class="col-md-6">
                                          <div class="mb-4">
                                            <div class="card-body">
                                              <div>
                                                <label for="postImage" class="form-label">Upload Image</label>
                                                <input type="file" name="images[]" class="form-control" id="postImage" aria-describedby="imageExt" onchange="getImagePreview(event)" accept=".jpg, .jpeg, .png, .gif" multiple/>
                                                <div id="imageExt" class="form-text"> Accept image .jpg, .jpeg, .png or .gif </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="mb-4">
                                            <div class="card-body">
                                              <div>
                                                <label for="postVideo" class="form-label">Upload Video</label>
                                                <input type="file" name="video" class="form-control" id="postVideo" onchange="getVideoPreview(event)" accept="video/*"/>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>     --}}


                                    <div class="card py-2 px-4 d-flex flex-row justify-content-between align-items-center">
                                        <p class="m-0">Add to your post</p>
                                        <div class="d-flex position-relative">
                                            <div class="file position-absolute" id="imgFile">
                                                <input type="file" class="form-control position-absolute" name="images[]" onchange="getImagePreview(event)" accept=".jpg, .jpeg, .png, .gif" multiple>
                                                <i class="bx bx-image text-success px-2 fs-5"></i>
                                            </div>
                                            <div class="file position-absolute" id="videoFile">
                                                <input type="file" class="form-control position-absolute" name="video" onchange="getVideoPreview(event)" accept="video/*">

                                                <i class="bx bx-video text-primary px-2 fs-5"></i>
                                            </div>
                                            <i class="bx bx-link text-info mx-1 mt-1 postLink fs-5" data-toggle="modal" data-target="#postData_link"></i>
                                        </div>
                                    </div>
                                    {{-- <input type="file" class="form-control position-absolute" name="video" id="browseFile" accept="video/*"> --}}

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

{{-- filepond to upload images and videos --}}
{{-- <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script>
    const inputElement = document.querySelector('input[name="images[]"]');
    const pond = FilePond.create(inputElement);
    FilePond.setOptions({
    server: {
        process: '/test',
        revert: '/testDele',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    },
});
</script> --}}

{{-- // create post progress-bar--}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script>
    $(document).ready(function () {

        var persent = $('.progress-bar');

        $('#createPostForm').ajaxForm({
            beforeSend:function(){
                var persentVal = '0%';
                persent.width(persentVal);
                persent.html(persentVal);
            },
            uploadProgress:function(event,position,total,percentComplete){
                var persentVal=percentComplete+'%';
                persent.css('width', persentVal+'%', function(){
                    return $(this).attr('aria-valuenow',persentVal) + '%';
                })
                // persent.width(persentVal);
                // persent.html(persentVal);
            }
            complete:function(xhr){
                alert('File uploaded successfully!');
            }
        })
    });

</script> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        // File input change event
        document.querySelector('.progress').style.display = 'none';

        document.getElementById('browseFile').addEventListener('change', function (event) {
            var fileInput = event.target;
            var file = fileInput.files[0];
            // var videoPreview = document.getElementById('videoPreview');
            var progressContainer = document.querySelector('.progress');

            // Display progress bar
            progressContainer.style.display = 'block';

            simulateFileUpload(file, function () {
                var container = document.querySelector('.previewSec');
                var html = `<video src="${URL.createObjectURL(file)}"></video>
                <span aria-hidden="true" style="cursor:pointer;margin-right: 6px;" onclick="closeFile(this)">&times;</span>`;
                // var html = `<video src="${URL.createObjectURL(file)}" class="py-3" controls></video>
                // <span aria-hidden="true" style="cursor:pointer;position-absolute" onclick="closeFile(this)">&times;</span>`;
                container.innerHTML += html;
                progressContainer.style.display = 'none';
            });
        });

        function simulateFileUpload(file, callback) {
            var progressBar = document.querySelector('.progress-bar');
            var progress = 0;

            var interval = setInterval(function () {
                progress += 10;
                progressBar.style.width = progress + '%';
                progressBar.textContent = progress + '%';

                if (progress >= 100) {
                    clearInterval(interval);

                    // Execute the callback function when progress is complete
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            }, 500);
        }
    });
</script> --}}


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
                    document.querySelector('input[name="video"]').setAttribute('required', 'required');
                } else {
                    youtubeSelectBlock.style.display = 'none';
                    youtubeSelectBlock.querySelector('#videoTitle').removeAttribute('required');
                    document.querySelector('input[name="video"]').removeAttribute('required');
                }
            } else {
                youtubeSelectBlock.style.display = 'none';
            }
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
        uploadVideo();
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





