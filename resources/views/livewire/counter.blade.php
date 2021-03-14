<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Menu</div>
                <div class="card-body">
                    <a href="{{ url('/menu/create') }}" class="btn btn-success btn-sm" title="Add New Menu">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a>
                    <form class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search..." wire:model='keyword'>
                            <span class="input-group-append">
                     <button class="btn btn-secondary" type="submit">
                     <i class="fa fa-search"></i>
                     </button>
                     </span>
                        </div>
                    </form>
                    <br/>
                    <br/>

                        <div class="container">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--         	@if($menu)--}}
                                    @foreach($menu as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->branch->branchDetails->title }}</td>
                                            <td>
                                                @if($item->status == 1)
                                                    <span class="badge bg-inverse-success">Active</span>
                                                @else
                                                    <span class="badge bg-inverse-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('/menu/' . $item->id) }}" title="View Menu"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                                <a href="{{ url('/menu/' . $item->id . '/edit') }}" title="Edit Menu"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{--	         @endif--}}
                                    </tbody>
                                </table>
                                <div class="pagination-wrapper"> {!! $menu->links() !!} </div>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        Livewire.emit('refreshMenus');
        // alert("updated!");
        // }
        // alert();
    });
</script>
