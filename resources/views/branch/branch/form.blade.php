<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
         <label for="title" class="control-label">{{ 'Title' }}</label>
         <input class="form-control" name="title" type="text" id="title" value="{{ $branch->title ?? ''}}" required>
         {!! $errors->first('title', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('business_type') ? 'has-error' : ''}}">
         <label for="business_type" class="control-label">{{ 'Business Type' }}</label>
         <input class="form-control" name="business_type" type="text" id="business_type" value="{{ $branch->business_type ?? ''}}" required>
         {!! $errors->first('business_type', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('main_commission_id') ? 'has-error' : ''}}">
         <label for="main_commission_id" class="control-label">{{ 'Main Commission Id' }}</label>
<!--          <input class="form-control" name="main_commission_id" type="number" id="main_commission_id" value="{{ $branch->main_commission_id ?? ''}}" required>
 -->
         <select class="custom-select mr-sm-2" name="main_commission_id" id="main_commission_id" required>
            <option value="">Main Commission Type</option>
            @foreach($commissions as $commission)
               @if($commission->type == 'general')
                <option value="{{ $commission->id }}" @if( $commission->id == $branch->main_commission_id) selected="selected" @endif >{{ $commission->title }}</option>
               @endif
            @endforeach
         </select>
         {!! $errors->first('main_commission_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('deliver_commission_id') ? 'has-error' : ''}}">
         <label for="deliver_commission_id" class="control-label">{{ 'Deliver Commission Id' }}</label>
         <!-- <input class="form-control" name="deliver_commission_id" type="number" id="deliver_commission_id" value="{{ $branch->deliver_commission_id ?? ''}}" > -->

         <select class="custom-select mr-sm-2" name="deliver_commission_id" id="deliver_commission_id" >
            <option value="">Delivery Commission Type</option>
            @foreach($commissions as $commission)
               @if($commission->type == 'delivery')
                <option value="{{ $commission->id }}"   @if( $commission->id == $branch->deliver_commission_id) selected="selected" @endif >{{ $commission->title }}</option>
               @endif
            @endforeach
         </select>


         {!! $errors->first('deliver_commission_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('contact') ? 'has-error' : ''}}">
         <label for="contact" class="control-label">{{ 'Contact' }}</label>
         <input class="form-control" name="contact" type="text" id="contact" value="{{ $branch->contact ?? ''}}" >
         {!! $errors->first('contact', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('location') ? 'has-error' : ''}}">
         <label for="location" class="control-label">{{ 'Location' }}</label>
         <input class="form-control" name="location" type="text" id="location" value="{{ $branch->location ?? ''}}" >
         {!! $errors->first('location', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('logo') ? 'has-error' : ''}}">
         <label for="logo" class="control-label">{{ 'Logo' }}</label>
         <input class="form-control-file" name="logo" type="file" id="logo" value="{{ $branch->logo ?? ''}}" accept="image/png, image/jpeg" >
         {!! $errors->first('logo', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
         <label for="status" class="control-label">{{ 'Status' }}</label>
         <!-- <input class="form-control" name="status" type="text" id="status" value="{{ $branch->status ?? ''}}" required> -->
         <select class="custom-select mr-sm-2" name="status" id="status" required>
            <option value="1"  @if( (isset($branch->status)) && ($branch->status) == '1') selected="selected" @endif>Enable</option>
            <option value="0"  @if( (isset($branch->status)) && ($branch->status) == '0') selected="selected" @endif>Disable</option>
         </select>

         {!! $errors->first('status', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-group{{ $errors->has('user_id') ? 'has-error' : ''}}">
   <label for="user_id" class="control-label">{{ 'User Id' }}</label>
   <!-- <input class="form-control" name="user_id" type="number" id="user_id" value="{{ $branch->user_id ?? ''}}" required> -->

   <select class="custom-select mr-sm-2" name="user_id" id="user_id" required>
      @foreach($users as $user)
         <option value="{{ $user->id }}" @if( $user->id == $branch->user_id) selected="selected" @endif >{{ $user->name }}</option>
      @endforeach
   </select>

   {!! $errors->first('user_id', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
   <label for="description" class="control-label">{{ 'Description' }}</label>
   <textarea class="form-control" name="description" id="description" rows="3">{{ $branch->description ?? ''}}</textarea>
   {!! $errors->first('description', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>