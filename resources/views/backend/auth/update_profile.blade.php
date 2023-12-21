@extends('backend.global.master', ['menu' => 'update_profile'])
@section('title', __('messages.lang.update_profile'))

@section('backend_custom_stylesheet')
@stop

@section('backend_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">@lang('messages.lang.update_profile')</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">@lang('messages.lang.update_profile')</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    @lang('messages.lang.update_profile')
                </div>
                {!! Form::open(['url' => '/admin/update-profile', 'class' => 'login', 'files' => true]) !!}
                <div class="card-body">
                    @include('messages.error')
                    @include('messages.success')
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="username" class="form-label">আপনার পুরো নাম</label>
                            <input type="text" class="form-control" name="username" id="username"
                                value="{{ Auth::user()->username }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="email" class="form-label">ইমেইল</label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="phone" class="form-label">মোবাইল নম্বর</label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                value="{{ Auth::user()->phone }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="phone" class="form-label">আপনার ছবি</label>
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <input type="file" name="upload_image" id="upload_image" accept="image/png, image/jpeg, image/jpg"/>
                                    <input type="hidden" id="cropped_image" name="cropped_image">
                                </div>
                            </div>
                        </div>
                        @if (isset(Auth::user()->photo))
                            <div id="user_image" style="width:350px; margin-top:30px">
                                @php
                                    $imagePath = \App\UtilityFunction::globalImagePath('users', Auth::user()->photo);
                                @endphp
                                <img src="{{ $imagePath }}" alt="image not found" class="img-fluid">
                            </div>
                        @endif
                        <div id="uploadimage" style='display:none' class="container">
                            <div class="panel panel-primary">
                                <div class="panel-body" align="center">
                                    <div id="image_demo" style="width:350px; margin-top:30px"></div>
                                    <div id="uploaded_image" style="width:350px; margin-top:30px;"></div>
                                </div>
                                <div class="panel-footer">
                                    <button type="button" class="btn btn-info crop_image text-center text-white">ছবি ক্রপ
                                        করুন</button>
                                </div>
                            </div>
                        </div>
                        <div id="uploadimage" style='display:none' class="container">
                            <div class="panel panel-primary">
                                <div class="panel-body" align="center">
                                    <div id="image_demo" style="width:350px; margin-top:30px"></div>
                                    <div id="uploaded_image" style="width:350px; margin-top:30px;"></div>
                                </div>
                                <div class="panel-footer">
                                    <button type="button" class="btn btn-info crop_image text-center text-white">ছবি ক্রপ
                                        করুন</button>
                                </div>
                            </div>
                        </div>
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
    {!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
    <script>
        $(document).ready(function() {
            $image_crop = $('#image_demo').croppie({
                enableExif: true,
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle' //circle
                },
                boundary: {
                    width: 300,
                    height: 300
                }
            });
            $('#upload_image').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    })
                }
                reader.readAsDataURL(this.files[0]);
                $('#uploadimage').show();
            });
            $('.crop_image').click(function(event) {
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(response) {
                    //console.log(response);
                    $('#cropped_image').val(response);
                    toastr.success('ছবি সফলভাবে ক্রপ করা হয়েছে');
                })
            });
        });
    </script>
@stop
