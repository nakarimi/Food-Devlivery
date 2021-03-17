@extends('dashboards.restaurant.layouts.master')
@section('title')
    Menus
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
                                <th>حالت</th>
                                <th class="disable_sort">غذا ها</th>
                                <th class="disable_sort">تغیرات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menu as $item)
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge bg-inverse-success">فعال</span>
                                        @else
                                            <span class="badge bg-inverse-danger">غیر فعال</span>
                                        @endif
                                    </td>
                                    <td>{!! show_menu_itmes($item->items) !!}</td>
                                    <td>
                                        <a href="{{ url('/menu/' . $item->id) }}" title="View Menu"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/menu/' . $item->id . '/edit') }}" title="Edit Menu"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
{{--                                        <form method="POST" action="{{ url('/menu' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                            {{ method_field('DELETE') }}--}}
{{--                                            {{ csrf_field() }}--}}
{{--                                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Menu" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
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
