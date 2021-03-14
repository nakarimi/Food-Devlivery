@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Menu</div>
            <div class="card-body">
               <a href="{{ url('/menu/create') }}" class="btn btn-success btn-sm" title="Add New Menu">
               <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/menu') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                  <div class="input-group">
                     <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                     <span class="input-group-append">
                     <button class="btn btn-secondary" type="submit">
                     <i class="fa fa-search"></i>
                     </button>
                     </span>
                  </div>
               </form>
               <br/>
               <br/>
               <livewire:counter />
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Custom JS -->
<script src="{{asset('js/livewire_automation.js')}}"></script>
@endsection
