@extends('layouts.master')

{{--title of the page--}}
@section('title')
    Admin Dashboard
@stop

{{-- Styles of the page--}}
@section('styles')

    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

{{-- Page content--}}
@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Welcome Admin!</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <h1>it is Admin Dashboard!</h1>
    </div>
@stop

{{-- Scripts of the page--}}
@section('scripts')
    <script src="{{asset('plugins/morris/morris.min.js')}}"></script>
    <script src="{{asset('plugins/raphael/raphael.min.js')}}"></script>

    <script src="{{asset('js/chart.js')}}"></script>
@stop
