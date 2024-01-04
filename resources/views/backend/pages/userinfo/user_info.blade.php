@extends('backend.global.master', ['menu' => 'manage_user_info'])
@section('title', __('ওয়ার্ড List'))

@section('backend_custom_stylesheet')
@stop

@section('backend_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">ব্যবহারকারীর তথ্য তালিকা</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">ব্যবহারকারীর তথ্য তালিকা</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    ওয়ার্ড তালিকা | মোট : <span class="badge bg-primary">{{ $count }}</span>
                    <button data-id="add_id" data-bs-toggle="modal" data-bs-target="#form_modal"
                        class="btn m-b-xs btn-sm btn-primary btn-addon float-end" id="add_btn"><i
                            class="fa fa-plus"></i>নতুন ওয়ার্ড যুক্ত করুন
                    </button>
                </div>
                <div class="card-body">
                    @include('messages.error')
                    @include('messages.success')
                    @if (!empty($userinfos[0]))
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="text-align: center;">
                                <tr>
                                    <th>ক্রমিক নম্বর</th>
                                    <th>নাম</th>
                                    <th>রক্তের গ্রুপ</th>
                                    <th>লিঙ্গ</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                @php
                                    $index = ($userinfos->currentPage() - 1) * $userinfos->perPage() + 1;
                                @endphp
                                @foreach ($userinfos as $userinfo)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $userinfo->username }}</td>
                                        <td>{{ $userinfo->blood_group }}</td>
                                        <td>{{ $userinfo->gender }}</td>
                                        <td>
                                            <button class="dropdown-item edit_btn" data-bs-toggle="modal"
                                                data-bs-target="#form_modal" data-id="{{ $userinfo->id }}">
                                                <i class="fas fa-pencil"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button data-bs-toggle="modal" data-bs-target="#delete_{{ $userinfo->id }}"
                                                class="dropdown-item hover-red">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php
                                        $index++;
                                    @endphp
                                    <div class="modal fade" id="delete_{{ $userinfo->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">মুছে ফেলার কনফার্মেশন!
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>আপনি কি এই তথ্যটি মুছে ফেলতে চান?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">বাতিল করুন</button>
                                                    <a href="{{ url('admin/manage-ward/delete/' . $userinfo->id) }}"
                                                        class="btn btn-primary">মুছে ফেলুন</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">

                            @if ($userinfos instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $userinfos->withQueryString()->links('pagination::bootstrap-4') }}
                            @endif

                        </div>
                    @else
                        @include('messages.no_data_found')
                    @endif
                </div>
            </div>


            <!-- Role Permission Handle Modal -->
            <div class="modal fade" id="form_modal" tabindex="-1" aria-labelledby="form_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_title">Add</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="text-center load_image" style="margin-top: 23px;">
                            <img src="{{ asset('images/ring-alt.gif') }}" style="width:50px;" alt="">

                            <div>লোডিং হচ্ছে.....</div>
                        </div>
                        {!! Form::open(['url' => '/admin/save-ward', 'id' => 'modal_form', 'files' => false]) !!}
                        <div class="modal-body" id="modal_body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল করুন</button>
                            <button type="submit" id="saveBtn" class="btn btn-primary single-submit-btn">সংরক্ষণ
                                করুন</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!-- Role Permission Handle Modal -->

        </div>
    </main>
@stop

@section('backend_custom_scripts')
    {!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
    <script>
        $(document).ready(function() {
            $("#add_btn").click(function() {
                var id = $(this).data('id');
                $("#modal_body").empty();
                $('.load_image').show();
                $.ajax({
                    type: "POST",
                    url: $('#modal_form').attr('action') + '?add_id=' + id,
                    data: $('#modal_form').serialize(),
                    dataType: "json",
                    success: function(data) {
                        $('.modal-title').html('নতুন ওয়ার্ড যুক্ত করুন');
                        $("#modal_body").html(data.data_generate);
                        $('.load_image').hide();
                    }
                }).fail(function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                });
            });

            $(".edit_btn").click(function() {
                var id = $(this).data('id');
                $("#modal_body").empty();
                $('.load_image').show();
                $.ajax({
                    type: "POST",
                    url: $('#modal_form').attr('action') + '?edit_id=' + id,
                    data: $('#modal_form').serialize(),
                    dataType: "json",
                    success: function(data) {
                        $('.modal-title').html('সম্পাদনা করুন');
                        $("#modal_body").html(data.data_generate);
                        $('.load_image').hide();
                    }
                }).fail(function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                });
            });
        });
    </script>
@stop

