@extends('dashboards.restaurant.layouts.master')
@section('title')
    Items
@stop

@section('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Items</h4>
                        <a class="btn btn-success" href="{{route('item.create')}}">Add New Item</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Branch</th>
                                <th>Price</th>
                                <th>Status</th>

                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item as $item)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="#" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/' . get_item_details($item)->thumbnail) }}"></a>
                                        </h2>
                                    </td>
                                    <td>{{ get_item_details($item)->title }}</td>
                                    <td>{{ @$item->branch->branchDetails->title}}</td>
                                    <td>{{ get_item_details($item)->price }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge bg-inverse-success">Active</span>
                                        @else
                                            <span class="badge bg-inverse-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/item/' . $item->id) }}" title="View Item"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/item/' . $item->id . '/edit') }}" title="Edit Item"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
{{--                                        <form method="POST" action="{{ url('/item' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                            {{ method_field('DELETE') }}--}}
{{--                                            {{ csrf_field() }}--}}
{{--                                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Item" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
{{--                                        </form>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Datatable JS -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
@stop
