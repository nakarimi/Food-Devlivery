@extends('layouts.master')
@section('title')
   All Branches
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Branch</div>
            <div class="card-body">
               <a href="{{ url('/branch/create') }}" class="btn btn-success btn-sm" title="Add New Branch">
               <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/branch') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                  <div class="input-group">
                     <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                     <span class="input-group-append">
                     <button class="btn btn-secondary" type="submit">
                     <i class="fa fa-search"></i>
                     </button>
                     </span>
                  </div>
               </form>
               <br/>
               <br/>
               <div class="table-responsive">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Thumbnail</th>
                           <th>Title</th>
                           <th>Business Type</th>
                           <th>Rejected Reason</th>
                           <th>Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($branch as $item)
                         <?php $itemDetails = get_branch_details($item, Session::get('branchType')); ?>
                        <tr>
                           <td>{{ $loop->iteration }}</td>
                           <td>
                                <h2 class="table-avatar">
                                    <a href="#" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/' .$itemDetails->logo) }}"></a>
                                </h2>
                            </td>
                           <td>{{ $itemDetails->title }}</td>
                           <td>{{ $item->business_type }}</td>
                           <td>{{ $itemDetails->note }}</td>
                           <td>
                               @if (\Request::is('pendingBranches'))
                                   <form method="POST" action="{{ url('/approveBranch') }}" accept-charset="UTF-8" style="display:inline">
                                       {{ csrf_field() }}
                                       <input type="hidden" value="{{$itemDetails->id}}" name="branch_detail_id">
                                       <input type="hidden" value="{{$item->id}}" name="branch_id">
                                       <button class="btn btn-sm btn-success" title="Approve" onclick="return confirm(&quot;Confirm approve?&quot;)"><i class="la la-check"></i></button>
                                   </form>
                                   <button class="btn btn-sm btn-danger reject_branch_update" branch_detail_id="{{$itemDetails->id}}" title="Reject" data-toggle="modal" data-target="#open_reject_form"><i class="la la-times"></i></button>
                               @endif

                              <a href="{{ url('/branch/' . $item->id) }}" title="View Branch"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                              
                              @if (Session::get('branchType') != "rejected")
                                 <a href="{{ url('/branch/' . $item->id . '/edit') }}" title="Edit Branch"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                              @endif
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div class="pagination-wrapper"> {!! $branch->appends(['search' => Request::get('search')])->render() !!} </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Add reject reason Modal -->
<div id="open_reject_form" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Any Reject reason?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    {!! csrf_field() !!}
                    <input type="text" value="" name="branch_detail_id">
                    <div class="form-group">
                        <label>Note:</label>
                        <textarea class="form-control" name="note" rows="4"></textarea>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" id="sumit_branch_reject_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add reject reason Modal -->

@endsection
