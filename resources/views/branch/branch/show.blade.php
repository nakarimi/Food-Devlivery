@extends('layouts.master')
@section('title')
   {{ $branch->branchDetails->title }}
@stop
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
{{--               <form method="POST" action="{{ url('branch' . '/' . $branch->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                  {{ method_field('DELETE') }}--}}
{{--                  {{ csrf_field() }}--}}
{{--                  <button type="submit" class="btn btn-danger btn-sm" title="Delete Branch" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>--}}
{{--               </form>--}}
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
                <br>
                <div class="table-responsive">
                    <h1>Branch History</h1>
                    <table class="table table-striped">
                        <thead>
                        <th>ID</th>
                        <th>title</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Status</th>
                        </thead>
                        <tbody>
                        @foreach($branch->branchFullDetails as $detail)
                            <tr>
                                <td>{{$detail->id}}</td>
                                <td>{{$detail->title}}</td>
                                <td>{{$detail->contact}}</td>
                                <td>{{$detail->location}}</td>
                                <td>{{$detail->description}}</td>
                                <td>
                                    @if ($detail->status == "pending" )
                                        <form method="POST" action="{{ url('/approveBranch') }}" accept-charset="UTF-8" style="display:inline">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{$detail->id}}" name="branch_detail_id">
                                            <input type="hidden" value="{{$branch->id}}" name="branch_id">
                                            <button class="btn btn-sm btn-info" onclick="return confirm(&quot;Confirm approve?&quot;)">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ url('/rejectBranch') }}" accept-charset="UTF-8" style="display:inline">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{$detail->id}}" name="branch_detail_id">
                                            <input type="hidden" value="{{$branch->id}}" name="branch_id">
                                            <button class="btn btn-sm btn-danger" onclick="return confirm(&quot;Confirm Reject?&quot;)">Reject</button>
                                        </form>
                                    @else
                                        {{$detail->status }}
                                    @endif
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
</div>
@endsection
