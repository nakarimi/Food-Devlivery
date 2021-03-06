@extends('layouts.master')
@section('title')
   Driver {{ $driver->title }}
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Driver {{ $driver->title }}</div>
            <div class="card-body">
               <a href="{{ url('/driver') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
               <a href="{{ url('/driver/' . $driver->id . '/edit') }}" title="Edit Driver"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
               <form method="POST" action="{{ url('driver' . '/' . $driver->id) }}" accept-charset="UTF-8" style="display:inline">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-danger btn-sm" title="Delete Driver" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Inactive</button>
               </form>
               <br/>
               <br/>
               <div class="table-responsive">
                  <table class="table table">
                     <tbody>
                        <tr>
                           <th>ID</th>
                           <td>{{ $driver->id }}</td>
                        </tr>
                        <tr>
                           <th> Title </th>
                           <td> {{ $driver->title }} </td>
                        </tr>
                        <tr>
                           <th> Contact </th>
                           <td> {{ $driver->contact }} </td>
                        </tr>
                        <tr>
                           <th> Token </th>
                           <td> {{ $driver->token }} </td>
                        </tr>
                        <tr>
                           <th> Status </th>
                           <td><span class="badge @if($driver->status == 'inactive') {{'bg-inverse-danger'}} @else {{'bg-inverse-success'}} @endif"> {{ucfirst($driver->status)}}</span></td>
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