@extends('frontend.auth.auth_master', ['menu' => ''])
@section('title', __('messages.page_title.collector_login_page_title'))

@section('frontend_auth_custom_stylesheet')
@stop

@section('frontend_auth_content')
    @php
        $website_info = \App\UtilityFunction::websiteDetails();
    @endphp
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">{{ trans('messages.page_title.login_page_title') }}</h3>
                                </div>
                                <div class="card-body">
                                    @include('messages.error')
                                    {!! Form::open(array('url'=>'/collector/login-request','class'=>"login")) !!}
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="email_or_mobile" name="email_or_mobile" type="text"
                                                placeholder="name@example.com/01XXX-XXXXXX" value="{{ old('email_or_mobile') }}"/>
                                            <label for="email_or_mobile">@lang('messages.lang.email_or_mobile')</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" id="inputPassword" type="password"
                                                placeholder="Password" />
                                            <label for="inputPassword">@lang('messages.lang.password')</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary">@lang('messages.lang.login')</button>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                                {{-- <div class="card-footer text-center py-3">
                                    <div class="small"><a href="register.html">Need an account? Sign up!</a></div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; {{ $website_info->website_short_name }}  {{ date('Y') }}</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
@stop

@section('frontend_auth_custom_scripts')
@stop
