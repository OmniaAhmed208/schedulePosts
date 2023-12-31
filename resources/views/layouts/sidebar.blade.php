<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{asset('tools/evolve/Icon/Icon-04.png')}}" style="height: 50px" alt="">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">E-Volve</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      <!-- Dashboards -->
        <li class="menu-item active">
            <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboards">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Content</span>
        </li>

        <!-- Apps -->
        <li class="menu-item">
            <a href="{{ route('posts.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div data-i18n="createPost">Create Post</div>
            </a>
        </li>

        {{-- @if (Auth::user()->user_type === 'admin') --}}
        @can('pages.link')
            <li class="menu-item">
                <a href="{{ route('newsLetter.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-copy"></i>
                    <div data-i18n="createPost">Newsletter</div>
                </a>
            </li>
        @endcan
        {{-- @endif --}}

        {{-- <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chart"></i>
                <div data-i18n="createPost">Analytics</div>
            </a>
        </li> --}}

        <!-- Components -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Components</span></li>
        @can('pages.link')
            <li class="menu-item">
                <a href="{{ route('services.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-detail"></i>
                    <div data-i18n="createPost">Services</div>
                </a>
            </li>
        @endcan

        {{-- @can('pages.link') --}}
            <li class="menu-item">
                <a href="{{ route('rolePermissions.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                    <div data-i18n="createPost">Roles & Permissions</div>
                </a>
            </li>    
        {{-- @endcan --}}

        <li class="menu-item">
            <a href="{{ url('policy') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div data-i18n="createPost">Policy & Terms</div>
            </a>
        </li>
        <!-- Users -->
        
        @can('pages.link')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Users</span></li>
            <li class="menu-item">
                <a href="{{url('users')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="createPost">Users</div>
                </a>
            </li>
        @endcan

        @can('pages.link')
            <li class="menu-item">
                <a href="{{route('subscribers.index')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-group"></i>
                    <div data-i18n="createPost">Subscribers</div>
                </a>
            </li>
        @endcan

    </ul>
  </aside>
        <!-- / Menu -->
