<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('branch_id') ? 'has-error' : ''}}">
         <label for="branch_id" class="control-label">{{ (get_role() == "restaurant") ? 'رستورانت' : 'Branch'}}</label>
         {{-- <input class="form-control" name="branch_id" type="number" id="branch_id" value="{{ $item->branch_id ?? ''}}" required> --}}
         <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" required>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" @if( isset($item->branch_id) && $branch->id == $item->branch_id) selected="selected" @endif >{{ $branch->branchDetails->title }}</option>
            @endforeach
         </select>
         {!! $errors->first('branch_id', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('status') ? 'has-error' : ''}}">
         <label for="status" class="control-label">{{ (get_role() == "restaurant") ? 'حالت' : 'Status'}}</label>
         {{-- <input class="form-control" name="status" type="text" id="status" value="{{ $item->status ?? ''}}" required> --}}
         <select class="custom-select mr-sm-2" name="status" id="status" required>
            <option value="1"  @if( (isset($item->status)) && ($item->status) == '1') selected="selected" @endif>Available</option>
            <option value="0"  @if( (isset($item->status)) && ($item->status) == '0') selected="selected" @endif>N/A</option>
         </select>
         {!! $errors->first('status', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('title') ? 'has-error' : ''}}">
         <label for="title" class="control-label">{{ (get_role() == "restaurant") ? 'عنوان' : 'Title'}}</label>
         <input class="form-control" name="title" type="text" id="title" value="{{ get_item_details($item, Session::get('itemType'))->title ?? ''}}" required>
         {!! $errors->first('title', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('code') ? 'has-error' : ''}}">
         <label for="category_id" class="control-label">{{ (get_role() == "restaurant") ? 'کتگوری' : 'Category' }}</label>
         <select class="custom-select mr-sm-2" name="category_id" id="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @if( isset($item->category_id) && $category->id == $item->category_id) selected="selected" @endif >{{ $category->title }}</option>
            @endforeach
         </select>
         {!! $errors->first('code', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
         <label for="description" class="control-label">{{ (get_role() == "restaurant") ? 'توضیحات' : 'Description' }}</label>
         <textarea class="form-control" name="description" id="description" rows="3">{{ get_item_details($item, Session::get('itemType'))->description ?? ''}}</textarea>
         {!! $errors->first('description', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('price') ? 'has-error' : ''}}">
         <label for="price" class="control-label">{{ (get_role() == "restaurant") ? 'قیمت' : 'Price' }}</label>
         <input class="form-control" name="price" type="number" id="price" value="{{ get_item_details($item, Session::get('itemType'))->price ?? ''}}" required>
         {!! $errors->first('price', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('package_price') ? 'has-error' : ''}}">
         <label for="package_price" class="control-label">{{ (get_role() == "restaurant") ? 'قیمت بسته بندی' : 'Package Price'}}</label>
         <input class="form-control" name="package_price" type="number" id="package_price" value="{{ get_item_details($item, Session::get('itemType'))->package_price ?? ''}}" >
         {!! $errors->first('package_price', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-row">
   <div class="col">
      <div class="form-group{{ $errors->has('unit') ? 'has-error' : ''}}">
         <label for="unit" class="control-label">{{ (get_role() == "restaurant") ? 'واحد' : 'Unit' }}</label>
         <input class="form-control" name="unit" type="text" id="unit" value="{{ get_item_details($item, Session::get('itemType'))->unit ?? ''}}" >
         {!! $errors->first('unit', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
   <div class="col">
      <div class="form-group{{ $errors->has('thumbnail') ? 'has-error' : ''}}">
         <label for="thumbnail" class="control-label">{{ (get_role() == "restaurant") ? 'تصویر' : 'Thumbnail' }}</label>
         {{-- logo is used insetead thumbnail to avoid code changes. --}}
         <input class="form-control-file" name="logo" type="file" id="logo" value="{{ get_item_details($item, Session::get('itemType'))->thumbnail ?? ''}}" accept="image/png, image/jpeg" >
         {!! $errors->first('thumbnail', '
         <p class="help-block">:message</p>
         ') !!}
      </div>
   </div>
</div>
<div class="form-group">
   <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? ((get_role() == "restaurant") ? 'ثبت کردن' : 'Update') : ((get_role() == "restaurant") ? 'اضافه کردن' : 'Create') }}">
</div>
