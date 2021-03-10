@extends('layouts.master')
@section('title')
    Blocked Customers
@stop

@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Blocked Customers</div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 table-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th><th>Customer Name</th><th>Branch</th>
                                    <th>Blocked By</th><th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($blockedCustomers as $customer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $customer->customer->name }}</td>
                                        <td>{{ $customer->branch->branchDetails->title }}</td>
                                        <td>{{ $customer->user->name }}</td>
                                        <td>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/blockedCustomer', $customer->id],
                                                'style' => 'display:inline'
                                            ])
                                            !!}
                                            <button class="btn btn-danger btn-sm" type="Submit" onclick="return confirm(&quot;Confirm delete?&quot;)">Delete</button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $blockedCustomers->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
