<div class="row">

<div class="col-md-6">
<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('name', 'Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    {!! Form::label('email', 'Email: ', ['class' => 'control-label']) !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    {!! Form::label('password', 'Password: ', ['class' => 'control-label']) !!}
    @php
        $passwordOptions = ['class' => 'form-control'];
        if ($formMode === 'create') {
            $passwordOptions = array_merge($passwordOptions, ['required' => 'required']);
        }
    @endphp
    {!! Form::password('password', $passwordOptions) !!}
    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('location') ? ' has-error' : ''}}">
    {!! Form::label('location', 'Location: ', ['class' => 'control-label']) !!}
    {!! Form::text('location', null, ['class' => 'form-control']) !!}
</div>
</div>

<div class="col-md-6">

<div class="form-group{{ $errors->has('contacts') ? ' has-error' : ''}}">
    {!! Form::label('contacts', 'Contact Number: ', ['class' => 'control-label']) !!}
    {!! Form::text('contacts', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group{{ $errors->has('role_id') ? ' has-error' : ''}}">
    {!! Form::label('role_id', 'Role: ', ['class' => 'control-label']) !!}
    {!! Form::select('role_id', $roles, isset($user->role_id) ? $user->role_id : [], ['class' => 'form-control']) !!}
</div>
    <div class="form-group{{ $errors->has('status') ? ' has-error' : ''}}">
        {!! Form::label('status', 'Status: ', ['class' => 'control-label']) !!}
        {!! Form::select('status', $status, isset($user->status) ? $user->status : [], ['class' => 'form-control']) !!}
    </div>

<div class="form-group{{ $errors->has('logo') ? ' has-error' : ''}}">
    <label for="logo" class="control-label">Photo(Logo):</label>
    <input type="file" class="form-control" name="logo" id="logo">
</div>
</div>
    <div class="form-group ml-3">
        {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
