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
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{asset('css/line-awesome.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">


    {{--   Adding specific style of each page--}}

    @yield('styles')

</head>
<body>
<div id="app">
    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
    @include('layouts.top-header')
    <!-- /Header -->

        <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- /Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Page Content -->
            <div class="content container-fluid">
            <!-- Page Header -->
                @include('layouts.page-header')
            <!-- /Page Header -->


                @yield('content')

            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->
</div>
<!-- jQuery -->
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>

<!-- Bootstrap Core JS -->
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>

<!-- Slimscroll JS -->
<script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>

<!-- Custom JS -->
<script src="{{asset('js/app.js')}}"></script>

<!-- Specific js of pages -->
@yield('scripts')

</body>
</html>