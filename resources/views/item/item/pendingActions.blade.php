<form method="POST" action="{{ url('/approveItem') }}" accept-charset="UTF-8" style="display:inline">
    {{ csrf_field() }}
    <input type="hidden" value="{{get_item_details($singleItem, Session::get('itemType'))->id}}" name="item_detail_id">
    <input type="hidden" value="{{$singleItem->id }}" name="item_id">
    <button class="btn btn-sm btn-success" title="Approve" onclick="return confirm(&quot;Confirm approve?&quot;)"><i class="la la-check"></i></button>
</form>
<a href="#" class="btn btn-sm btn-danger" title="Reject" data-toggle="modal" data-target="#open_reject_form"><i class="la la-times"></i></a>

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
                <form action="{{ url('/rejectItem') }}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" value="{{get_item_details($singleItem, Session::get('itemType'))->id}}" name="item_detail_id">
                    <div class="form-group">
                        <label>Note:</label>
                        <textarea class="form-control" name="note" rows="4"></textarea>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add reject reason Modal -->
