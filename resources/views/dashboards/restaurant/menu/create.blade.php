@extends('dashboards.restaurant.layouts.master')
@section('title')
    Create Menu
@stop

@section('styles')
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Create Menu</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ url('/menu') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        @include ('menu.menu.form', ['formMode' => 'create'])

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

@stop
