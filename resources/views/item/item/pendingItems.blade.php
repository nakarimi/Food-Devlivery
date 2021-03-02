@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Pending Items</div>
                    <div class="card-body">
                        <a href="{{ route('item.index') }}" class="btn btn-success btn-info" title="View all Items">
                            <i class="fa fa-list" aria-hidden="true"></i> View All Items
                        </a>

                        <form method="GET" action="{{ url('/item') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th><th>Branch Id</th><th>Item Status</th><th>Item Id</th><th>Detail Status</th><th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($item as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->branch_id }}</td><td>{{ $item->status }}</td><td>{{ $item->item_id }}</td>
                                        <td>Pending</td>

                                        <td>
                                            <a href="{{ url('/item/' . $item->id) }}" title="View Item"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/item/' . $item->id . '/edit') }}" title="Edit Item"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                            <form method="POST" action="{{ url('/item' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs" title="Delete Item" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{-- There is an issue with pagination of this list, so commented out for now. --}}
                            {{-- <div class="pagination-wrapper"> {!! $item->appends(['search' => Request::get('search')])->render() !!} </div> --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
