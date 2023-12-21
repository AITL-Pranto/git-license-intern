<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $website_info = \App\UtilityFunction::websiteDetails();
        $imagePath = \App\UtilityFunction::globalImagePath('website', $website_info->website_favicon);
    @endphp
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
     {{-- fav icon --}}
    <link rel="icon" type="image/x-icon" href="{{ $imagePath }}">
    {{-- fav icon --}}
    <title>500 Error - Server Error</title>
    <link href="{{ asset('frontend/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="layoutError">
        <div id="layoutError_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="text-center mt-4">
                                <h1 class="display-1">500</h1>
                                <p class="lead">অভ্যন্তরীণ সার্ভার ত্রুটি</p>
                                <p class="lead">দুঃখিত! অনাকাঙ্খিত কারণে সফটওয়্যার এ সমস্যা দেখা দিয়েছে। কিছুক্ষন পরে
                                    আবার চেষ্টা করুন।</p>
                                <a href="{{ url()->previous() }}">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    পূর্বের পেজ এ ফিরে যান।
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutError_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; {{ $website_info->website_short_name }} {{ date('Y') }}</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('frontend/js/scripts.js') }}"></script>
</body>

</html>
