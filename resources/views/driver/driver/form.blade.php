<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ 'Full Name' }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ $driver->title ?? $_GET['title'] ?? ''}}" required>
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label">{{ 'User' }}</label>
    <!-- <input class="form-control" name="user_id" type="number" id="user_id" value="{{ $driver->user_id ?? ''}}" required> -->

     <select class="custom-select mr-sm-2" name="user_id" id="user_id" required @if(isset($_GET['userId'])) style="pointer-events: none" @endif>
      @foreach($users as $user)
         <option value="{{ $user->id }}" @if( (isset($driver->user_id) && $user->id == $driver->user_id) || $user->id == ($_GET['userId'] ?? '')) selected="selected" @endif >{{ $user->name }}</option>
      @endforeach
   </select>

    {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
</div>
   </div>
</div>

<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('contact') ? 'has-error' : ''}}">
    <label for="contact" class="control-label">{{ 'Contact' }}</label>
    <input class="form-control" name="contact" type="text" id="contact" value="{{ $driver->contact ?? ''}}" >
    {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
</div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <!-- <input class="form-control" name="status" type="text" id="status" value="{{ $driver->status ?? ''}}" required> -->

    <select class="custom-select mr-sm-2" name="status" id="status" required>
        <option value="active"  @if( (isset($driver->status)) && ($driver->status) == 'active') selected="selected" @endif>Active</option>
        <option value="free"  @if( (isset($driver->status)) && ($driver->status) == 'free') selected="selected" @endif>Free</option>
        <option value="busy"  @if( (isset($driver->status)) && ($driver->status) == 'busy') selected="selected" @endif>Busy</option>
        <option value="inactive"  @if( (isset($driver->status)) && ($driver->status) == 'inactive') selected="selected" @endif>Inactive</option>

    </select>

    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
   </div>
</div>

<div class="form-group{{ $errors->has('token') ? 'has-error' : ''}}">
    <label for="token" class="control-label">{{ 'Token' }}</label>
    <input class="form-control" name="token" type="text" id="token" value="{{ $driver->token ?? ''}}" >
    {!! $errors->first('token', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
