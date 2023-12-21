@php
    $website_info = \App\UtilityFunction::websiteDetails();
    $imagePath = \App\UtilityFunction::globalImagePath('website', $website_info->website_favicon);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ $website_info->website_short_name }} - @yield('title')</title>
    {{-- fav icon --}}
    <link rel="icon" type="image/x-icon" href="{{ $imagePath }}">
    {{-- fav icon --}}
    <link href="{{ asset('frontend/css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <script src="{{ asset('frontend/js/font-awesome.js') }}" crossorigin="anonymous"></script>
    <style>
        .toast {
            opacity: 1 !important;
        }
    </style>
</head>

<body class="bg-primary">
    @yield('frontend_auth_content')
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery-3.6.4.min.js') }}"></script>
    <script src="{{ asset('frontend/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>
