@section('title')
    Active Orders
@stop

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">سفارشات فعال</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('dashboards.restaurant.orders.table')
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<!-- Datatable JS -->
@push('scripts')
@include('dashboards.restaurant.orders.scripts')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    var channel = pusher.subscribe('food-app');
    channel.bind('update-event', function(data) {
        // if (JSON.stringify(data['message']) == "Items Updated!"){
        Livewire.emit('refreshActiveOrders');
    });
</script>
<script>
    // Since DOM is changing on each refresh we need to reinitilize
    // data table.
    document.addEventListener('reinitializaJSs', function () {
        $('.datatable').dataTable({
            'bPaginate': true,
            'searching' : true
        });
    });
</script>

@endpush
