@extends('dashboards.support.layouts.master')
@section('title')
داشبورد
@stop

@section('styles')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

@section('content')
<div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                       <h5 class="page-title">Welcome <b>{{auth()->user()->name}}</h5>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
    
</div>

@endsection

@section('scripts')
<script src="{{asset('js/dashboard_charts.js')}}"></script>
@stop