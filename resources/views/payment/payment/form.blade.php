<div class="form-row">
   <div class="col">

    <div class="form-group{{ $errors->has('branch_id') ? 'has-error' : ''}}">
        <label for="branch_id" class="control-label">{{ 'Payer' }}</label>
        {{-- <input class="form-control" name="branch_id" type="number" id="branch_id" value="{{ $payment->branch_id ?? ''}}" required> --}}
        <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
            <option value="">Select Payer</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" @if( isset($payment->branch_id) && $branch->id == $payment->branch_id) selected="selected" @endif >{{ $branch->title }}</option>
            @endforeach
         </select>

        {!! $errors->first('branch_id', '<p class="help-block">:message</p>') !!}
    </div>

   </div>
   <div class="col">
        <div class="form-group{{ $errors->has('reciever_id') ? 'has-error' : ''}}">
            <label for="reciever_id" class="control-label">{{ 'Reciever' }}</label>
            {{-- <input class="form-control" name="reciever_id" type="number" id="reciever_id" value="{{ $payment->reciever_id ?? ''}}" required> --}}

            <select class="custom-select mr-sm-2" name="reciever_id" id="reciever_id" required>
                <option value="">Select Reciever</option>
                @foreach($users as $user)
                    @if($user->role->name == "admin" || $user->role->name == "support")
                        <option value="{{ $user->id }}" @if( isset($payment->reciever_id) && $user->id == $payment->reciever_id) selected="selected" @endif >{{ $user->name }}</option>
                    @endif
                @endforeach
            </select>

            {!! $errors->first('reciever_id', '<p class="help-block">:message</p>') !!}
        </div>
   </div>
</div>


<div class="form-row">
   <div class="col">
        <div class="form-group{{ $errors->has('paid_amount') ? 'has-error' : ''}}">
            <label for="paid_amount" class="control-label">{{ 'Paid Amount' }}</label>
            <input class="form-control" name="paid_amount" type="number" step="0.01" id="paid_amount" value="{{ $payment->paid_amount ?? ''}}" required>
            {!! $errors->first('paid_amount', '<p class="help-block">:message</p>') !!}
        </div>
   </div>
   <div class="col">
        <div class="form-group{{ $errors->has('date_and_time') ? 'has-error' : ''}}">
            <label for="date_and_time" class="control-label">{{ 'Date And Time' }}</label>
            <input class="form-control" name="date_and_time" type="date" id="date_and_time" value="{{ $payment->date_and_time ?? ''}}" required>
            {!! $errors->first('date_and_time', '<p class="help-block">:message</p>') !!}
        </div>
   </div>
</div>

<div class="form-group{{ $errors->has('note') ? 'has-error' : ''}}">
    <label for="note" class="control-label">{{ 'Note' }}</label>
    {{-- <input class="form-control" name="note" type="text" id="note" value="" > --}}
    <textarea class="form-control" name="note" id="note" rows="3">{{ $payment->note ?? ''}}</textarea>

    {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
