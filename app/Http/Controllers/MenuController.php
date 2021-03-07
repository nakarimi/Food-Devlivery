<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $userId = auth()->user()->id;
            $menu = loadUserMenuData($userId);
            return view('dashboards.restaurant.menu.index', compact('menu'));
        }

        //Todo: Should setup search for other users.
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $menu = Menu::where('title', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('items', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $menu = Menu::latest()->paginate($perPage);
        }

        return view('menu.menu.index', compact('menu'));
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
            return view('dashboards.restaurant.menu.create', $data);
        }

        $data = $this->dropdown_data();
        return view('menu.menu.create', $data);
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
            'title' => 'required',
            'branch_id' => 'required',
            'status' => 'required'
		]);
        $requestData = $request->all();
        $requestData['items'] = json_encode($requestData['items']);

        Menu::create($requestData);

        return redirect('menu')->with('flash_message', 'Menu added!');
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
        $data['menu'] = Menu::findOrFail($id);
        $data['items'] = $this->get_menu_items($id);
        if (get_role() == "restaurant"){
            $userId = Auth::user()->id;
            $branch = Branch::findOrFail($data['menu']->branch_id);
            if ($branch->user_id != $userId){
                abort(404);
            }
            return view('dashboards.restaurant.menu.show', $data);
        }

        return view('menu.menu.show', $data);
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
            return view('dashboards.restaurant.menu.edit', $data);
        }

        $data = $this->dropdown_data($id);
        return view('menu.menu.edit', $data);
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
            'title' => 'required',
            'branch_id' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        $requestData['items'] = json_encode($requestData['items']);

        $menu = Menu::findOrFail($id);
        $menu->update($requestData);

        return redirect('menu')->with('flash_message', 'Menu updated!');
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
        Menu::destroy($id);

        return redirect('menu')->with('flash_message', 'Menu deleted!');
    }

    /**
     * Load necessary data for dropdowns.
     *
     * @param  int  $id
     *
     * @return array $data
     */
    public function dropdown_data($id = false, $userId = null) {

        // Pass all available items.
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $data['items'] = loadUserItemsData(['approved']);
        }
        else {
            $data['items'] = Item::where('status', 1)->get();
        }

        // Pass branches for dropdown list form.
        if ($userId != null){
            // If User Id passed it will load branches to that specific user.
            $data['branches'] = getUserBranches($userId);
        }
        else {
            $data['branches'] = Branch::all();
        }

        // Pass Users for dropdown list form.
        $data['current_items'] = [];

        // Pass menu to view. (For Edit form)
        $data['menu'] = ($id) ? Menu::findOrFail($id) : null;

        // Prevent other roles from url restriction.
        // the branch user id should equal current user id.
        if (get_role() == "restaurant"){
            $branch = Branch::findOrFail($data['menu']->branch_id);
            if ($branch->user_id != $userId){
                abort(404);
            }
        }

        return $data;
    }
    // Get itmes for a menu.
    public function get_menu_items($id) {

        $menu = Menu::findOrFail($id);
        $itemIDs = json_decode($menu->items);

        $itmes = Item::whereIn('id', $itemIDs)->get();

        return $itmes;
    }

    public function loadItemsBasedOnBranch()
    {
        $branchId = $_GET['branchId'];
        $itemsArray = [];
        if ($branchId != null){
            $branch = Branch::findorfail($branchId);
            $items = Item::whereHas(
                'itemFullDetails', function ($query) {
                $query->where('details_status', 'approved');
            })->where('branch_id',$branchId)->get();

            foreach ($items as $item){
                $itemsArray[$item->itemDetails->id] = $item->itemDetails->title;
            }
        }
        return $itemsArray;
    }
}
