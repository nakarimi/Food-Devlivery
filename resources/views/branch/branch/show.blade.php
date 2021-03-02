@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">{{ $branch->branchDetails->title }}</div>
            <div class="profile-img-wrap" style="right: 0px; top: 10px; width: 190px;">
               <div class="profile-img">
                     <a href="#"><img alt="" src="{{ url('storage/profile_images/'.$branch->branchDetails->logo) }}"></a>
               </div>
            </div>
            <div class="card-body">
            
               <a href="{{ url('/branch') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
               <a href="{{ url('/branch/' . $branch->id . '/edit') }}" title="Edit Branch"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
               <form method="POST" action="{{ url('branch' . '/' . $branch->id) }}" accept-charset="UTF-8" style="display:inline">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-danger btn-sm" title="Delete Branch" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>

                  
               </form>
               <br/>
               <br/>
               <div class="table-responsive">
                  <table class="table table">
                     <tbody>
                        
                        <tr>
                           <th>ID</th>
                           <td>{{ $branch->id }}</td>
                        </tr>
                        <tr>
                           <th>Title </th>
                           <td> {{ $branch->branchDetails->title }} </td>
                        </tr>
                        <tr>
                           <th> Business Type </th>
                           <td> {{ $branch->business_type }} </td>
                        </tr>
                        <tr>
                           <th> Main Commission </th>
                           <td> {{ $branch->mainCommission->title }} </td>
                        </tr>
                        @if (isset($branch->deliver_commission_id)) 
                            <tr>
                                <th> Delivery Commission </th>
                                <td> {{ $branch->deliveryCommission->title }} </td>
                            </tr>
                        @endif

                        <tr>
                           <th> Contact </th>
                           <td> {{ $branch->branchDetails->contact }} </td>
                        </tr>

                        <tr>
                           <th> Location </th>
                           <td> {{ $branch->branchDetails->location }} </td>
                        </tr>

                        <tr>
                           <th> Description </th>
                           <td> {{ $branch->branchDetails->description }} </td>
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