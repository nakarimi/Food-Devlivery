@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Commission {{ $commission->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/commission') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/commission/' . $commission->id . '/edit') }}" title="Edit Commission"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('commission' . '/' . $commission->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Commission" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $commission->id }}</td>
                                    </tr>
                                    <tr><th> Title </th><td> {{ $commission->title }} </td></tr>
                                    <tr><th> Type </th><td> {{ $commission->type }} </td></tr><tr><th> Value </th><td> {{ $commission->value }} </td></tr><tr><th> Percentage </th><td> {{ $commission->percentage }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
