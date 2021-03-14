<div class="col-md-12">
    <table class="table table-striped col-md-8 offset-md-2">
        <thead>
        <th>id</th>
        <th>Branch</th>
{{--        <th>title</th>--}}
        </thead>
        <tbody>
        @foreach($item as $i)
        <tr>
            <td>{{$i->id}}</td>
            <td>{{$i->branch->branchDetails->title}}</td>
{{--            <td>{{ get_item_details($i, Session::get('itemType'))->title }}</td>--}}
{{--            <td>{{  Session::get('itemType') }}</td>--}}
        </tr>
            @endforeach
        </tbody>
    </table>
    <div class="col-md-4 offset-md-4">
        <button wire:click = "refreshComponent">Reload</button>
    </div>

</div>

@livewireScripts
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('21bc4b7163b1064323d3', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        // if (JSON.stringify(data['message']) == "Items Updated!"){
            Livewire.emit('refreshItems');
            // alert("updated!");
        // }
        // alert();
    });
</script>
