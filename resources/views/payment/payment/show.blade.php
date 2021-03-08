@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Payment {{ $payment->date_and_time }}</div>
            <div class="card-body">
               <a href="{{ url('/payment') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
               <a href="{{ url('/payment/' . $payment->id . '/edit') }}" title="Edit Payment"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
{{--               <form method="POST" action="{{ url('payment' . '/' . $payment->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                  {{ method_field('DELETE') }}--}}
{{--                  {{ csrf_field() }}--}}
{{--                  <button type="submit" class="btn btn-danger btn-sm" title="Delete Payment" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>--}}
{{--               </form>--}}
               <br/>
               <br/>
               <div class="table-responsive">
                  <table class="table table">
                     <tbody>
                        <tr>
                           <th>ID</th>
                           <td>{{ $payment->id }}</td>
                        </tr>
                        <tr>
                           <th> Branch </th>
                           <td> {{ $payment->branchDetails->title }} </td>
                        </tr>
                        <tr>
                           <th> Reciever </th>
                           <td> {{ $payment->user->name }} </td>
                        </tr>
                        <tr>
                           <th> Paid Amount </th>
                           <td> {{ $payment->paid_amount }} </td>
                        </tr>
                        <tr>
                           <th> Paid Date </th>
                           <td> {{ $payment->date_and_time }} </td>
                        </tr>
                        <tr>
                           <th> Note </th>
                           <td> {{ $payment->note }} </td>
                        </tr>

                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
