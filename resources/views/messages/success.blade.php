@if (null !== Session::get('success_message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ Session::get('success_message') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
