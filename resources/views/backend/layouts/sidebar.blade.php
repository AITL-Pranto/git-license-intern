<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @if (\App\Models\User::hasPermission(Auth::user(), \App\Http\Controllers\Enum\AdminPermissionEnum::MANAGE_DASHBOARD))
                    <div class="sb-sidenav-menu-heading">@lang('messages.lang.admin_dashboard')</div>
                    <a class="nav-link @if (!empty($menu) && $menu == 'admin_dashboard') active @endif"
                        href="{{ route('viewAdminDashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        @lang('messages.lang.admin_dashboard')
                    </a>
                @endif
                @if (\App\Models\User::hasPermission(Auth::user(), \App\Http\Controllers\Enum\AdminPermissionEnum::MANAGE_ADMINS))
                    <div class="sb-sidenav-menu-heading">অ্যাডমিন</div>
                    <a class="nav-link @if (!empty($menu) && $menu == 'manage_admin') active @endif"
                        href="{{ route('viewAdmins') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        অ্যাডমিন
                    </a>
                @endif
                @if (\App\Models\User::hasPermission(Auth::user(), \App\Http\Controllers\Enum\AdminPermissionEnum::MANAGE_COLLECTORS))
                    <div class="sb-sidenav-menu-heading">কালেক্টর</div>
                    <a class="nav-link @if (!empty($menu) && $menu == 'manage_collector') active @endif"
                        href="{{ route('viewCollectors') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        কালেক্টর
                    </a>
                @endif

                @if (\App\Models\User::hasPermission(Auth::user(), \App\Http\Controllers\Enum\AdminPermissionEnum::MANAGE_COLLECTORS))
                    <div class="sb-sidenav-menu-heading">ওয়ার্ড</div>
                    <a class="nav-link @if (!empty($menu) && $menu == 'manage_ward') active @endif"
                        href="{{ route('viewWards') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        ওয়ার্ড
                    </a>
                @endif

                <div class="sb-sidenav-menu-heading">কালেক্টর/অপারেটর</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                    aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Layouts
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="layout-static.html">Static Navigation</a>
                        <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">সেটিংস</div>
                @if (\App\Models\User::hasPermission(Auth::user(), \App\Http\Controllers\Enum\AdminPermissionEnum::MANAGE_WEBSITE_SETTINGS))
                    <a class="nav-link @if (!empty($menu) && $menu == 'website_settings') active @endif"
                        href="{{ route('getWebsiteSettings') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-wrench"></i></div>
                        ওয়েবসাইট সেটিংস
                    </a>
                @endif
                @if (\App\Models\User::hasPermission(Auth::user(), \App\Http\Controllers\Enum\AdminPermissionEnum::MANAGE_ROLE_PERMISSONS))
                    <a class="nav-link @if (!empty($menu) && $menu == 'role_permission') active @endif"
                        href="{{ route('rolePermission') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                        রোল এবং পারমিশন
                    </a>
                @endif
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">লগইন করে আছেন:</div>
            {{ Auth::user()->username }}
        </div>
    </nav>
</div>
