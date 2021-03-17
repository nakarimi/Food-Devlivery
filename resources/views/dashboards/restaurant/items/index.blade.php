@extends('dashboards.restaurant.layouts.master')
@section('title')
    غذا ها
@stop

@section('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
@stop

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">غذا ها</h4>
                    <a class="btn btn-success" href="{{route('item.create')}}">اضافه کردن غذا جدید</a>
                </div>
            </div>

            <div class="card-body">
               <div class="table-responsive itemList">
                  <table class="table table-striped mb-0 datatable">
                     <thead>
                        <tr>
                           <th class="disable_sort">#</th>
                           <th class="disable_sort">تصویر</th>
                           <th class="disable_sort">عنوان</th>
                           <th>قیمت</th>
                           <th>حالت</th>
                            <th class="disable_sort">تغیرات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item as $item)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="#" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/' . get_item_details($item, Session::get('itemType'))->thumbnail) }}"></a>
                                        </h2>
                                    </td>
                                    <td>{{ get_item_details($item, Session::get('itemType'))->title }}</td>
                                    <td>{{ get_item_details($item, Session::get('itemType'))->price }}</td>
                                    <td>
                                        @if(get_item_details($item, Session::get('itemType'))->details_status == "pending")
                                            <span class="badge bg-inverse-warning">معطل</span>
                                        @elseif (get_item_details($item, Session::get('itemType'))->detials_status == "rejected")
                                            <span class="badge bg-inverse-danger">رد شده</span>
                                            @else
                                            <span class="badge bg-inverse-success">فعال</span>
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

                   {{-- <div class="pagination-wrapper"> {!! $item->appends(['search' => Request::get('search')])->render() !!} </div> --}}
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
