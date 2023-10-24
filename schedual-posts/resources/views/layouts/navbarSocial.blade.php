{{-- @php
  $faceAccount = App\Models\Api::count();
  $instaAccount = App\Models\Instagram::count();
@endphp   --}}
  
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">

      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>

      {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('facebookPosts') }}" class="nav-link btn btn-info text-white mx-1 @if ($faceAccount == 0) d-none @endif">Facebook</a>
      </li> --}}

      
      {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('instagram') }}" class="nav-link btn text-white mx-2 @if ($instaAccount == 0) d-none @endif"
         style="background: #d63384;">Instagram</a>
      </li> --}}

    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      @if (Auth::user()->user_type === 'admin')
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments" style="color: #06283D"></i>
            <span class="badge badge-danger navbar-badge" id='chatCount'></span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="{{ route('admin.chat') }}" class="dropdown-item dropdown-footer">See All Messages</a>
          </div>
        </li>
      @endif  
    </ul>  
  </nav>
  <!-- /.navbar -->

<script src="{{ asset('/liveChat/tools/chat/js/msg_counter.js') }}"></script> 
<script>
    window.onload = function() { 
      var routeUrl = "{{ route('fetchNewMessages') }}"; 
      fetchNewMessages(routeUrl,'chatCount'); 
    }; 
</script> 