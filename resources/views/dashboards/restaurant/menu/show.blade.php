@extends('dashboards.restaurant.layouts.master')
@section('title')
    Single Menu
@stop

@section('styles')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $menu->title }}</div>
                <div class="card-body">
                    <a href="{{ url('/menu') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <a href="{{ url('/menu/' . $menu->id . '/edit') }}" title="Edit Menu"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                    <form method="POST" action="{{ url('menu' . '/' . $menu->id) }}" accept-charset="UTF-8" style="display:inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Menu" onclick="return confirm(&quot;Confirm Inactive?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Inactive</button>
                    </form>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $menu->id }}</td>
                            </tr>
                            <tr>
                                <th> Title </th>
                                <td> {{ $menu->title }} </td>
                            </tr>
                            <tr>
                                <th> Branch </th>
                                <td> {{ $menu->branch->branchDetails->title }} </td>
                            </tr>
                            <tr>
                                <th> Status </th>
                                <td>
                                    @if($menu->status == 1)
                                        <span class="badge bg-inverse-success">Active</span>
                                    @else
                                        <span class="badge bg-inverse-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th> Items </th>
                                <td> {!! show_menu_itmes($menu->items) !!} </td>
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




