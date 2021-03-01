<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ 'Title' }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ $menu->title ?? ''}}" required>
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    {{-- <input class="form-control" name="status" type="number" id="status" value="{{ $menu->status ?? ''}}" required> --}}
    <select class="custom-select mr-sm-2" name="status" id="status" required>
        <option value="1"  @if( (isset($menu->status)) && ($menu->status) == '1') selected="selected" @endif>Enable</option>
        <option value="0"  @if( (isset($menu->status)) && ($menu->status) == '0') selected="selected" @endif>Disable</option>
    </select>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
   </div>
</div>

<div class="form-group{{ $errors->has('branch_id') ? 'has-error' : ''}}">
    <label for="branch_id" class="control-label">{{ 'Branch Id' }}</label>
    {{-- <input class="form-control" name="branch_id" type="number" id="branch_id" value="{{ $item->branch_id ?? ''}}" required> --}}
    <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
    @foreach($users as $user)
        <option value="{{ $user->id }}" @if( isset($item->branch_id) && $user->id == $item->branch_id) selected="selected" @endif >{{ $user->name }}</option>
    @endforeach
    </select>
    {!! $errors->first('branch_id', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group{{ $errors->has('items') ? 'has-error' : ''}}">
    <label for="items" class="control-label">{{ 'Items' }}</label>
    {{-- <textarea class="form-control" rows="5" name="items" type="textarea" id="items" >{{ $menu->items ?? ''}}</textarea> --}}
    <select class="custom-select mr-sm-2" name="items[]" id="items" multiple required>
        @foreach($items as $item)
            <option value="{{ $item->id }}" >{{ $item->itemDetails->title }}</option>
        @endforeach
    </select>
    {!! $errors->first('items', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
