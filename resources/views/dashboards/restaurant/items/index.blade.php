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
                           <th>موجود</th>
                           <th class="disable_sort">تغیرات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item as $item)

                            @php $itemDetails = get_item_details($item, Session::get('itemType')); @endphp
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="{{ url('/item/' . $item->id) }}" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/' . $itemDetails->thumbnail) }}"></a>
                                        </h2>
                                    </td>
                                    <td>{{ $itemDetails->title }}</td>
                                    <td>{{ $itemDetails->price }}</td>
                                    <td>

                                        @php $checked = ''; $style = 'warning'; @endphp
                                        @if($itemDetails->details_status == "pending")
                                            <span class="badge bg-inverse-warning">معطل</span>
                                        
                                        @elseif ($itemDetails->details_status == "rejected")
                                              <span class="badge bg-inverse-warning">رد شده</span>
                                              <p><b> {{$itemDetails->notes}} </b></p>

                                        @elseif ($item->status)
                                              @php $checked = 'checked'; $style = 'success'; @endphp
                                        @endif

                                        @if($itemDetails->details_status != "pending" && $itemDetails->details_status != "rejected")
                                          <input type="checkbox" class="itemStockStatus" item_id="{{ $item->id }}" {{$checked}} data-toggle="toggle" data-on="بلی" data-off="خیر" data-onstyle="success" data-offstyle="danger">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/item/' . $item->id) }}" title="View Item"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/item/' . $item->id . '/edit') }}" title="Edit Item"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
{{--                                        <form method="POST" action="{{ url('/item' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                            {{ method_field('DELETE') }}--}}
{{--                                            {{ csrf_field() }}--}}
{{--                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Item" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
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
@include('dashboards.restaurant.orders.scripts')

@stop
