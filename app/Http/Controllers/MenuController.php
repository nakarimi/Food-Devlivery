<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Branch;

class MenuController extends Controller
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
    public function dropdown_data($id = false) {

        // Pass all available items.
        $data['items'] = Item::where('status', 1)
            ->where('branch_id', 2)
            ->get();

        // Pass branches for dropdown list form.
        $data['branches'] = Branch::all();

        // Pass Users for dropdown list form.
        $data['current_items'] = [];

        // Pass menu to view. (For Edit form)
        $data['menu'] = ($id) ? Menu::findOrFail($id) : null;
        
        return $data;
    }
    // Get itmes for a menu.
    public function get_menu_items($id) {

        $menu = Menu::findOrFail($id);
        $itemIDs = json_decode($menu->items);

        $itmes = Item::whereIn('id', $itemIDs)->get();

        return $itmes;
    }
}
