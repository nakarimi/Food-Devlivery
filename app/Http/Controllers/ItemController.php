<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $item = Item::where('branch_id', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
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
			'status' => 'required',
			'title' => 'required',
			'price' => 'required'
		]);
        $requestData = $request->all();

        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'pending';
        
        $id = DB::table('items')->insertGetId(
            ['branch_id' => $requestData['branch_id'],
            'status' => $requestData['status']
            ]);
        
        if ($id) {
            $details_id = DB::table('item_details')->insertGetId(
                ['item_id' => $id,
                'title' => $requestData['title'],
                'description' => $requestData['description'],
                'code' => $requestData['code'],
                'thumbnail' => save_file($request, 'thumbnail'),
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
        $item = Item::findOrFail($id);

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
			'status' => 'required',
			'title' => 'required',
			'price' => 'required'
		]);
        $requestData = $request->all();
        
        $item = Item::findOrFail($id);
        $item->update($requestData);

        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'pending';
        
        $update = ['item_id' => $id,
            'title' => $requestData['title'],
            'description' => $requestData['description'],
            'code' => $requestData['code'],
            'price' => $requestData['price'], 
            'package_price' => $requestData['package_price'],
            'unit' => $requestData['unit'],
            'details_status' => $status,
        ];

        // If there was a new image, use it otherwise get old image name.
        if ($request->file('logo')) {
            $update['thumbnail'] = save_file($request);
        } else {
            $update['thumbnail'] =  $item->itemDetails->thumbnail;
        }
        
        // Update details.
        $details_id = DB::table('item_details')->insertGetId($update);

        if (!$details_id) {
            return redirect('branch')->with('flash_message', 'Sorry there is problem, updating item data');
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
    public function dropdown_data($id = false) {
        // Pass commissons for dropdown list form.
    //    $data['commissions'] = Commission::all();

        // Pass Users for dropdown list form.
        $data['users'] = User::all();

        // Pass Item to view. (For Edit form)
        // $item = Item::findOrFail($id);
        $data['item'] = ($id) ? Item::findOrFail($id) : null;

        return $data;
    }
}
