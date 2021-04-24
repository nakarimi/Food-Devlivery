<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
         <label for="title" class="control-label">{{ 'Title' }}</label>
         <input class="form-control" name="title" type="text" id="title" value="{{ $branch->branchDetails->title ?? $_GET['title'] ?? ''}}" required>
         {!! $errors->first('title', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('business_type') ? 'has-error' : ''}}">
         <label for="business_type" class="control-label">{{ 'Business Type' }}</label>
         <select class="custom-select mr-sm-2" name="business_type" id="business_type" required>
            <option value="food"  @if( (isset($branch->business_type)) && ($branch->business_type) == 'food') selected="selected" @endif>Food</option>
            <option value="super_market"  @if( (isset($branch->business_type)) && ($branch->business_type) == 'super_market') selected="selected" @endif>Super Market</option>
            <option value="other"  @if( (isset($branch->business_type)) && ($branch->business_type) == 'other') selected="selected" @endif>Other</option>
         </select>

         {!! $errors->first('business_type', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('main_commission_id') ? 'has-error' : ''}}">
         <label for="main_commission_id" class="control-label">{{ 'Main Commission' }}</label>
         <select class="custom-select mr-sm-2" name="main_commission_id" id="main_commission_id" required>
            <option value="">Main Commission Type</option>
            @foreach($commissions as $commission)
               @if($commission->type == 'general')
                <option value="{{ $commission->id }}" @if( isset($branch->main_commission_id) && $commission->id == $branch->main_commission_id) selected="selected" @endif >{{ $commission->title }}</option>
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
         <label for="deliver_commission_id" class="control-label">{{ 'Deliver Commission' }}</label>
         <!-- <input class="form-control" name="deliver_commission_id" type="number" id="deliver_commission_id" value="{{ $branch->deliver_commission_id ?? ''}}" > -->

         <select class="custom-select mr-sm-2" name="deliver_commission_id" id="deliver_commission_id" >
            <option value="">Delivery Commission Type</option>
            @foreach($commissions as $commission)
               @if($commission->type == 'delivery')
                <option value="{{ $commission->id }}"   @if( isset($branch->deliver_commission_id) && $commission->id == $branch->deliver_commission_id) selected="selected" @endif >{{ $commission->title }}</option>
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
         <input class="form-control" name="contact" type="text" id="contact" value="{{ $branch->branchDetails->contact ?? ''}}" >
         {!! $errors->first('contact', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('location') ? 'has-error' : ''}}">
         <label for="location" class="control-label">{{ 'Location' }}</label>
         <input class="form-control" name="location" type="text" id="location" value="{{ $branch->branchDetails->location ?? ''}}" >
         {!! $errors->first('location', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">

   <div class="col">
      <div class="form-group{{ $errors->has('banner') ? 'has-error' : ''}}">
         <label for="logo" class="control-label">{{ 'Banner' }}</label>
         <input class="form-control-file" name="banner" type="file" id="banner" value="{{ $branch->branchDetails->banner ?? ''}}" accept="image/png, image/jpeg" >
         {!! $errors->first('banner', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>

   <div class="col">
      <div class="form-group{{ $errors->has('logo') ? 'has-error' : ''}}">
         <label for="logo" class="control-label">{{ 'Logo' }}</label>
         <input class="form-control-file" name="logo" type="file" id="logo" value="{{ $branch->branchDetails->logo ?? ''}}" accept="image/png, image/jpeg" >
         {!! $errors->first('logo', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('user_id') ? 'has-error' : ''}}">
         <label for="user_id" class="control-label">{{ 'User' }}</label>
         <select class="custom-select mr-sm-2" name="user_id" id="user_id" required @if(isset($_GET['userId'])) style="pointer-events: none" @endif>
            @foreach($users as $user)
               <option value="{{ $user->id }}" @if( (isset($branch->user_id) && $user->id == $branch->user_id) || $user->id == ($_GET['userId'] ?? '')) selected="selected"  @endif>{{ $user->name }}</option>
            @endforeach
         </select>

         {!! $errors->first('user_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
         <label for="status" class="control-label">{{ 'Status' }}</label>
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
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
   <label for="description" class="control-label">{{ 'Description' }}</label>
   <textarea class="form-control" name="description" id="description" rows="3">{{ $branch->branchDetails->description ?? ''}}</textarea>
   {!! $errors->first('description', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
