@extends('backend.global.master', ['menu' => 'change_password'])
@section('title', __('messages.lang.change_password'))

@section('backend_custom_stylesheet')
@stop

@section('backend_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">@lang('messages.lang.change_password')</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">@lang('messages.lang.change_password')</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    @lang('messages.lang.change_password')
                </div>
                {!! Form::open(['url' => '/admin/update-password', 'class' => 'login']) !!}
                <div class="card-body">
                    @include('messages.error')
                    @include('messages.success')
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">পুরাতন পাসওয়ার্ড প্রদান করুন</label>
                            <input type="password" class="form-control" name="current_password" id="current_password"
                                placeholder="পুরাতন পাসওয়ার্ড প্রদান করুন" value="{{ old('current_password') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="password" class="form-label">নতুন পাসওয়ার্ড প্রদান করুন</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="নতুন পাসওয়ার্ড প্রদান করুন" value="{{ old('password') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">পুনরায় নতুন পাসওয়ার্ড প্রদান করুন</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                placeholder="পুনরায় নতুন পাসওয়ার্ড প্রদান করুন" value="{{ old('confirm_password') }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success text-center">পাসওয়ার্ড আপডেট করুন</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </main>
@stop

@section('backend_custom_scripts')
@stop
