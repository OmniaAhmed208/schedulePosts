</div>
</div>
</div>


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
  $(function databaseTable($tableId) 
  {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#permissionTable').DataTable({
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

</body>
</html>
