@extends('layouts.layout')
@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title">Welcome {{Auth::user()->name}}! 🎉</h5>
                                <p class="mb-4">We're excited to have you on our social media platform! <br>Explore, connect, and share your experiences with the community.</p>

                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">View our newsletter</a>
                            </div>
                        </div>

                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{ asset('tools/assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="140" alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 order-1">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card" style="background: #06283d">
                            <div class="card-body text-white">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                        <img src="{{ asset('tools/assets/img/icons/unicons/service.png') }}" alt="chart success" class="rounded" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-medium d-block mb-1 text-white">Services</h3>
                                    <h4 class="card-title mb-2 text-white">{{ $appCount }} / {{ $servicesCount }}</h4>
                                </div>
                                <p>Accounts you are connected in</p>
                                <small class="fw-medium">
                                    <a href="{{ route('services.index') }}" class="text-white">
                                        <i class="bx bx-right-arrow-alt"></i> View more
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                        <img src="{{ asset('tools/assets/img/icons/unicons/post.png') }}" alt="post" class="rounded" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-medium d-block mb-1">Published</h3>
                                    <h4 class="card-title mb-2">{{ $lastPosts }}</h4>
                                </div>
                                <p>Posts published at last week</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                        <img src="{{ asset('tools/assets/img/icons/unicons/post.png') }}" alt="post" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-medium d-block mb-1">posts</h3>
                                    <h4 class="card-title mb-2">{{ $postsCount }}</h4>
                                </div>
                                <p>All posts you have</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-lg-12 order-1 mb-4">
                <div class="card p-4">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>
    <!-- / Content -->

    {{-- <div class="content-backdrop fade"></div> --}}
</div>
<!-- Content wrapper -->

<!-- Include Bootstrap JS (and jQuery if needed) -->
<script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var posts = <?php echo $allPosts; ?>;
        var accounts = <?php echo $accounts; ?>;
        var eventsArray = [];
        var popupDiv;

        for (let i = 0; i < posts.length; i++) {
            var post = posts[i];
            var dateStr = post.scheduledTime;
            var color = '#ebebeb';

            var parts = dateStr.split(' ');
            var datePart = parts[0];
            var timePart = parts[1];
            var dateParts = datePart.split('-');
            var timeParts = timePart.split(':');
            var year = parseInt(dateParts[0]);
            var month = parseInt(dateParts[1]) - 1;
            var day = parseInt(dateParts[2]);
            var hours = parseInt(timeParts[0]);
            var minutes = parseInt(timeParts[1]);
            var jsDate = new Date(year, month, day, hours, minutes);

            var postImage = '';
            if(post.post_images.length > 0){
                // postImage = `{{asset('')}}`+post.post_images[0].image;
                postImage = post.post_images[0].image;
            }

            var event = {
                title: post.account_name,
                start: jsDate,
                allDay: false,
                backgroundColor: color,
                borderColor: color,
                image: postImage,
                id: post.id,
                type: post.account_type,
                status: post.status,
                account_id: post.account_id
            };

            eventsArray.push(event);
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: eventsArray,

            eventContent: function (arg) {
            // console.log(arg.event.extendedProps)

                var imageHtml = arg.event.extendedProps.image
                    ? '<img src="' + arg.event.extendedProps.image + '" class="event-image img-fluid rounded" style="max-width:50px" />'
                    : '';

                var typeIconHtml = `
                    <span class="socialLogo ${arg.event.extendedProps.type}App">
                        <i class="bx bxl-${arg.event.extendedProps.type} rounded-circle p-1"></i>
                    </span>`;

                var eventContentHtml = `
                    <div class="event-content position-relative d-flex justify-content-between align-items-center w-100 p-1"
                    style="border-left:3px solid ${arg.event.extendedProps.status === 'pending' ? '#f39c12' : '#01b954'};
                    height:100%;">
                    <div class="event-title">${typeIconHtml}</div>
                    ${imageHtml}
                    </div>
                `;

                return {
                    html: eventContentHtml,
                };
            },
            
            eventClick: function (info) {
                var event = info.event;

                var postId = parseInt(event.id);
                var post = posts.find(function (p) {
                    return p.id === postId;
                });
                if(post.post_images.length > 0){
                    var postImage = post.post_images[0].image;
                }
                if(post.post_videos.length > 0){
                    var postVideo = post.post_videos[0].video;
                }
                var scheduledTime = post.scheduledTime.split(' ')[1];
                var editUrl = "{{ route('posts.edit', ['post' => '__id__']) }}"; 
                var editPost = editUrl.replace('__id__', post.id);

                var deleteUrl = "{{ route('posts.destroy', ['post' => '__id__']) }}"; 
                var deletePost = deleteUrl.replace('__id__', post.id);

                var accountID = post.account_id;
                var account = accounts.find(function (a) {
                    return a.account_id === accountID;
                });
                var account_img = account.account_pic;

                var deletePostBtn = '<a class="btn mx-1 fw-bold shadow-sm bg-white" id="deletePost">Delete</a>';

                var popoverContent = `
                    <div class="popover-content popover-calender shadow-sm">
                        <div class="accountInfo p-2 px-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="info position-relative d-flex align-items-center">
                                    <img src="${account_img}" class="rounded-circle p-1 w-25 border ${post.account_type}App-border" alt="">
                                    <span class="position-absolute socialLogo ${post.account_type}App"><i class="bx bxl-${post.account_type} p-1 rounded-circle"></i></span>
                                    <div>
                                        <a href="${account.account_link}" class="text-dark">
                                            <span class="ms-3 accountName fw-bold">${post.account_name ? post.account_name : ''} </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="text-dark text-end time"> ${scheduledTime} </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between p-4 px-3 bg-white">
                            <div class="postData w-100">${post.content != null ? post.content : ''}</div>
                            ${postImage ? `<div class="w-50"><img src="${postImage}" alt="Image" class="popup-image w-100"/></div>` : ''}
                            ${postVideo ? `<div class="w-50"><video src="${postVideo}" alt="video" class="popup-image w-100"/></video></div>` : ''}
                        </div>

                        ${post.status == 'pending' ? 
                            `<div class="popoverFooter p-2 d-flex justify-content-end bg-white" style="border-top: 1px solid #dee2e6;">
                                <a class="btn mx-1 fw-bold shadow-sm bg-white" href="${editPost}">Edit Post</a>
                                <form action="${deletePost}" method="POST" id="deleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn mx-1 fw-bold shadow-sm bg-white" id="deletePostBtn" 
                                    onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                                </form>
                            </div>` 
                        : ''}                        
                    </div>
                `;
                
                if (popupDiv) {
                    popupDiv.popover('hide');
                }

                popupDiv = $(info.el).popover({
                    // title: event.title,
                    content: popoverContent,
                    placement: 'auto',
                    trigger: 'focus',
                    html: true,
                    sanitize: false, // to show buttons and forms inside popover
                });

                popupDiv.popover('show');
            }
        });

        calendar.render();
    });
</script>


@endsection



