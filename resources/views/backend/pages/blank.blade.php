@extends('backend.global.master', ['menu' => ''])
@section('title', __(''))

@section('backend_custom_stylesheet')
@stop

@section('backend_content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Heading</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Sub heading</li>
            </ol>
        </div>
    </main>
@stop

@section('backend_custom_scripts')
@stop
