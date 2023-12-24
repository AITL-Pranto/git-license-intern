@extends('backend.global.master', ['menu' => 'manage_ward'])
@section('title', __('ওয়ার্ড List'))

@section('backend_custom_stylesheet')
@stop

@section('backend_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">ওয়ার্ড তালিকা</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">ওয়ার্ড তালিকা</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    ওয়ার্ড তালিকা | মোট : <span class="badge bg-primary">{{ $count }}</span>

                    <a href="{{ route('adminAddWard') }}" class="btn m-b-xs btn-sm btn-primary btn-addon float-end"
                        id="add_btn"><i class="fa fa-plus"></i>নতুন ওয়ার্ড যুক্ত করুন
                    </a>
                </div>
                <div class="card-body">
                    @include('messages.error')
                    @include('messages.success')
                    @if (!empty($wards[0]))
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="text-align: center;">
                                <tr>
                                    <th>ক্রমিক নম্বর</th>
                                    <th>ওয়ার্ডের নাম</th>
                                    <th>ওয়ার্ড নং</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                @php
                                    $index = ($wards->currentPage() - 1) * $wards->perPage() + 1;
                                @endphp
                                @foreach ($wards as $ward)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $ward->bn_name }}</td>
                                        <td>{{ $ward->ward_no }}</td>
                                        <td>
                                            <a class="dropdown-item edit_btn" href="{{route("adminModifyWard",$ward->id)}}">
                                                <i class="fas fa-pencil"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <button data-bs-toggle="modal" data-bs-target="#delete_{{ $ward->id }}"
                                                class="dropdown-item hover-red">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php
                                        $index++;
                                    @endphp
                                    <div class="modal fade" id="delete_{{ $ward->id }}" tabindex="-1"
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
                                                    <a href="{{ url('admin/manage-ward/delete/' . $ward->id) }}"
                                                        class="btn btn-primary">মুছে ফেলুন</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">

                            @if ($wards instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $wards->withQueryString()->links('pagination::bootstrap-4') }}
                            @endif

                        </div>
                    @else
                        @include('messages.no_data_found')
                    @endif
                </div>
            </div>

        </div>
    </main>
@stop
