@extends('layouts.master')

{{--title of the page--}}
@section('title')
    Backups
@stop

{{-- Styles of the page--}}
@section('styles')

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
@stop

{{-- Page content--}}
@section('content')
    <br>
    <div class="row">
        <div class="col-6">
            @if(session()->has('message'))
                <div class="alert {{ session()->get('alertType') }} alert-dismissible" id="message-alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>
        <div class="col-md-12">
            <div class="col-auto float-right ml-auto">
                <a class="btn add-btn" href="{{ route('get-backup') }}"
                   onclick="event.preventDefault(); document.getElementById('get-backup-form').submit();"><i class="fa fa-plus"></i>Create New Backup</a>
                <form id="get-backup-form" action="{{ route('get-backup') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <form id="download-backup-form" method="POST" action="{{route('backup.download')}}" style="display: none;">
                    <input type="hidden" name="backup_name" id="backup_name">
                    @csrf
                </form>
            </div>
            <div>
                <table class="table table-border custom-table mb-0 datatable searchable-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Backup Name</th>
                        <th>File Size</th>
                        <th>Last Modified</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($backups as $backup)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>Backup-{{$backup['file_name']}}</td>
                            <td>{{round($backup['file_size'] * 0.000001, 2) }} MB</td>
                            <td>{{$backup['last_modified']}}</td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-success download-button" backup-name ="{{$backup['file_name']}}" href="#"><i class="fa fa-download"> Download</i></a>
                                <a class="btn btn-sm btn-danger delete_backup_button" href="#" data-toggle="modal" data-target="#delete_backup" data-backup-name="{{$backup['file_name']}}"><i class="fa fa-remove"> Delete</i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Delete province Modal -->
    <div class="modal custom-modal fade" id="delete_backup" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Backup</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <form id="delete_backup_form" method="POST" >
                                    <input type="hidden" name="_method" value="delete">
                                    {!! csrf_field() !!}
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn" onclick="document.getElementById('delete_backup_form').submit();">Delete</a>

                                </form>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete province Modal -->
@stop

{{-- Scripts of the page--}}
@section('scripts')

    <!-- Datatable JS -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        $(".delete_backup_button").click(function() {
            var backup_name = $(this).attr('data-backup-name');
            var action = 'delete-backup/'+backup_name;
            $("#delete_backup_form").attr("action", action);
        });

        $('.download-button').click(function (e) {
            e.preventDefault();
            var backup_name = $(this).attr('backup-name');
            $('#backup_name').val(backup_name);
            $("#download-backup-form").submit();
        });
    </script>
@stop
