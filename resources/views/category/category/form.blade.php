<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ 'Title' }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ $category->categoryDetails->title ?? ''}}" required>
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('branch_id') ? 'has-error' : ''}}">
    <label for="branch_id" class="control-label">{{ 'Branch Id' }}</label>
    {{-- <input class="form-control" name="branch_id" type="number" id="branch_id" value="{{ $category->branch_id ?? ''}}" required> --}}
    <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
        @foreach($users as $user)
            <option value="{{ $user->id }}" @if( isset($category->branch_id) && $user->id == $category->branch_id) selected="selected" @endif >{{ $user->name }}</option>
        @endforeach
    </select>
    {!! $errors->first('branch_id', '<p class="help-block">:message</p>') !!}
</div>
   </div>
</div>

<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    {{-- <input class="form-control" name="status" type="text" id="status" value="{{ $category->status ?? ''}}" required> --}}
    <select class="custom-select mr-sm-2" name="status" id="status" required>
        <option value="1"  @if( (isset($category->status)) && ($category->status) == '1') selected="selected" @endif>Available</option>
        <option value="0"  @if( (isset($category->status)) && ($category->status) == '0') selected="selected" @endif>N/A</option>
    </select>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('thumbnail') ? 'has-error' : ''}}">
    <label for="thumbnail" class="control-label">{{ 'Thumbnail' }}</label>
    {{-- logo is used insetead thumbnail to avoid code changes. --}}
    <input class="form-control-file" name="logo" type="file" id="logo" value="{{ $category->categoryDetails->thumbnail ?? ''}}" accept="image/png, image/jpeg" >
    {!! $errors->first('thumbnail', '<p class="help-block">:message</p>') !!}
</div>
   </div>
</div>

<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">{{ 'Description' }}</label>
    <textarea class="form-control" name="description" id="description" rows="3">{{ $category->categoryDetails->description ?? ''}}</textarea>
    {!! $errors->first('description', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
