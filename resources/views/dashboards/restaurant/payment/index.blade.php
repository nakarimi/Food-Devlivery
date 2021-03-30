@extends('dashboards.restaurant.layouts.master')
@section('title')
   Payments
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Payment</div>
            <div class="card-body">
               <br/>
               <div class="table-responsive">
                  <table class="table table-striped mb-0 datatable ">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th class="disable_sort">Payer</th>
                           <th class="disable_sort">Receiver</th>
                           <th>Paid Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($payment as $item)
                        <tr>
                           <td>{{ $loop->iteration}}</td>
                           <td>{{ $item->branchDetails->title }}</td>
                           <td>{{ $item->user->name }}</td>
                           <td>{{ $item->paid_amount }}</td>
                           <td>{{ $item->date_and_time }}</td>
                            <td>
                                @if ($item->status == "pending")
                                    <span class="badge badge-warning">{{$item->status}}</span>
                                @elseif ($item->status == "approved")
                                    <span class="badge badge-success">{{$item->status}}</span>
                                @else
                                    <span class="badge badge-danger">{{$item->status}}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div class="pagination-wrapper"> {!! $payment->appends(['search' => Request::get('search')])->render() !!} </div>
               </div>
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
