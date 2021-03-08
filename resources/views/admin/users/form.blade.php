
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
@if ($formMode === 'edit')
    <div class="form-group">
        <label>Old Password:</label>
        <input type="password" class="form-control" value="12345678" readonly>
    </div>
@endif
<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    {!! Form::label('password', ($formMode == 'edit' ? 'Set New Password: ' : 'Password: '), ['class' => 'control-label']) !!}
    @php
        $passwordOptions = ['class' => 'form-control'];
        if ($formMode === 'create') {
            $passwordOptions = array_merge($passwordOptions, ['required' => 'required']);
        }
    @endphp
    {!! Form::password('password', $passwordOptions) !!}
    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('role_id') ? ' has-error' : ''}}">
    {!! Form::label('role_id', 'Role: ', ['class' => 'control-label']) !!}
    {!! Form::select('role_id', $roles, isset($user->role_id) ? $user->role_id : [], ['class' => 'form-control']) !!}
</div>
    <div class="form-group">
        {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

