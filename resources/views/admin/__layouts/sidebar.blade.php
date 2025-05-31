<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
        <img src="{{ asset('') }}/logo.png" alt="{{ config('app.name') }} Logo" class="brand-image img-circle elevation-3" style="opacity: .9">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('admin-template') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name ?? 'Guest' }}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach($menus as $menu)
                    <li class="nav-item">
                        @if(!isset($menu['sub']))
                            <a href="{{ route($menu['route_name']) }}" class="nav-link {{ $isActive1 == $menu['permission'] ? 'active' : '' }}">
                                <i class="nav-icon {{ $menu['icon'] }}"></i>
                                <p>{{ $menu['title'] }}</p>
                            </a>
                        @else
                            <a href="#" class="nav-link {{ $isActive1 == $menu['permission'] ? 'active' : '' }}">
                                <i class="nav-icon {{ $menu['icon'] }}"></i>
                                <p>
                                    {{ $menu['title'] }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach($menu['sub'] as $sub)
                                    <li class="nav-item {{ $isActive2 == $sub['permission'] ? 'menu-open' : '' }}">
                                        <a href="{{ route($sub['route_name']) }}" class="nav-link {{ $isActive2 == $sub['permission'] ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ $sub['title'] }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>