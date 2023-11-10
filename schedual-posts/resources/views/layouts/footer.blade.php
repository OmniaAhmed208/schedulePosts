</div>
<!-- / Layout page -->
</div>
<!-- Overlay -->
{{-- <div class="layout-overlay layout-menu-toggle"></div> --}}
</div>
<!-- / Layout wrapper -->

<script src="{{asset('tools/assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('tools/assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('tools/assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('tools/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('tools/assets/vendor/js/menu.js')}}"></script>
<!-- Vendors JS -->
<script src="{{asset('tools/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

<!-- Main JS -->
<script src="{{asset('tools/assets/js/main.js')}}"></script>

<!-- Page JS -->
<script src="{{asset('tools/assets/js/dashboards-analytics.js')}}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>


<!-- jQuery -->
<script src="{{ asset('tools/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('tools/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('tools/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('tools/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('tools/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('tools/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('tools/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('tools/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('tools/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('tools/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('tools/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('tools/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('tools/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- uPlot -->
<script src="{{ asset('tools/plugins/uplot/uPlot.iife.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('tools/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('tools/dist/js/pages/dashboard.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('tools/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('tools/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('tools/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('tools/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('tools/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('tools/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset('tools/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('tools/plugins/fullcalendar/main.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('tools/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('tools/plugins/toastr/toastr.min.js') }}"></script>
{{-- <script>
  $('.toastrDefaultSuccess').click(function() {
    toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
  });
</script> --}}

<script>
  toastr.options = {
    // positionClass: 'toast-top-right',
    // timeOut: 3000,
    closeButton: true,
    progressBar: true
  };
</script>

@if (session()->has('success'))
  <script> toastr.success("{{ session('success') }}"); </script>
@endif

@if(session()->has('error'))
  <script> toastr.error('{{ session('error') }}'); </script>
@endif

{{-- after publish post --}}
@if(session()->has('postStatusForPublishing'))
  @foreach(session('postStatusForPublishing') as $message)
    <script>
      @if(strpos($message, 'successfully') !== false)
          toastr.success('{{ $message }}');
      @elseif(strpos($message, 'pending') !== false)
          toastr.info('{{ $message }}');
      @else
          toastr.error('{{ $message }}');
      @endif
    </script>
  @endforeach
@endif


@if (session()->has('rolePermission'))
  <script>
    @if(strpos(session('rolePermission'), 'successfully') !== false)
        toastr.success("{{ session('rolePermission') }}");
    @else
        toastr.warning("{{ session('rolePermission') }}");
    @endif
  </script>
@endif

<!-- Page specific script DataTables-->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<script>
  // switch
  $(function () {
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })
  });

  $(function () {
   // Initialize Select2 Elements
   $('.select2').select2();
 });
</script>

{{-- chat --}}
@php
    $websiteName = "E-Volve";
    $websiteColor = "#06283D";
@endphp

@auth
  @if (Auth::user()->role_for_messages != 'admin')
      @include('liveChat::pages.main.chat', ['websiteName' => $websiteName], ['chatColor' => $websiteColor])
  @endif
@else
  @include('liveChat::pages.main.chat', ['websiteName' => $websiteName], ['chatColor' => $websiteColor])
@endauth


{{-- calling function of facebookapi every time which selected to get new posted --}}
{{-- @php
  $interval = App\Models\Api::all()->last();
  $time = 60;
  if($interval){
    $time = $interval->update_interval;
  }

  $channels = App\Models\Api::all()->where('account_type', 'youtube')->where('creator_id', Auth::user()->id);
@endphp
<script>
  window.fetchFacebookDataUrl = "{{ route('fetch-facebook-data') }}";

  var channels = <?php echo $channels; ?>;
  const channelId = [];
  for (const key in channels) {
    if (channels.hasOwnProperty(key)) {
      const userChannelID = channels[key].user_account_id;
      if (userChannelID) {
        channelId.push(userChannelID);
      }
    }
  }

  const youtubeUrl = [];
  channelId.forEach(channel=>{
    var url = "{{ route('youtubeData', ['channel_id' => '__id__']) }}";
    window.fetchYoutubeDataUrl = url.replace('__id__', channel);
    youtubeUrl.push(window.fetchYoutubeDataUrl);
  });
</script>

<script>
  var minutes = <?php echo $time; ?>;
  console.log('every ' + minutes + ' minutes');
  var timeInMilliseconds = minutes * 60 * 1000;

  function fetchData(url) {
      fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data fetched:', data);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
  }

  setInterval(() => {
    fetchData(window.fetchFacebookDataUrl)
    youtubeUrl.forEach(url=>{
      fetchData(url);
    });
  }, timeInMilliseconds);

</script> --}}

{{-- calender --}}
{{-- @php
    $allPosts = App\Models\publishPost::where('creator_id', Auth::user()->id)->get();
    $accounts = App\Models\api::where('creator_id', Auth::user()->id)->get();
@endphp
<script>
  $(function () {
    var posts = <?php echo $allPosts; ?>;
    var accounts = <?php echo $accounts; ?>;

    var date = new Date();
    var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear();

    var Calendar = FullCalendar.Calendar;
    var calendarEl = document.getElementById('calendar');

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

          var typeIconHtml = `
            <span class="rounded-circle socialLogo ${arg.event.extendedProps.type}App">
              <i class="fab fa-${arg.event.extendedProps.type}"></i>
            </span>`;

          if (arg.event.extendedProps.status === 'pending') {
          return {
              html: '<div class="event-content position-relative d-flex justify-content-between align-items-center w-100 p-1" style="border-left:3px solid #f39c12;height:100%">' +
              '<div class="event-title">' + typeIconHtml  + '</div>' +
              imageHtml +
              '</div>'
          };
          } else {
          return {
              html: '<div class="event-content position-relative d-flex justify-content-between align-items-center w-100 p-1" style="border-left:3px solid #01b954;height:100%">' +
              '<div class="event-title">' + typeIconHtml  + '</div>' +
              imageHtml +
              '</div>'
          };
          }
      },

      editable: false,
      droppable: false,

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
</script> --}}

</body>
</html>
