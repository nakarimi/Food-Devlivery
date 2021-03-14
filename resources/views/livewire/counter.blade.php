<div style="text-align: center">
    <button style="visibility: hidden;" wire:click="increment" id="myElement">+</button>
<!--     <h2>{{ $count }}</h2>
 -->
    <div class="container">
  <div class="table-responsive">
      <table class="table">
         <thead>
            <tr>
               <th>#</th>
               <th>Title</th>
               <th>Branch</th>
               <th>Status</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
         	@if($menu)
	            @foreach($menu as $item)
	            <tr>
	               <td>{{ $loop->iteration }}</td>
	               <td>{{ $item->title }}</td>
	               <td>{{ $item->branch->branchDetails->title }}</td>
	               <td>
	                  @if($item->status == 1)
	                     <span class="badge bg-inverse-success">Active</span>
	                  @else
	                     <span class="badge bg-inverse-danger">Inactive</span>
	                  @endif
	               </td>
	               <td>
	                  <a href="{{ url('/menu/' . $item->id) }}" title="View Menu"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
	                  <a href="{{ url('/menu/' . $item->id . '/edit') }}" title="Edit Menu"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
	               </td>
	            </tr>
	            @endforeach
	         @endif
         </tbody>
      </table>
	</div>
</div>