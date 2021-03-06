@extends('dashboards.restaurant.layouts.master')
@section('title')
    مینیو ها
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
                        <h4 class="card-title mb-0">مینیو ها</h4>
                        <a class="btn btn-success" href="{{route('menu.create')}}">اضافه کردن مینیو جدید</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 datatable">
                            <thead>
                            <tr>
                                <th class="disable_sort">#</th>
                                <th class="disable_sort">عنوان</th>
                                <th class="disable_sort">غذا ها</th>
                                <th>موجود</th>
                                <th class="disable_sort">تغیرات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menu as $item)
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{!! show_menu_itmes($item->items) !!}</td>
                                    <td>
                                        @php $checked = ''; $style = 'warning'; @endphp
                                        @if ($item->status)
                                              @php $checked = 'checked'; $style = 'success'; @endphp
                                        @endif
                                        <span></span>
                                        <input type="checkbox" class="menuStockStatus" item_id="{{ $item->id }}" {{$checked}} data-toggle="toggle" data-on="بلی" data-off="خیر" data-onstyle="success" data-offstyle="danger">
                                    </td>
                                    <td>
                                        <a href="{{ url('/menu/' . $item->id) }}" title="View Menu"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/menu/' . $item->id . '/edit') }}" title="Edit Menu"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
{{--                                        <form method="POST" action="{{ url('/menu' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                            {{ method_field('DELETE') }}--}}
{{--                                            {{ csrf_field() }}--}}
{{--                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Menu" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
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
@include('dashboards.restaurant.orders.scripts')

@stop
