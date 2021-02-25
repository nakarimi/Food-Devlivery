<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
         <label for="title" class="control-label">{{ 'Title' }}</label>
         <input class="form-control" name="title" type="text" id="title" value="{{ $commission->title ?? ''}}" required>
         {!! $errors->first('title', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('type') ? 'has-error' : ''}}">
         <label for="type" class="control-label">{{ 'Type' }}</label>
         <select class="custom-select mr-sm-2" name="type" id="type" required>
            <option value="">Commission Type</option>
            <option value="general" @if( (isset($commission->type)) && ($commission->type) == 'general') selected="selected" @endif >General</option>
            <option value="devliver" @if( (isset($commission->type)) && ($commission->type) == 'devliver') selected="selected" @endif>Delivery</option>
         </select>
         {!! $errors->first('type', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('value') ? 'has-error' : ''}}">
         <label for="value" class="control-label">{{ 'Value' }}</label>
         <input class="form-control" name="value" type="number" id="value" value="{{ $commission->value ?? ''}}" >
         {!! $errors->first('value', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('percentage') ? 'has-error' : ''}}">
         <label for="percentage" class="control-label">{{ 'Percentage' }}</label>
         <input class="form-control" name="percentage" type="number" id="percentage" value="{{ $commission->percentage ?? ''}}" >
         {!! $errors->first('percentage', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
         <label for="status" class="control-label">{{ 'Status' }}</label>
         <select class="custom-select mr-sm-2" name="status" id="status" required>
            <option value="1"  @if( (isset($commission->status)) && ($commission->status) == '1') selected="selected" @endif>Enable</option>
            <option value="0"  @if( (isset($commission->status)) && ($commission->status) == '0') selected="selected" @endif>Disable</option>
         </select>

         {!! $errors->first('status', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
   </div>
</div>
<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>