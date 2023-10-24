
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Calendar</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="tools/plugins/fontawesome-free/css/all.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="tools/plugins/fullcalendar/main.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="tools/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="tools/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="tools/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="tools/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="tools/plugins/moment/moment.min.js"></script>
<script src="tools/plugins/fullcalendar/main.js"></script>

@php
    $allPosts = App\Models\Publish_Post::where('creator_id', Auth::user()->id)->get();
@endphp

<script>
    $(function () {
        var posts = <?php echo $allPosts; ?>;

        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();

        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');

        // Create an empty array to hold the events
        var eventsArray = [];
        var popupDiv;

        for (let i = 0; i < posts.length; i++) {
            var post = posts[i];
            var dateStr = post.scheduledTime;
            var color = '#ddd';

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
                title: post.pageName,
                start: jsDate, 
                allDay: false, 
                backgroundColor: color,
                borderColor: color,
                image: post.image,
                id: post.id,
                type: post.type,
                status: post.status
            };

            // Push the event to the eventsArray
            eventsArray.push(event);
        }

        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap',
            events: eventsArray, 
            eventContent: function (arg) {
        
                var imageHtml = arg.event.extendedProps.image
                ? '<img src="' + arg.event.extendedProps.image + '" class="event-image img-fluid rounded" style="max-width:50px" />'
                : '';

                var typeIconHtml = '';
                if (arg.event.extendedProps.type === 'facebook') {
                typeIconHtml = '<i class="fab fa-facebook rounded-circle socialLogo" style="background:#007bff"></i>';
                } else if (arg.event.extendedProps.type === 'instagram') {
                typeIconHtml = '<i class="fab fa-instagram rounded-circle socialLogo" style="background:#d63384"></i>';
                } else '';

                if (arg.event.extendedProps.status === 'pending') {
                return {
                    html: '<div class="event-content position-relative d-flex justify-content-between align-items-center p-1" style="border-left:3px solid #f39c12">' +
                    '<div class="event-title">' + typeIconHtml  + '</div>' +
                    imageHtml +
                    '</div>'
                };
                } else {
                return {
                    html: '<div class="event-content position-relative d-flex justify-content-between align-items-center p-1" style="border-left:3px solid #01b954">' +
                    '<div class="event-title">' + typeIconHtml  + '</div>' +
                    imageHtml +
                    '</div>'
                };
                } 
            },
            editable: false,
            droppable: false,
            // Add eventMouseEnter and eventMouseLeave callbacks
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

                if (post) {
                var scheduledTime = post.scheduledTime.split(' ')[1];
                var popupContent = `
                    <div class="accountInfo bg-white p-2 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="info position-relative">
                        <img src="{{ asset('tools/dist/img/avatar5.png') }}" class="rounded-circle" style="width:50px" alt="">
                        
                        ${post.type === 'facebook' ? `
                            <i class="fab fa-facebook rounded-circle position-absolute bg-white socialLogo" style="color:#007bff !important"></i>
                        ` : post.type === 'instagram' ? `
                            <i class="fab fa-instagram rounded-circle position-absolute bg-white socialLogo" style="color:#d63384 !important"></i>
                        ` : '' }

                        <span class="ml-3 accountName" style="font-weight: bold">${post.pageName}</span>
                        </div>
                        <div class="text-dark p-1 text-end">  ${scheduledTime}  </div>
                    </div>
                    </div>

                    <div class="row p-3 px-4">
                    <div class="col-7">
                        <div class="postData">${post.postData}</div>
                    </div>
                    <div class="col-5">
                        ${post.image ? `<div><img src="${post.image}" alt="Image" class="popup-image img-fluid"/></div>` : ''}
                    </div>
                    </div>

                    <div class="p-3 px-4 d-flex justify-content-end bg-white" style="border-top: 1px solid #dee2e6;">
                    <a href="${post.link ? post.link: '#'}" target="_blank" class="postLink" style="font-weight: bold;color:#6c757d !important">View Post</a>
                    </div>
                `;

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
                if (popupDiv) 
                {
                popupDiv.addEventListener('mouseenter',()=>{
                    popupDiv.style.display = 'block';
                });

                popupDiv.addEventListener('mouseleave',()=>{
                    popupDiv.style.display = 'none';
                });

                if (popupDiv.hasMouseEnterListener) {
                    popupDiv.style.display = 'block';
                } else {
                    popupDiv.style.display = 'none';
                }
                }
            }
        });

        calendar.render();
    });
</script>

</body>
</html>
