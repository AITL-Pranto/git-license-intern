<!DOCTYPE html>
<html lang="en">

<head>
    {{-- css+meta tags support globally --}}
    @include('backend.global.css_support')
    {{-- css+meta tags support globally --}}
    {{-- custom css --}}
    @yield('backend_custom_stylesheet')
    {{-- custom css --}}
</head>

<body class="sb-nav-fixed">
    {{-- top navbar --}}
    @include('backend.layouts.navbar')
    {{-- top navbar --}}
    <div id="layoutSidenav">
        {{-- sidebar --}}
        @include('backend.layouts.sidebar')
        {{-- sidebar --}}
        <div id="layoutSidenav_content">

            {{-- dynamic body contents --}}
            @yield('backend_content')
            {{-- dynamic body contents --}}

            {{-- footer --}}
            @include('backend.layouts.footer')
            {{-- footer --}}
        </div>
    </div>

    {{-- js support globally --}}
    @include('backend.global.js_support')
    {{-- js support globally --}}
    {{-- custom script --}}
    @yield('backend_custom_scripts')
    {{-- custom script --}}
</body>

</html>
