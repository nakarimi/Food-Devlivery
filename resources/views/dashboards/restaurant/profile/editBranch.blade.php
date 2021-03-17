@extends('dashboards.restaurant.layouts.master')
@section('title')
    Edit ({{ $branch->branchDetails->title}})
@stop

@section('styles')
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Branch</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ url('/branch/' . $branch->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
                                    <label for="title" class="control-label">{{ 'Title' }}</label>
                                    <input class="form-control" name="title" type="text" id="title" value="{{ $branch->branchDetails->title}}" required>
                                    {!! $errors->first('title', '
                                    <p class="help-block">:message</p>
                                    ') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group{{ $errors->has('contact') ? 'has-error' : ''}}">
                                    <label for="contact" class="control-label">{{ 'Contact' }}</label>
                                    <input class="form-control" name="contact" type="text" id="contact" value="{{ $branch->branchDetails->contact ?? ''}}" >
                                    {!! $errors->first('contact', '
                                    <p class="help-block">:message</p>
                                    ') !!}
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group{{ $errors->has('location') ? 'has-error' : ''}}">
                                    <label for="location" class="control-label">{{ 'Location' }}</label>
                                    <input class="form-control" name="location" type="text" id="location" value="{{ $branch->branchDetails->location ?? ''}}" >
                                    {!! $errors->first('location', '
                                    <p class="help-block">:message</p>
                                    ') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group{{ $errors->has('logo') ? 'has-error' : ''}}">
                                    <label for="logo" class="control-label">{{ 'Logo' }}</label>
                                    <input class="form-control-file" name="logo" type="file" id="logo" value="{{ $branch->branchDetails->logo ?? ''}}" accept="image/png, image/jpeg" >
                                    {!! $errors->first('logo', '
                                    <p class="help-block">:message</p>
                                    ') !!}
                                </div>
                            </div>
                            <div class="col">

                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
                            <label for="description" class="control-label">{{ 'Description' }}</label>
                            <textarea class="form-control" name="description" id="description" rows="3">{{ $branch->branchDetails->description ?? ''}}</textarea>
                            {!! $errors->first('description', '
                            <p class="help-block">:message</p>
                            ') !!}
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Update">
                        </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

@stop
