@if (session()->get('flash_message'))
    <div class="alertDiv">
        <div class="alert alert-dark alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('flash_message') }}</strong>
        </div>
    </div>
@endif