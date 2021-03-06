@extends('dashboards.restaurant.layouts.master')
@section('title')
    Dashboard
@stop

@section('styles')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">test</div>
                <div class="profile-img-wrap" style="right: 0px; top: 10px; width: 190px;">
                    <div class="profile-img">
                        <a href="#"><img alt="" src="{{ url('storage/profile_images/'.get_item_details($item)->thumbnail) }}"></a>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ url('/item') }}" title="Back"><button class="btn btn-warning btn-sm float-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <a href="{{ url('/item/' . $item->id . '/edit') }}" title="Edit Item"><button class="btn btn-primary btn-sm float-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                    <form method="POST" action="{{ url('item' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm float-right" title="Delete Item" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                    </form>
                    <br/>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $item->id }}</td>
                            </tr>
                            <tr>
                                <th> Title </th>
                                <td> {{ get_item_details($item)->details_status ?? ''}} </td>
                            </tr>
                            <tr>
                                <th> Branch </th>
                                <td> {{ $item->branch->branchDetails->title }} </td>
                            </tr>
                            <tr>
                                <th> Price </th>
                                <td> {{ get_item_details($item)->price }} </td>
                            </tr>
                            <tr>
                                <th> Package Price </th>
                                <td> {{ get_item_details($item)->package_price }} </td>
                            </tr>
                            <tr>
                                <th> Unit </th>
                                <td> {{ get_item_details($item)->unit }} </td>
                            </tr>
                            <tr>
                                <th> Description </th>
                                <td> {{ get_item_details($item)->description }} </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@stop
