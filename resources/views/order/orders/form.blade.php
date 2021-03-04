<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
         <label for="title" class="control-label">{{ 'Title' }}</label>
         <input class="form-control" name="title" type="text" id="title" value="{{ $order->title ?? ''}}" required>
         {!! $errors->first('title', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('branch_id') ? 'has-error' : ''}}">
         <label for="branch_id" class="control-label">{{ 'Branch Id' }}</label>
         <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
         @foreach($branches as $branch)
         <option value="{{ $branch->id }}" @if( isset($order->branch_id) && $branch->id == $order->branch_id) selected="selected" @endif >{{ $branch->branchDetails->title }}</option>
         @endforeach
         </select>
         {!! $errors->first('branch_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('customer_id') ? 'has-error' : ''}}">
         <label for="customer_id" class="control-label">{{ 'Customer Id' }}</label>
         {{-- <input class="form-control" name="customer_id" type="number" id="customer_id" value="{{ $order->customer_id ?? ''}}" required> --}}
         <select class="custom-select mr-sm-2" name="customer_id" id="customer_id" required>
         @foreach($customers as $user)
         <option value="{{ $user->id }}" @if( (isset($order->customer_id) && $user->id == $order->customer_id)) selected="selected" @endif >{{ $user->name }}</option>
         @endforeach
         </select>
         {!! $errors->first('customer_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
         <label for="status" class="control-label">{{ 'Status' }}</label>
         {{-- <input class="form-control" name="status" type="text" id="status" value="{{ $order->status ?? ''}}" required> --}}
         <select class="custom-select mr-sm-2" name="status" id="status" required>
         <option value="pending"  @if( (isset($order->status)) && ($order->status) == 'pending') selected="selected" @endif>Pending</option>
         <option value="approved"  @if( (isset($order->status)) && ($order->status) == 'approved') selected="selected" @endif>Approved</option>
         <option value="reject"  @if( (isset($order->status)) && ($order->status) == 'reject') selected="selected" @endif>Reject</option>
         <option value="processing"  @if( (isset($order->status)) && ($order->status) == 'processing') selected="selected" @endif>Processing</option>
         <option value="delivered"  @if( (isset($order->status)) && ($order->status) == 'delivered') selected="selected" @endif>Delivered</option>
         <option value="completed"  @if( (isset($order->status)) && ($order->status) == 'completed') selected="selected" @endif>Complete</option>
         <option value="canceld"  @if( (isset($order->status)) && ($order->status) == 'canceld') selected="selected" @endif>Cancel</option>
         </select>
         {!! $errors->first('status', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('note') ? 'has-error' : ''}}">
         <label for="note" class="control-label">{{ 'Note' }}</label>
         <input class="form-control" name="note" type="text" id="note" value="{{ $order->note ?? ''}}" >
         {!! $errors->first('note', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('reciever_phone') ? 'has-error' : ''}}">
         <label for="reciever_phone" class="control-label">{{ 'Reciever Phone' }}</label>
         <input class="form-control" name="reciever_phone" type="text" id="reciever_phone" value="{{ $order->reciever_phone ?? ''}}" required>
         {!! $errors->first('reciever_phone', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('driver_id') ? 'has-error' : ''}}">
         <label for="driver_id" class="control-label">{{ 'Driver Id' }}</label>
         <select class="custom-select mr-sm-2" name="driver_id" id="driver_id" required>
         <option value="" >No Driver Seleced</option>
         @foreach($drivers as $user)
         <option value="{{ $user->id }}" @if( (isset($order->driver_id) && $user->id == $order->driver_id)) selected="selected" @endif >{{ $user->title }}</option>
         @endforeach
         </select>
         {!! $errors->first('customer_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('delivery_adress') ? 'has-error' : ''}}">
         <label for="delivery_adress" class="control-label">{{ 'Delivery Adress' }}</label>
         {{-- <input class="form-control" name="status" type="text" id="status" value="{{ $order->status ?? ''}}" required> --}}
         <input class="form-control" name="delivery_adress" type="text" id="delivery_adress" value="{{ $order->deliveryDetails->delivery_adress ?? ''}}" required>
         {!! $errors->first('status', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-group{{ $errors->has('contents') ? 'has-error' : ''}}">
   <label for="contents" class="control-label">{{ 'Contents' }}</label>
   <textarea class="form-control" rows="5" name="contents" type="textarea" id="contents" required>{{ $order->contents ?? ''}}</textarea>
   {!! $errors->first('contents', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>