<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Branch;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        $id = DB::table('branches')->insertGetId(
            ['user_id' => $requestData['user_id'],
            'business_type' => $requestData['business_type'],
            'main_commission_id' => $requestData['main_commission_id'],
            'deliver_commission_id' => $requestData['deliver_commission_id'],
            'status' => $requestData['status']]
        );
        
        // Todo: make upload logo possible and add url to table.
        if ($id) {
            $details_id = DB::table('branche_main_info')->insertGetId(
                ['business_id' => $id,
                'title' => $requestData['title'],
                'description' => $requestData['description'],
                'logo' => $this->save_file($request),
                'contact' => $requestData['contact'],
                'location' => $requestData['location'], 
                'status' => 'approved']
            );

            if (!$details_id) {
                Branch::destroy($id);
            }
        }
        else {
            return redirect('branch')->with('flash_message', 'Sorry there is problem, storing branch data');
        }    
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

        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'pending';
        $update = ['business_id' => $id,
        'title' => $requestData['title'],
        'description' => $requestData['description'],
        'contact' => $requestData['contact'],
        'location' => $requestData['location'], 
        'status' => 'pending'];
        
        // If there was a new image, use it otherwise get old image name.
        if ($request->file('logo')) {
            $update['logo'] = $this->save_file($request);
        } else {
            $update['logo'] =  $branch->branchDetails->logo;
        }
        
        // Update details.
        $details_id = DB::table('branche_main_info')->insertGetId($update);

        if (!$details_id) {
            return redirect('branch')->with('flash_message', 'Sorry there is problem, storing branch data');
        }
   
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

    /**
     * Load necessary data for dropdowns.
     *
     * @param  int  $id
     *
     * @return array $data
     */
    public function dropdown_data($id = false) {
        // Pass commissons for dropdown list form.
       $data['commissions'] = Commission::all();

        // Pass Users for dropdown list form.
        $data['users'] = User::all();

        // Pass branches to view. (For Edit form)
        $data['branch'] = ($id) ? Branch::findOrFail($id) : null;
        

        return $data;
    }

      /**
     * Store the image and return it's address.
     *
     * @param \Illuminate\Http\Response $request
     * request contains the file object.
     *
     * @return a string which is name of the file with extension and address.
     *
     * */
    public function save_file(Request $request) {
        // Handle File Upload
        if($request->file('logo')) {

            // Get filename with extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('logo')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            // Upload Image
            $path = $request->file('logo')->storeAs('profile_images', $fileNameToStore);

        }
        else {
            $fileNameToStore = 'noimage.jpg';
        }

        return $fileNameToStore;
    }
}
