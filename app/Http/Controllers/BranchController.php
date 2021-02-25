<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Branch;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\User;

class BranchController extends Controller
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
            $branch = Branch::where('user_id', 'LIKE', "%$keyword%")
                ->orWhere('business_type', 'LIKE', "%$keyword%")
                ->orWhere('main_commission_id', 'LIKE', "%$keyword%")
                ->orWhere('deliver_commission_id', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('title', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->orWhere('logo', 'LIKE', "%$keyword%")
                ->orWhere('contact', 'LIKE', "%$keyword%")
                ->orWhere('location', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $branch = Branch::latest()->paginate($perPage);
        }

        return view('branch.branch.index', compact('branch'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
       
        $data = $this->dropdown_data();
        return view('branch.branch.create', $data);
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
			'user_id' => 'required',
			'business_type' => 'required',
			'main_commission_id' => 'required',
			'status' => 'required',
			'title' => 'required'
		]);
        $requestData = $request->all();
        
        Branch::create($requestData);

        return redirect('branch')->with('flash_message', 'Branch added!');
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
        $branch = Branch::findOrFail($id);

        return view('branch.branch.show', compact('branch'));
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
        return view('branch.branch.edit', $data);
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
			'user_id' => 'required',
			'business_type' => 'required',
			'main_commission_id' => 'required',
			'status' => 'required',
			'title' => 'required'
		]);
        $requestData = $request->all();
        
        $branch = Branch::findOrFail($id);
        $branch->update($requestData);

        return redirect('branch')->with('flash_message', 'Branch updated!');
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
        Branch::destroy($id);

        return redirect('branch')->with('flash_message', 'Branch deleted!');
    }

    public function dropdown_data($id = false) {
        // Pass commissons for dropdown list form.
       $data['commissions'] = Commission::all();

        // Pass Users for dropdown list form.
        $data['users'] = User::all();

        // Pass branches to view. (For Edit form)
        $data['branch'] = ($id) ? Branch::findOrFail($id) : null;
        

        return $data;
    }
}
