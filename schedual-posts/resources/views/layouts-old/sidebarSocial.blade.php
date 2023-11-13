<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-1 sidebar-light-primary">

  <div class="side_top">
    <!-- Brand Logo -->
    <a href="#" class="mt-5 mb-2 d-flex justify-content-center">
      <img src="{{ asset('/tools/evolve/Logo/Logo-01.png') }}" class="img-fluid elevation w-75" alt="User Image">
    </a>
  </div>
  

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="{{ route('adminSocail') }}" class="nav-link outsideLinks mt-3">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        <li class="nav-item pt-3 mt-3" style="border-top: 1px solid #dee2e6;">
          <div class="nav-link active_mandatory">
            <span class="mx-2">--</span>
            <p>
              Content
            </p>
          </div>
        </li>

        <li class="nav-item">
          <a href="{{ route('accountPages') }}" class="nav-link outsideLinks">
            <i class="nav-icon fas fa-plus-circle"></i>
            <p>
              Create Posts
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('posts.index') }}" class="nav-link outsideLinks"> {{-- table from database --}}
            <i class="nav-icon far fa-clone"></i>
            <p>
              Posts
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('historyPosts') }}" class="nav-link outsideLinks">
            <i class="nav-icon far fa-calendar-minus"></i>
            <p>
              History
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link outsideLinks">
            <i class="nav-icon fas fa-bullhorn"></i>
            <p>
              Campaigns
            </p>
          </a>
        </li>

        <li class="nav-item pt-3 mt-3" style="border-top: 1px solid #dee2e6;">
          <div class="nav-link active_mandatory">
            <span class="mx-2">--</span>
            <p>
              Analytics
            </p>
          </div>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link outsideLinks">
            <i class="nav-icon fab fa-facebook"></i>
            <p>
              Facebook
            </p>
          </a>
        </li>

        <li class="nav-item pt-3 mt-3" style="border-top: 1px solid #dee2e6;">
          <div class="nav-link active_mandatory">
            <span class="mx-2">--</span>
            <p>
              Configuration
            </p>
          </div>
        </li>

        <li class="nav-item">
          <a href="{{ route('socialAccounts.index') }}" class="nav-link outsideLinks">
            <i class="nav-icon fas fa-address-book"></i>
            <p>
              Accounts
            </p>
          </a>
        </li>
        
        @if (Auth::user()->user_type === 'admin')
          <li class="nav-item">
            <a href="{{ route('services.index') }}" class="nav-link outsideLinks">
              <i class="nav-icon fas fa-server"></i>
              <p>
                Services
              </p>
            </a>
          </li>
        @endif 

        <li class="nav-item">
          <a href="{{ route('rolePermission') }}" class="nav-link outsideLinks">
            <i class="nav-icon fas fa-shield-alt"></i>
            <p>
              Roles & Permissions
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link settings">
            <i class="nav-icon fas fa-cog "></i>
            <p>
              Settings
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('timeThink') }}" class="nav-link insideLinks">
                <i class="far fa-circle nav-icon"></i>
                <p>Think Time</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('updatePostsTime') }}" class="nav-link insideLinks">
                <i class="far fa-circle nav-icon"></i>
                <p>Time to update posts</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link tools">
            <i class="nav-icon fas fa-wrench"></i>
            <p>
              Tools
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('schedulePosts') }}" class="nav-link insideLinks">
                <i class="far fa-circle nav-icon"></i>
                <p>Time of schedule Posts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('updatePostsNow') }}" class="nav-link insideLinks">
                <i class="far fa-circle nav-icon"></i>
                <p>Get New posts</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link Policy">
            <i class="nav-icon fab fa-codepen"></i>
            <p>
              Policy
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('privacy') }}" class="nav-link insideLinks">
                <i class="far fa-circle nav-icon"></i>
                <p>Privacy Policy</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('terms') }}" class="nav-link insideLinks">
                <i class="far fa-circle nav-icon"></i>
                <p>Terms Policy</p>
              </a>
            </li>
          </ul>
        </li>

        @if (Auth::user()->user_type === 'admin')
          <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link outsideLinks">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('subscribers.index') }}" class="nav-link outsideLinks">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Subscribers
              </p>
            </a>
          </li>
        @endif

        <li class="nav-item mt-3 pt-3 mb-5" style="border-top: 1px solid #dee2e6;">
          <a href="#" class="nav-link Policy">
            {{-- <i class="nav-icon far fa-user"></i> --}}
            <img src="{{ asset('tools/dist/img/admin.png') }}" class="img-circle elevation-2 mr-2" style="width:30px" alt="User Image">
            <p>
              {{ Auth::user()->name }}
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <!-- Authentication Links -->
            @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                      <i class="far fa-circle nav-icon"></i>
                      {{ __('Login') }}
                    </a>
                </li>
            @endif
    
            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                      <i class="far fa-circle nav-icon"></i>
                      {{ __('Register') }}
                    </a>
                </li>
            @endif
    
            @else
              <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                    <i class="far fa-circle nav-icon"></i>
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            @endguest
    
          </ul>
        </li>
      </ul>
      
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>


<script>
  let nav_item = document.querySelectorAll('.nav-item');

  nav_item.forEach(element => {
    element.onclick = function(){
      // console.log(element)
    }
  });

</script>