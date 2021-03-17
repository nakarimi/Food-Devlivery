@extends('layouts.master')
@section('title')
    Drivers
@stop
@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Driver</div>
                    <div class="card-body">
                        <a href="{{ url('/driver/create') }}" class="btn btn-success btn-sm" title="Add New Driver">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/driver') }}" accept-charset="UTF-8"
                            class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..."
                                    value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br />
                        <br />
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>User</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($driver as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->contact }}</td>
                                            <td><span class="badge @if($item->status == 'inactive') {{'bg-inverse-danger'}} @else {{'bg-inverse-success'}} @endif"> {{ucfirst($item->status)}}</span></td>
                                            <td>
                                                <a href="{{ url('/driver/' . $item->id) }}" title="View Driver"><button
                                                        class="btn btn-info btn-xs"><i class="fa fa-eye"
                                                            aria-hidden="true"></i></button></a>
                                                <a href="{{ url('/driver/' . $item->id . '/edit') }}"
                                                    title="Edit Driver"><button class="btn btn-primary btn-xs"><i
                                                            class="fa fa-pencil-square-o"
                                                            aria-hidden="true"></i></button></a>

                                                {{-- <form method="POST" action="{{ url('/driver' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline"> --}}
                                                {{-- {{ method_field('DELETE') }} --}}
                                                {{-- {{ csrf_field() }} --}}
                                                {{-- <button type="submit" class="btn btn-danger btn-xs" title="Delete Driver" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button> --}}
                                                {{-- </form> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $driver->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
