@extends('dashboards.support.layouts.master')
@section('title')
داشبورد
@stop

@section('styles')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

@section('content')
<div class="content container-fluid">
</div>

@endsection

@section('scripts')
<script src="{{asset('js/dashboard_charts.js')}}"></script>
@stop