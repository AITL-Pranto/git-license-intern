<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>{{ $website_info->website_short_name }} - @yield('title')</title>
{{-- fav icon --}}
@php
    $imagePath = \App\UtilityFunction::globalImagePath('website', $website_info->website_favicon);
@endphp
<link rel="icon" type="image/x-icon" href="{{ $imagePath }}">
{{-- fav icon --}}
<link href="{{ asset('frontend/css/datatable.min.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/styles.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"/>
<script src="{{ asset('frontend/js/font-awesome.js') }}" crossorigin="anonymous"></script>
<style>
    .toast {
        opacity: 1 !important;
    }
</style>
