<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\ItemDetails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Support\Facades\Session;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        Session::put('itemType', 'approved');
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $item = loadUserItemsData(['pending', 'approved', 'rejected']);
            return view('dashboards.restaurant.items.index', compact('item'));
        }

        //Todo: Search should be configured for other roles.
        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $item = Item::wherehas(
                'itemDetails', function ($query) use ($keyword) {
                $query->where('title','LIKE', "%$keyword%")
                    ->orwhere('price','LIKE', "%$keyword%");
            })->orWhere('status', 'LIKE', "%$keyword%")->latest()->paginate($perPage);
        } else {
            $item = Item::latest()->paginate($perPage);
        }

        return view('item.item.index', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $userId = auth()->user()->id;
            $data = $this->dropdown_data(false,$userId);
            return view('dashboards.restaurant.items.create', $data);
        }

        $data = $this->dropdown_data();
        return view('item.item.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'branch_id' => 'required',
            'category_id' => 'required',
			'status' => 'required',
			'title' => 'required',
			'price' => 'required'
		]);
        $requestData = $request->all();
        $role = get_role();

        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'approved';
        if ($role == 'restaurant'){
            $status = 'pending';
        }

        $id = DB::table('items')->insertGetId(
            ['branch_id' => $requestData['branch_id'],
            'category_id' => $requestData['category_id'],
            'status' => $requestData['status']
            ]);

        if ($id) {
            $details_id = DB::table('item_details')->insertGetId(
                ['item_id' => $id,
                'title' => $requestData['title'],
                'description' => $requestData['description'],
                'thumbnail' => save_file($request),
                'price' => $requestData['price'],
                'package_price' => $requestData['package_price'],
                'unit' => $requestData['unit'],
                'details_status' => $status,
                ]);

            if (!$details_id) {
                Item::destroy($id);
            }
        }
        else {
            return redirect('branch')->with('flash_message', 'Sorry there is problem, storing Item data');
        }
        event(new \App\Events\UpdateEvent('Items Updated!'));
        return redirect('item')->with('flash_message', 'Item added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $item = Item::with('itemFullDetails')->findOrFail($id);

        // Prevent other roles from url restriction.
        // the branch user id should equal current user id.
        if (get_role() == "restaurant"){
            $userId = Auth::user()->id;
            $branch = Branch::findOrFail($item->branch_id);
            abortUrlFor(null, $userId, $branch->user_id);
            return view('dashboards.restaurant.items.show', compact('item'));
        }

        return view('item.item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $userId = auth()->user()->id;
            $data = $this->dropdown_data($id, $userId);
            return view('dashboards.restaurant.items.edit', $data);
        }
        $data = $this->dropdown_data($id);

        return view('item.item.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'branch_id' => 'required',
            'category_id' => 'required',
			'status' => 'required',
			'title' => 'required',
			'price' => 'required'
		]);
        $requestData = $request->all();
        $role = get_role();

        $item = Item::findOrFail($id);
        $item->update($requestData);

        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'approved';
        if ($role == 'restaurant'){
            $status = 'pending';
        }

        $update = ['item_id' => $id,
            'title' => $requestData['title'],
            'description' => $requestData['description'],
            'price' => $requestData['price'],
            'package_price' => $requestData['package_price'],
            'unit' => $requestData['unit'],
            'details_status' => $status,
        ];

        // If there was a new image, use it otherwise get old image name.
       if ($request->file('logo')) {
           $update['thumbnail'] = save_file($request);
       } else {
          $update['thumbnail'] =  get_item_details($item, Session::get('itemType'))->thumbnail;
       }

        // Update details.
        $details_id = DB::table('item_details')->insertGetId($update);

        if (!$details_id) {
            return redirect('branch')->with('flash_message', 'Sorry there is problem, updating item data');
        }
        else {
            if (get_role() == "restaurant"){
                $this->changeStatusToOld($id, $details_id, 'pending', true);
            }
            else {
            $this->changeStatusToOld($id, $details_id, null, true);
            }
        }

        return redirect('item')->with('flash_message', 'Item updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Item::destroy($id);

        return redirect('item')->with('flash_message', 'Item deleted!');
    }

     /**
     * Load necessary data for dropdowns.
     *
     * @param  int  $id
     *
     * @return array $data
     */
    public function dropdown_data($id = false, $userId = null) {
        // Pass categories for dropdown list form.
        $data['categories'] = Category::all();

        // Pass branches for dropdown list form.
        if ($userId != null){
            // If User Id passed it will load branches to that specific user.
            $data['branches'] = getUserBranches($userId);
        }
        else {
            $data['branches'] = Branch::all();
        }

        // Pass Item to view. (For Edit form)
        // $item = Item::findOrFail($id);
        $data['item'] = ($id) ? Item::findOrFail($id) : null;

        // Prevent other roles from url restriction.
        // the branch user id should equal current user id.
        if (get_role() == "restaurant" && $id != false){
            $branch = Branch::findOrFail($data['item']->branch_id);
            abortUrlFor(null, $userId, $branch->user_id);
        }
        return $data;
    }

    public function pendingItems()
    {
        Session::put('itemType', 'pending');
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $item = loadUserItemsData(['pending']);
            return view('dashboards.restaurant.items.index', compact('item'));
        }
        $item = $this->getItemsBasedOnStatus('pending');
        return view('item.item.index', compact('item'));
    }

    public function approvedItems()
    {
        Session::put('itemType', 'approved');
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $item = loadUserItemsData(['approved']);
            return view('dashboards.restaurant.items.index', compact('item'));
        }

        $item = $this->getItemsBasedOnStatus('approved');
        return view('item.item.index', compact('item'));
    }

    public function approveItem(Request $request)
    {
        $detialId = $request->item_detail_id;
        $itemId = $request->item_id;
        $item = ItemDetails::findOrFail($detialId);
        $item->details_status = "approved";
        $item->save();
        $this->changeStatusToOld($itemId, $detialId, null, true);

        // Set session, so that it consider this item as approve item, to avoid errors.
        Session::put('itemType', 'approved');
         return redirect()->back()->with('flash_message', 'Item Approved!');
    }

    public function rejectItem(Request $request)
    {
        $detialId = $request->item_detail_id;
        $item = ItemDetails::findOrFail($detialId);
        $item->notes = $request->note;
        $item->details_status = "rejected";
        $item->save();
         return redirect()->back()->with('flash_message', 'Item Rejected!');
    }

    public function getItemsBasedOnStatus($status)
    {
        $item = Item::whereHas(
            'itemFullDetails', function ($query) use ($status) {
            $query->where('details_status', '=', $status);
        })->latest()->paginate(10);
        return $item;
    }

    // This function make the status of other records of same item to old.
    public function changeStatusToOld($item_id, $detailId, $status = null, $run = false)
    {
        if ($run){
            $query = DB::table('item_details')->where('item_id', '=', $item_id);
            $update= $query->where('id', '!=', $detailId);
            if ($status != null){
                $update = $query->where('details_status', '=', $status);
            }
            $update->update(array('details_status' => "old"));
        }
    }
}
