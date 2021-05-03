<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>


    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/favicon.png')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-rtl/css/bootstrap.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{asset('css/line-awesome.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('css/RTL-style.css')}}">

    <!-- Custome CSS -->
    <link rel="stylesheet" href="{{asset('css/custome.css')}}">

    <!-- jQuery -->
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>

    @include('layouts.common_scripts')

{{--    TODO: this should only push based on component--}}
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">

    {{--   Adding specific style of each page--}}

    @yield('styles')
    @livewireStyles
    @stack('styles')
</head>
<body>

{{-- This silent audio is added here for this reason https://stackoverflow.com/a/52228983/7995302 --}}
<audio id="audio" src="{{asset('audio/silence_64kb.mp3')}}"  muted style="display:none;" ></audio>

<div id="app">

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
    @include('dashboards.restaurant.layouts.top-header')
    <!-- /Header -->

    @include('layouts.alert')

    <!-- Sidebar -->
    @include('dashboards.restaurant.layouts.sidebar')
    <!-- /Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Page Content -->
            <div class="content container-fluid">
                <!-- Page Header -->
            {{--                @include('layouts.page-header')--}}
            <!-- /Page Header -->

                @yield('content')
                {{$slot ?? ''}}

            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->
</div>


<!-- Bootstrap Core JS -->
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-rtl/js/bootstrap.min.js')}}"></script>

<!-- Slimscroll JS -->
<script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>

<!-- Custom JS -->
<script src="{{asset('js/app.js')}}"></script>

@include('layouts.pusher_script')

<!-- Specific js of pages -->
@yield('scripts')
@livewireScripts
@stack('scripts')
</body>
</html>
