@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create New Menu</div>
                    <div class="card-body">
                        <a href="{{ url('/menu') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

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
    </div>
@endsection

@section('scripts')
    <script>
        $("#branch_id").on('change', function (){
            var branch_id = $(this).val(); // this.value
            $.ajax({
                url: '/loadItemsBasedOnBranch',
                data: { branchId: branch_id },
                type: 'get',
                success: function (data) {
                    $("#items option").remove();
                    $.each(data,function(key, value)
                    {
                        $("#items").append('<option value=' + key + '>' + value + '</option>');
                    });
                },
                error: function (e) {
                    alert("Error Occurred!");
                }
            })
        });
    </script>
@stop
