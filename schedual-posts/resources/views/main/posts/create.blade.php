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
                        <form class="form-horizontal" action="{{ route('posts.store')}}" method="post" enctype="multipart/form-data">
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
                                            <i class="bx bx-link text-info mx-1 mt-1 postLink fs-5" data-toggle="modal" data-target="#modal-default"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group container" id="youtubeSelectBlock" style="display: none;">
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
                                @if (($userApps->isNotEmpty()) && $timeThink)
                                    <button type="submit" class="btn publishBtn float-right border border-info px-4">Publish</button>
                                @endif
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
                    @else
                        <a href="{{ route('users.show',Auth::user()->id) }}" class="ms-2">Login to your account first</a>    
                    @endif

                </div>

            </section>
        </div>
    </div>
</div>


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





