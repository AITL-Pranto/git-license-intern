<!DOCTYPE html>
<html lang="en">

<head>
    {{-- css+meta tags support globally --}}
    @include('frontend.global.css_support')
    {{-- css+meta tags support globally --}}
    {{-- custom css --}}
    @yield('frontend_custom_stylesheet')
    {{-- custom css --}}
</head>

<body class="sb-nav-fixed">
    {{-- top navbar --}}
    @include('frontend.layouts.navbar')
    {{-- top navbar --}}
    <div id="layoutSidenav">
        {{-- sidebar --}}
        @include('frontend.layouts.sidebar')
        {{-- sidebar --}}
        <div id="layoutSidenav_content">

            {{-- dynamic body contents --}}
            @yield('frontend_content')
            {{-- dynamic body contents --}}

            {{-- footer --}}
            @include('frontend.layouts.footer')
            {{-- footer --}}
        </div>
    </div>

    {{-- js support globally --}}
    @include('frontend.global.js_support')
    {{-- js support globally --}}
    {{-- custom script --}}
    @yield('frontend_custom_scripts')
    {{-- custom script --}}
</body>

</html>
