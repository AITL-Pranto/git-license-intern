@extends('backend.global.master', ['menu' => 'website_settings'])
@section('title', __('ওয়েবসাইট সেটিংস'))

@section('backend_custom_stylesheet')
@stop

@section('backend_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">ওয়েবসাইট সেটিংস</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">ওয়েবসাইট সেটিংস</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-wrench me-1"></i>
                    ওয়েবসাইট সেটিংস
                </div>
                {!! Form::open(['url' => '/admin/save-website-settings', 'class' => 'login', 'files' => true]) !!}
                <div class="card-body">
                    @include('messages.error')
                    @include('messages.success')
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_name" class="form-label">ওয়েবসাইটের সংক্ষিপ্ত নাম</label>
                            <input type="text" class="form-control" name="website_name" id="website_name"
                                value="{{ $setting->website_name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_short_name" class="form-label">ওয়েবসাইটের পুরো নাম</label>
                            <input type="text" class="form-control" name="website_short_name" id="website_short_name"
                                value="{{ $setting->website_short_name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_url" class="form-label">ওয়েবসাইট ইউআরএল</label>
                            <input type="text" class="form-control" name="website_url" id="website_url"
                                value="{{ $setting->website_url }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_email" class="form-label">অফিসিয়াল ইমেইল</label>
                            <input type="email" class="form-control" name="website_email" id="website_email"
                                value="{{ $setting->website_email }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_contact" class="form-label">যোগাযোগ</label>
                            <input type="text" class="form-control" name="website_contact" id="website_contact"
                                value="{{ $setting->website_contact }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="helpline" class="form-label">হেল্পলাইন</label>
                            <input type="text" class="form-control" name="helpline" id="helpline"
                                value="{{ $setting->helpline }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_address" class="form-label">ঠিকানা</label>
                            <input type="text" class="form-control" name="website_address" id="website_address"
                                value="{{ $setting->website_address }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_facebook_link" class="form-label">ফেসবুক পেজ লিংক</label>
                            <input type="text" class="form-control" name="website_facebook_link" id="website_facebook_link"
                                value="{{ $setting->website_facebook_link }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_youtube_page_link" class="form-label">ইউটিউব চ্যানেল লিংক</label>
                            <input type="text" class="form-control" name="website_youtube_page_link" id="website_youtube_page_link"
                                value="{{ $setting->website_youtube_page_link }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="website_copyright_text" class="form-label">কপিরাইট এর বিবরণ</label>
                            <textarea type="text" class="form-control" name="website_copyright_text" id="website_copyright_text"  cols="50" rows="4">{{ $setting->website_copyright_text }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="about_us" class="form-label">আমাদের সম্পর্কে</label>
                            <textarea type="text" class="form-control" name="about_us" id="about_us"  cols="50" rows="4">{{ $setting->about_us }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="refund_return" class="form-label">রিফান্ড এবং রিটার্ন নীতি</label>
                            <textarea type="text" class="form-control" name="refund_return" id="refund_return"  cols="50" rows="4">{{ $setting->refund_return }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="terms_condition" class="form-label">টার্মস & কন্ডিশন</label>
                            <textarea type="text" class="form-control" name="terms_condition" id="terms_condition"  cols="50" rows="4">{{ $setting->terms_condition }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="privacy_policy" class="form-label">প্রাইভেসি ও পলিসি</label>
                            <textarea type="text" class="form-control" name="privacy_policy" id="privacy_policy"  cols="50" rows="4">{{ $setting->privacy_policy }}</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex">
                        <div class="mt-4 mb-3">
                            <label for="phone" class="form-label">অফিসিয়াল লোগো</label>
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <input type="file" name="website_logo" id="website_logo" accept="image/png, image/jpeg, image/jpg"/>
                                </div>
                            </div>
                        </div>
                        @if (isset($setting->website_logo))
                            <div style="width:350px; margin-top:30px">
                                @php
                                    $imagePath = \App\UtilityFunction::globalImagePath('website', $setting->website_logo);
                                @endphp
                                <img src="{{ $imagePath }}" alt="image not found" class="img-fluid">
                            </div>
                        @endif
                    </div>
                    <div class="form-group d-flex">
                        <div class="mt-4 mb-3">
                            <label for="phone" class="form-label">অফিসিয়াল ফ্যাব আইকন</label>
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <input type="file" name="website_favicon" id="website_favicon" accept="image/png, image/jpeg, image/jpg"/>
                                </div>
                            </div>
                        </div>
                        @if (isset($setting->website_favicon))
                            <div style="width:350px; margin-top:30px">
                                @php
                                    $imagePath = \App\UtilityFunction::globalImagePath('website', $setting->website_favicon);
                                @endphp
                                <img src="{{ $imagePath }}" alt="image not found" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success text-center">@lang('messages.lang.update_button')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </main>
@stop

@section('backend_custom_scripts')
@stop
