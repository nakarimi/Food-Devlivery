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
         <input class="form-control" name="main_commission_id" type="number" id="main_commission_id" value="{{ $branch->main_commission_id ?? ''}}" required>
         {!! $errors->first('main_commission_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('deliver_commission_id') ? 'has-error' : ''}}">
         <label for="deliver_commission_id" class="control-label">{{ 'Deliver Commission Id' }}</label>
         <input class="form-control" name="deliver_commission_id" type="number" id="deliver_commission_id" value="{{ $branch->deliver_commission_id ?? ''}}" >
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
         <input class="form-control" name="status" type="text" id="status" value="{{ $branch->status ?? ''}}" required>
         {!! $errors->first('status', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-group{{ $errors->has('user_id') ? 'has-error' : ''}}">
   <label for="user_id" class="control-label">{{ 'User Id' }}</label>
   <input class="form-control" name="user_id" type="number" id="user_id" value="{{ $branch->user_id ?? ''}}" required>
   {!! $errors->first('user_id', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
   <label for="description" class="control-label">{{ 'Description' }}</label>
   <textarea class="form-control" name="description" id="description" rows="3" value="{{ $branch->description ?? ''}}"></textarea>
   {!! $errors->first('description', '
   <p class="help-block">:message</p>
   ') !!}
</div>
<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>