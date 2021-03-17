<div class="alert {{ session()->get('alertType') }} alert-dismissible" id="message-alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session()->get('message') }}
</div>

