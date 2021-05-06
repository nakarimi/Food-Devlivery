<div class="form-row">
  <div class="col">
    <div class="form-group{{ $errors->has('title') ? 'has-error' : '' }}">
      <label for="title" class="control-label">{{ 'Title' }}</label>
      <input class="form-control" name="title" type="text" id="title" value="{{ $order->title ?? '' }}" required>
      {!! $errors->first(
    'title',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
  <div class="col">
    <div class="form-group{{ $errors->has('branch_id') ? 'has-error' : '' }}">
      <label for="branch_id" class="control-label">{{ 'Branch Id' }}</label>
      <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
        @foreach ($branches as $branch)
          <option value="{{ $branch->id }}" @if (isset($order->branch_id) && $branch->id == $order->branch_id) selected="selected" @endif>{{ $branch->branchDetails->title }}</option>
        @endforeach
      </select>
      {!! $errors->first(
    'branch_id',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
</div>
<div class="form-row">
  <div class="col">
    <div class="form-group{{ $errors->has('customer_id') ? 'has-error' : '' }}">
      <label for="customer_id" class="control-label">{{ 'Customer Id' }}</label>
      {{-- <input class="form-control" name="customer_id" type="number" id="customer_id" value="{{ $order->customer_id ?? ''}}" required> --}}
      <select class="custom-select mr-sm-2" name="customer_id" id="customer_id" required>
        @foreach ($customers as $user)
          <option value="{{ $user->id }}" @if (isset($order->customer_id) && $user->id == $order->customer_id) selected="selected" @endif>{{ $user->name }}</option>
        @endforeach
      </select>
      {!! $errors->first(
    'customer_id',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
  <div class="col">
    <div class="form-group{{ $errors->has('status') ? 'has-error' : '' }}">
      <label for="status" class="control-label">{{ 'Status' }}</label>
      {{-- <input class="form-control" name="status" type="text" id="status" value="{{ $order->status ?? ''}}" required> --}}
      <select class="custom-select mr-sm-2" name="status" id="status" required>
        <option value="pending" @if (isset($order->status) && $order->status == 'pending') selected="selected" @endif>Pending</option>
        <option value="reject" @if (isset($order->status) && $order->status == 'reject') selected="selected" @endif>Reject</option>
        <option value="processing" @if (isset($order->status) && $order->status == 'processing') selected="selected" @endif>Processing</option>
        <option value="delivered" @if (isset($order->status) && $order->status == 'delivered') selected="selected" @endif>Delivered</option>
        <option value="completed" @if (isset($order->status) && $order->status == 'completed') selected="selected" @endif>Complete</option>
        <option value="canceld" @if (isset($order->status) && $order->status == 'canceld') selected="selected" @endif>Cancel</option>
      </select>
      {!! $errors->first(
    'status',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
</div>
<div class="form-row">
  <div class="col">
    <div class="form-group{{ $errors->has('note') ? 'has-error' : '' }}">
      <label for="note" class="control-label">{{ 'Note' }}</label>
      <input class="form-control" name="note" type="text" id="note" value="{{ $order->note ?? '' }}">
      {!! $errors->first(
    'note',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
  <div class="col">
    <div class="form-group{{ $errors->has('reciever_phone') ? 'has-error' : '' }}">
      <label for="reciever_phone" class="control-label">{{ 'Reciever Phone' }}</label>
      <input class="form-control" name="reciever_phone" type="text" id="reciever_phone"
        value="{{ $order->reciever_phone ?? '' }}" required>
      {!! $errors->first(
    'reciever_phone',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
</div>

<div class="form-row">
  <div class="col">
    <div class="form-group{{ $errors->has('delivery_type') ? 'has-error' : '' }}">
      <label for="delivery_type" class="control-label">{{ 'Delivery Type' }}</label>
      <select class="custom-select mr-sm-2" name="delivery_type" id="delivery_type" required>
        <option value="self" @if ($order->has_delivery == 1 && $order->deliveryDetails->delivery_type == 'pending') selected="self" @endif>Self</option>
        <option value="own" @if ($order->has_delivery == 1 && $order->deliveryDetails->delivery_type == 'own') selected="selected" @endif>Own</option>
        <option value="company" @if ($order->has_delivery == 1 && $order->deliveryDetails->delivery_type == 'company') selected="selected" @endif>Company</option>
      </select>
      {!! $errors->first(
    'customer_id',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
  <div class="col">
    <div class="form-group{{ $errors->has('delivery_address') ? 'has-error' : '' }}">
      <label for="delivery_address" class="control-label">{{ 'Delivery Adress' }}</label>
      {{-- <input class="form-control" name="status" type="text" id="status" value="{{ $order->status ?? ''}}" required> --}}
      <input class="form-control" name="delivery_address" type="text" id="delivery_address"
        value="{{ $order->deliveryDetails->delivery_address ?? '' }}">
      {!! $errors->first(
    'status',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div>
</div>

<div class="form-row">
  {{-- <div class="col">
    <div class="form-group{{ $errors->has('driver_id') ? 'has-error' : '' }}">
      <label for="driver_id" class="control-label">{{ 'Driver Id' }}</label>
      <select class="custom-select mr-sm-2" name="driver_id" id="driver_id">
        <option value="">No Driver Seleced</option>
        @foreach ($drivers as $user)
          <option value="{{ $user->id }}" @if (isset($order->deliveryDetails->driver_id) && $user->id == $order->deliveryDetails->driver_id) selected="selected" @endif>{{ $user->title }}</option>
        @endforeach
      </select>
      {!! $errors->first(
    'customer_id',
    '
         <p class="help-block">:message</p>
         ',
) !!}
    </div>
  </div> --}}
  
</div>

<input type="text" name="contents" class="d-none">
<input type="text" name="order_id" class="d-none" value="{{ $order->id }}">
{{-- <div class="form-group{{ $errors->has('contents') ? 'has-error' : ''}}">
   <label for="contents" class="control-label" style="display: flex;"><span>{{ 'Contents' }}</span> {!! show_order_itmes($order->contents) !!}</label>
   {!! $errors->first('contents', '
   <p class="help-block">:message</p>
   ') !!}
</div> --}}
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link colleplse-toggle" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Items
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <table class="table table-borderless">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Quantity</th>
                  <th>Name</th>
                  <th>Quantity</th>
              </tr>
          </thead>
          <tbody>
               @foreach ($exist_item as $key => $item)
               @if ($key%2 == 0)
                <tr>
              @endif
                  <td>
                      <label class="flex-grow-1">{{ $item->approvedItemDetails->title }}</label>
                  </td>
                  <td>
                      <input type="number" class="form-control focusedInput d-inline-block w-75 rounded-pill items_in_order"
                        @foreach ($order->contentsToArray()['contents'] as $content)
                          @foreach ($content as $details)
                                @if ($item->id == (int) $details['item_id'])
                                  @php
                                      echo ('value="' . $details['count'] . '"');
                                  @endphp
                                @endif
                          @endforeach
                        @endforeach
                        {{-- Default value --}}
                        value="0"
                        data-item="{{$item->approvedItemLessDetails}}"
                      >
                </td>
              @if ($key%2 != 0)
                </tr>
              @endif
           @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
    <div class="card">
      <div class="card-header" id="headingOne">
        <h5 class="mb-0">
          <button class="btn btn-link colleplse-toggle" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            More Item
          </button>
        </h5>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <table class="table table-borderless">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Quantity</th>
                  <th>Name</th>
                  <th>Quantity</th>
              </tr>
          </thead>
          <tbody>
               @foreach ($non_exist_item as $key => $item)
               @if ($key%2 == 0)
                <tr>
              @endif
                  <td>
                      <label class="flex-grow-1">{{ $item->approvedItemDetails->title }}</label>
                  </td>
                  <td>
                      <input type="number" class="form-control focusedInput d-inline-block w-75 rounded-pill items_in_order"
                        {{-- Default value --}}
                        value="0"
                        data-item="{{$item->approvedItemLessDetails}}"
                      >
                </td>
              @if ($key%2 != 0)
                </tr>
              @endif
           @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="form-group">
  <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
