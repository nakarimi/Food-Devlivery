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
                    <h4 class="card-title mb-0">  لیست مشتریان علاقمند ({{count($list)}}) </h4>
                </div>
            </div>

            <div class="card-body">
               <div class="table-responsive itemList">
                  <table class="table table-striped mb-0 datatable">
                     <thead>
                        <tr>
                           <th class="disable_sort">#</th>
                           <th class="disable_sort">نام مشتری</th>
                           
                        </tr>
                    </thead>
                            <tbody>
                            @foreach($list as $item)

                                <tr>
                                    <td>{{ $item->id}}</td>
                                    <td>{{ $item->customer }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
               </div>
            </div>
         </div>
      </div>
@endsection

@section('scripts')
@include('dashboards.restaurant.orders.scripts')

@stop
