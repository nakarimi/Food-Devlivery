<div class="form-group{{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label">{{ 'User Id' }}</label>
    <input class="form-control" name="user_id" type="number" id="user_id" value="{{ $branch->user_id or ''}}" required>
    {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('business_type') ? 'has-error' : ''}}">
    <label for="business_type" class="control-label">{{ 'Business Type' }}</label>
    <input class="form-control" name="business_type" type="text" id="business_type" value="{{ $branch->business_type or ''}}" required>
    {!! $errors->first('business_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('main_commission_id') ? 'has-error' : ''}}">
    <label for="main_commission_id" class="control-label">{{ 'Main Commission Id' }}</label>
    <input class="form-control" name="main_commission_id" type="number" id="main_commission_id" value="{{ $branch->main_commission_id or ''}}" required>
    {!! $errors->first('main_commission_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('deliver_commission_id') ? 'has-error' : ''}}">
    <label for="deliver_commission_id" class="control-label">{{ 'Deliver Commission Id' }}</label>
    <input class="form-control" name="deliver_commission_id" type="number" id="deliver_commission_id" value="{{ $branch->deliver_commission_id or ''}}" >
    {!! $errors->first('deliver_commission_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ $branch->status or ''}}" required>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ 'Title' }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ $branch->title or ''}}" required>
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">{{ 'Description' }}</label>
    <input class="form-control" name="description" type="text" id="description" value="{{ $branch->description or ''}}" >
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('logo') ? 'has-error' : ''}}">
    <label for="logo" class="control-label">{{ 'Logo' }}</label>
    <input class="form-control" name="logo" type="text" id="logo" value="{{ $branch->logo or ''}}" >
    {!! $errors->first('logo', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('contact') ? 'has-error' : ''}}">
    <label for="contact" class="control-label">{{ 'Contact' }}</label>
    <input class="form-control" name="contact" type="text" id="contact" value="{{ $branch->contact or ''}}" >
    {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('location') ? 'has-error' : ''}}">
    <label for="location" class="control-label">{{ 'Location' }}</label>
    <input class="form-control" name="location" type="text" id="location" value="{{ $branch->location or ''}}" >
    {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
