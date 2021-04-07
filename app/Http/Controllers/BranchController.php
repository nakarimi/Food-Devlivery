<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Branch;
use App\Models\BranchDetails;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        abortUrlFor("restaurant");
        Session::put('branchType', 'approved');
        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $branch = Branch::wherehas(
                'branchDetails' , function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%$keyword%");
            })
                // ->orWhere('business_type', 'LIKE', "%$keyword%")
                // ->orWhere('main_commission_id', 'LIKE', "%$keyword%")
                // ->orWhere('deliver_commission_id', 'LIKE', "%$keyword%")
                // ->orWhere('status', 'LIKE', "%$keyword%")
                // ->orWhere('title', 'LIKE', "%$keyword%")
                // ->orWhere('description', 'LIKE', "%$keyword%")
                // ->orWhere('logo', 'LIKE', "%$keyword%")
                // ->orWhere('contact', 'LIKE', "%$keyword%")
                // ->orWhere('location', 'LIKE', "%$keyword%")
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
        abortUrlFor("restaurant");
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

        if ($id) {
            $details_id = DB::table('branche_main_info')->insertGetId(
                ['business_id' => $id,
                'title' => $requestData['title'],
                'description' => $requestData['description'],
                'logo' => save_file($request),
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
        abortUrlFor("restaurant");
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
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $userId = auth()->user()->id;
            // Pass branches to view. (For Edit form for restaurant with limited data)
            $data['branch'] = Branch::where('id', $id)->where('user_id', $userId)->with('branchDetails')->latest()->first();
            return view('dashboards.restaurant.profile.editBranch', $data);
        }

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
        $role = get_role();
        $message = 'Branch updated!';

        $requestArray = [
            'location' => 'required',
            'title' => 'required',
            'contact' => 'required'
        ];

        if ($role != "restaurant"){
            $requestArray = [
                'user_id' => 'required',
                'business_type' => 'required',
                'main_commission_id' => 'required',
                'status' => 'required',
                'title' => 'required'
            ];
        }
        $this->validate($request, $requestArray);
        $requestData = $request->all();

        $branch = Branch::findOrFail($id);
        $branch->update($requestData);

        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'approved';
        
        $returnUrl = 'branch';
        if ($role == 'restaurant'){
            $message = 'اطلاعت شما تغیر داده شد، لطفا تا تائید بخش پشتیبانی منتظر بمانید.';
            $status = 'pending';
            $returnUrl = '/profile';
        }
        $update = ['business_id' => $id,
        'title' => $requestData['title'],
        'description' => $requestData['description'],
        'contact' => $requestData['contact'],
        'location' => $requestData['location'],
        'status' => $status];

        // If there was a new image, use it otherwise get old image name.
        if ($request->file('logo')) {
            $update['logo'] = save_file($request);
        } else {
            $update['logo'] =  $branch->branchDetails->logo;
        }

        // Update details.
        $details_id = DB::table('branche_main_info')->insertGetId($update);

        if (!$details_id) {
            return redirect($returnUrl)->with('flash_message', 'Sorry there is problem, storing branch data');
        }
        
        if (get_role() == "restaurant"){
            $userId = \auth()->user()->id;
            send_notification([1], $userId, 'Updated its Branch Details');
            $this->changeStatusToOld($branch->id, $details_id, 'pending', true);
        }
        else {
            $this->changeStatusToOld($branch->id, $details_id, null, true);
        }

        return redirect($returnUrl)->with('flash_message', $message);
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

    public function pendingBranches()
    {
        Session::put('branchType', 'pending');
        $branch = getBranchesBasedOnStatus("pending");
        return view('branch.branch.index', compact('branch'));
    }

    public function rejectedBranches()
    {
        Session::put('branchType', 'rejected');
        $branch = getBranchesBasedOnStatus("rejected");
        return view('branch.branch.index', compact('branch'));
    }

    public function approvedBranches()
    {
        Session::put('branchType', 'approved');
        $branch = getBranchesBasedOnStatus("approved");
        return view('branch.branch.index', compact('branch'));
    }


    public function restaurantProfile()
    {
        $userId = Auth::user()->id;
        $branch = Branch::where('user_id', $userId)->with('branchDetails')->latest()->first();
        return view('dashboards.restaurant.profile.profile', compact('branch'));
    }

    public function approveBranch(Request $request)
    {
        $detialId = $request->branch_detail_id;
        $businessId = $request->branch_id;
        $branch = BranchDetails::findOrFail($detialId);
        $branch->status = "approved";
        $branch->save();
        $this->changeStatusToOld($businessId, $detialId, null, true);
        $notifyUser = Branch::find($businessId)->user_id;
        send_notification([$notifyUser], 1, 'تغیرات روی پروفایل تان توسط ادمین قبول شد');
        return redirect()->back()->with('flash_message', 'Branch Approved!');
    }

    public function rejectBranch(Request $request)
    {

        $detialId = $request->detail_id;
        $reason = $request->reason; 
        $branch = BranchDetails::findOrFail($detialId);
        $branch->status = "rejected";
        $branch->note = $reason;
        $branch->save();
        $notifyUser = Branch::find($branch->business_id)->user_id;
        send_notification([$notifyUser], 1, 'تغیرات روی پروفایل تان توسط ادمین رد شد');
        return redirect()->back()->with('flash_message', 'Branch Rejected!');
    }

    // This function make the status of other records of same branch to old.
    public function changeStatusToOld($business_id, $detailId, $status = null, $run = false)
    {
        if ($run){
            $query = DB::table('branche_main_info')->where('business_id', '=', $business_id);
            $update = $query->where('id', '!=', $detailId);
            if ($status != null){
                $update = $query->where('status', '=', $status);
            }
               $update->update(array('status' => "old"));
        }
    }
}
