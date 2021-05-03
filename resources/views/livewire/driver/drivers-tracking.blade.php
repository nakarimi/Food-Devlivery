
@section('title')
    Driver Tracking
@stop

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Driver Tracking</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Total Orders</th>
                                <th>Contact</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($drivers_data as $item)
                                <tr>
                                    <td>{{ $item->title}}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->contact }}</td>
                                    <td><span class="badge @if($item->status == 'inactive') {{'bg-inverse-danger'}} @else {{'bg-inverse-success'}} @endif"> {{ucfirst($item->status)}}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JS -->
<script src="{{asset('js/order.js')}}"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    var channel = pusher.subscribe('food-app');
    channel.bind('update-event', function(data) {
        Livewire.emit('refreshDrivers');
    });
</script>

