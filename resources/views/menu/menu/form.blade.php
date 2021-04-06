<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ (get_role() == "restaurant") ? 'عنوان' : 'Title' }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ $menu->title ?? ''}}" required>
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ (get_role() == "restaurant") ? 'حالت' : 'Status' }}</label>
    {{-- <input class="form-control" name="status" type="number" id="status" value="{{ $menu->status ?? ''}}" required> --}}
    <select class="custom-select mr-sm-2" name="status" id="status" required>
        <option value="1"  @if( (isset($menu->status)) && ($menu->status) == '1') selected="selected" @endif>{{ (get_role() == "restaurant") ? 'موجود' : 'Enable'}}</option>
        <option value="0"  @if( (isset($menu->status)) && ($menu->status) == '0') selected="selected" @endif>{{ (get_role() == "restaurant") ? 'غیر فعال' : 'Disable'}}</option>
    </select>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
   </div>
</div>

<div class="form-group{{ $errors->has('branch_id') ? 'has-error' : ''}}">

    @if(Auth::user()->role->name == "restaurant")
    <div style="display: none;">
        <label for="branch_id" class="control-label">{{ 'Branch' }}</label>
        <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}" selected="selected" >{{ $branch->branchDetails->title }}</option>
         @endforeach
        </select>
    </div>
    @else
    <label for="branch_id" class="control-label">{{ 'Branch' }}</label>
    <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
        <option  value="" selected disabled>Select Branch</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}" @if( isset($menu->branch_id) && $branch->id == $menu->branch_id) selected="selected" @endif >{{ $branch->branchDetails->title }}</option>
        @endforeach
    </select>
    @endif
    {!! $errors->first('branch_id', '
    <p class="help-block">:message</p>
    ') !!}
</div>

<div class="form-group{{ $errors->has('items') ? 'has-error' : ''}}">
    <label for="items" class="control-label">{{ (get_role() == "restaurant") ? 'غذا ها' : 'Items' }}</label>
    {{-- <textarea class="form-control" rows="5" name="items" type="textarea" id="items" >{{ $menu->items ?? ''}}</textarea> --}}
    <select class="custom-select mr-sm-2" name="items[]" id="items" multiple required>
        @foreach($items as $item)
            @if($menu)
                <option value="{{ $item->id }}" {{ select_item_logic($menu->items, $item->id) }} >{{ get_item_details($item)->title }}</option>
            @else
                <option value="{{ $item->id }}"  >{{ get_item_details($item)->title }}</option>
            @endif
        @endforeach
    </select>
    {!! $errors->first('items', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? ((get_role() == "restaurant") ? 'ثبت کردن' : 'Update') : ((get_role() == "restaurant") ? 'اضافه کردن' : 'Create') }}">
</div>
