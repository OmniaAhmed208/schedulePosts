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
                                <img src="tools/assets/img/illustrations/man-with-laptop-light.png"
                                height="140" alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 order-1">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card" style="background: #06283d">
                            <div class="card-body text-white">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                        <img src="tools/assets/img/icons/unicons/service.png" alt="chart success" class="rounded" />
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
                                        <img src="tools/assets/img/icons/unicons/post.png" alt="post" class="rounded" />
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
                                        <img src="tools/assets/img/icons/unicons/post.png" alt="post" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-medium d-block mb-1">posts</h3>
                                    <h4 class="card-title mb-2">{{ $allPosts }}</h4>
                                </div>
                                <p>All posts you have</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <!-- Expense Overview -->
            <div class="col-lg-12 order-1 mb-4">

                {{-- <div class="card h-100">
                    <div class="card-header">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <button
                                type="button"
                                class="nav-link active"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-tabs-line-card-income"
                                aria-controls="navs-tabs-line-card-income"
                                aria-selected="true">
                                Filter applications <i class="bx bx-down-arrow-alt"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body px-0">
                        <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                            <div id="incomeChart"></div>
                            <div class="d-flex justify-content-center pt-4 gap-2">
                            <div class="flex-shrink-0">
                                <div id="expensesOfWeek"></div>
                            </div>
                            <div>
                                <p class="mb-n1 mt-1">Expenses This Week</p>
                                <small class="text-muted">$39 less than last week</small>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div> --}}

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

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>

@php
    $allPosts = App\Models\publishPost::where('creator_id', Auth::user()->id)->get();
    $accounts = App\Models\api::where('creator_id', Auth::user()->id)->get();
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var posts = <?php echo $allPosts; ?>;
        var accounts = <?php echo $accounts; ?>;
        // Create an empty array to hold the events
        var eventsArray = [];
        var popupDiv;

        for (let i = 0; i < posts.length; i++)
        {
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

            var event = {
                title: post.account_name,
                start: jsDate,
                allDay: false,
                backgroundColor: color,
                borderColor: color,
                image: post.thumbnail,
                id: post.id,
                type: post.account_type,
                status: post.status,
                account_id: post.account_id
            };

            // Push the event to the eventsArray
            eventsArray.push(event);
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            // themeSystem: 'bootstrap',
            events: eventsArray,

            eventContent: function (arg) {
                var imageHtml = arg.event.extendedProps.image
                    ? '<img src="' + arg.event.extendedProps.image + '" class="event-image img-fluid rounded" style="max-width:50px" />'
                    : '';

                var typeIconHtml = `
                    <span class="rounded-circle socialLogo ${arg.event.extendedProps.type}App">
                        <i class="bx bxl-${arg.event.extendedProps.type}"></i>
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


            editable: false,
            droppable: false,

            initialView: 'dayGridMonth', // Initial view when the calendar loads
            height: 'auto', // Adjust the height as needed

            eventMouseEnter: function (event) {
                // Create a small popup div for event details if it doesn't exist
                if (!popupDiv) {
                popupDiv = document.createElement('div');
                popupDiv.className = 'event-popup position-absolute';
                document.body.appendChild(popupDiv);
                }

                var postId = parseInt(event.event.id);
                var post = posts.find(function (p) {
                return p.id === postId;
                });

                var accountID = parseInt(event.event.account_id);
                var account = accounts.find(function (a) {
                    return a.account_id === accountID;
                });

                if (post) {
                var scheduledTime = post.scheduledTime.split(' ')[1];
                var popupContent = `
                    <div class="accountInfo bg-white p-2 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="info position-relative">
                            <img src="{{ asset('tools/dist/img/avatar5.png') }}" class="rounded-circle" style="width:50px" alt="">
                            <span class="rounded-circle position-absolute socialLogo ${post.account_type}App"><i class="fab fa-${post.account_type}"></i></span>
                            <span class="ml-3 accountName" style="font-weight: bold">${post.account_name ? post.account_name : ''} </span>
                        </div>
                        <div class="text-dark p-1 text-end">  ${scheduledTime}  </div>
                    </div>
                    </div>

                    <div class="row p-3 px-4">
                        <div class="col-7">
                            <div class="postData">${post.content}</div>
                        </div>
                        <div class="col-5">
                            ${post.thumbnail ? `<div><img src="${post.thumbnail}" alt="Image" class="popup-image img-fluid"/></div>` : ''}
                        </div>
                    </div>

                    <div class="p-3 px-4 d-flex justify-content-end bg-white" style="border-top: 1px solid #dee2e6;">

                    </div>
                `;
                // <a href="${post.link ? post.link: '#'}" target="_blank" class="postLink" style="font-weight: bold;color:#6c757d !important">View Post</a>
                // ${account ? `<a href="${account.account_link ? account.account_link : '#'}" target="_blank" class="postLink" style="font-weight: bold;color:#6c757d !important">View Post</a></div> : '' `}

                // Set the content of the popupDiv
                popupDiv.innerHTML = popupContent;


                var eventElement = event.el;
                var rect = eventElement.getBoundingClientRect();
                popupDiv.style.top = rect.bottom + 'px'; // Position below the event
                popupDiv.style.left = rect.left + 50 + 'px'; // Align with the left of the event

                // Show the popup
                popupDiv.style.display = 'block';
                }
            },

            eventMouseLeave: function () {
                if (popupDiv) {
                    popupDiv.style.display = 'none';
                }
            },

        });
        calendar.render();
    });
</script>

@endsection



