@extends('backend.global.master', ['menu' => 'role_permission'])
@section('title', __('ব্যবহারকারীর রোল এবং পারমিশন'))

@section('backend_custom_stylesheet')
@stop

@section('backend_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">ব্যবহারকারীর রোল এবং পারমিশন</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">ব্যবহারকারীর রোল এবং পারমিশন</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    রোল এবং পারমিশন | মোট : <span class="badge bg-primary">{{ $roles_count }}</span>

                    <button data-id="add_id" data-bs-toggle="modal" data-bs-target="#form_modal"
                        class="btn m-b-xs btn-sm btn-primary btn-addon float-end" id="add_btn"><i
                            class="fa fa-plus"></i>নতুন রোল এবং পারমিশন যুক্ত করুন
                    </button>
                </div>
                <div class="card-body">
                    @if (!empty($roles[0]))
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="text-align: center;">
                                <tr>
                                    <th>ক্রমিক নম্বর</th>
                                    <th>আইডি</th>
                                    <th>রোল</th>
                                    <th>সম্পাদনা</th>
                                    <th>মুছে ফেলুন</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                @php
                                    $index = ($roles->currentPage() - 1) * $roles->perPage() + 1;
                                @endphp
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <button class="dropdown-item edit_btn" data-bs-toggle="modal"
                                                data-bs-target="#form_modal" data-id="{{ $role->id }}">
                                                <i class="fas fa-pencil"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button data-bs-toggle="modal" data-bs-target="#delete_{{ $role->id }}"
                                                class="dropdown-item hover-red">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="delete_{{ $role->id }}" tabindex="-1"
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
                                                    <a href="{{ url('admin/delete-role-permission/' . $role->id) }}"
                                                        class="btn btn-primary">মুছে ফেলুন</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $index++
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">

                            @if ($roles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $roles->withQueryString()->links('pagination::bootstrap-4') }}
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
                            <h5 class="modal-title" id="form_modal">Add</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="text-center load_image" style="margin-top: 23px;">
                            <img src="{{ asset('images/ring-alt.gif') }}" style="width:50px;" alt="">

                            <div>লোডিং হচ্ছে.....</div>
                        </div>
                        {!! Form::open(['url' => '/admin/save-role-permission', 'id' => 'modal_form']) !!}
                        <div class="modal-body" id="model_body"></div>
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
        $(function() {
            function moveItems(origin, dest) {
                $(origin).find(':selected').appendTo(dest);
            }

            function moveAllItems(origin, dest) {
                $(origin).children().appendTo(dest);
            }

            $(document.body).on('click', '#left', function() {
                moveItems('#my_multi_select2', '#my_multi_select1');
            });

            $(document.body).on('click', '#right', function() {
                moveItems('#my_multi_select1', '#my_multi_select2');
            });

            $(document.body).on('click', '#leftall', function() {
                moveAllItems('#my_multi_select2', '#my_multi_select1');
            });

            $(document.body).on('click', '#rightall', function() {
                moveAllItems('#my_multi_select1', '#my_multi_select2');
            });
        });
    </script>
    <script>
        $(function() {
            function moveItems(origin, dest) {
                $(origin).find(':selected').appendTo(dest);
            }

            function moveAllItems(origin, dest) {
                $(origin).children().appendTo(dest);
            }

            $('#left_add_modal').on('click', function() {
                moveItems('#my_multi_select2_add_modal', '#my_multi_select1_add_modal');
            });

            $('#right_add_modal').on('click', function() {
                moveItems('#my_multi_select1_add_modal', '#my_multi_select2_add_modal');
            });

            $('#leftall_add_modal').on('click', function() {
                moveAllItems('#my_multi_select2_add_modal', '#my_multi_select1_add_modal');
            });

            $('#rightall_add_modal').on('click', function() {
                moveAllItems('#my_multi_select1_add_modal', '#my_multi_select2_add_modal');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document.body).on('click', '#saveBtn', function() {
                selectBox = document.getElementById("my_multi_select2");
                for (var i = 0; i < selectBox.options.length; i++) {
                    selectBox.options[i].selected = true;
                }
            });

            $(".edit_btn").on('click', function() {
                var id = $(this).data('id');
                $("#model_body").empty();
                $('.load_image').show();
                $('.modal-title').html('রোল এবং পারমিশন সম্পাদনা করুন');
                $.ajax({
                    type: "POST",
                    url: $('#modal_form').attr('action') + '?edit_id=' + id,
                    data: $('#modal_form').serialize(),
                    dataType: "json",
                    success: function(data) {

                        $('#password_change').show();
                        $("#model_body").html(data.data_generate);
                        $('.load_image').hide();
                    }
                }).fail(function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                });
            });
            $("#add_btn").click(function() {
                var id = $(this).data('id');
                $("#model_body").empty();
                $('.load_image').show();
                $('.modal-title').html('নতুন রোল এবং পারমিশন যুক্ত করুন');
                $.ajax({
                    type: "POST",
                    url: $('#modal_form').attr('action') + '?add_id=' + id,
                    data: $('#modal_form').serialize(),
                    dataType: "json",
                    success: function(data) {
                        $('#password_change').remove();

                        $("#model_body").html(data.data_generate);
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
