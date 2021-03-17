@extends('dashboards.restaurant.layouts.master')
@section('title')
    Edit ({{ get_item_details($item, Session::get('itemType'))->title }})
@stop

@section('styles')
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit ({{ get_item_details($item, Session::get('itemType'))->title }})</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                        <form method="POST" action="{{ url('/item/' . $item->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('item.item.form', ['formMode' => 'edit'])

                        </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

@stop
