<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $payment = Payment::wherehas(
                'branchDetails',  function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%$keyword%");
            })->orWhere('paid_amount', 'LIKE', "%$keyword%")
                ->orWhere('date_and_time', 'LIKE', "%$keyword%")
                ->orWhere('note', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $payment = Payment::latest()->paginate($perPage);
        }

        return view('payment.payment.index', compact('payment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = $this->dropdown_data();
        // foreach($data['branches'] as $branch) {
        //     print_r($branch);
        // }
        // print_r();die;
        return view('payment.payment.create', $data);
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
			'reciever_id' => 'required',
			'paid_amount' => 'required',
			'date_and_time' => 'required'
		]);
        $requestData = $request->all();

        Payment::create($requestData);

        return redirect('payment')->with('flash_message', 'Payment added!');
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
        $payment = Payment::findOrFail($id);

        return view('payment.payment.show', compact('payment'));
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
        $data['payment'] = Payment::findOrFail($id);
        return view('payment.payment.edit', $data);
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
			'reciever_id' => 'required',
			'paid_amount' => 'required',
			'date_and_time' => 'required'
		]);
        $requestData = $request->all();

        $payment = Payment::findOrFail($id);
        $payment->update($requestData);

        return redirect('payment')->with('flash_message', 'Payment updated!');
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
        Payment::destroy($id);

        return redirect('payment')->with('flash_message', 'Payment deleted!');
    }

    public function dropdown_data($id = false) {

        // Pass Branches for dropdown list form.
        $data['branches'] = DB::table("branches")->select('branche_main_info.title', 'branches.id')->where('branche_main_info.status', 'like', 'approved')->join('branche_main_info', 'branche_main_info.business_id', '=', 'branches.id')->latest('branche_main_info.created_at')->get();

        // Pass Users for dropdown list form.
        $data['users'] = User::all();

        return $data;
    }
}
