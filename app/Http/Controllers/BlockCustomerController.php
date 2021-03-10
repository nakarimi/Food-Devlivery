<?php

namespace App\Http\Controllers;

use App\Models\BlockCustomer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlockCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blockedCustomers = BlockCustomer::latest()->paginate(10);
        return view('customer.BlockedCustomers.index', compact('blockedCustomers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $order_id = $request->order_id;
        $blockCustomer = new BlockCustomer();
        $blockCustomer->user_id = $userId;
        $blockCustomer->customer_id = $request->customer_id;
        $blockCustomer->branch_id = $request->branch_id;
        $blockCustomer->notes = $request->note;
        $blockCustomer->save();
        $this->changStatusToCancel($request->branch_id, $request->customer_id);
        return redirect()->back()->with('flash_message', 'Customer Successfully Blocked!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlockCustomer  $blockCustomer
     * @return \Illuminate\Http\Response
     */
    public function show(BlockCustomer $blockCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlockCustomer  $blockCustomer
     * @return \Illuminate\Http\Response
     */
    public function edit(BlockCustomer $blockCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlockCustomer  $blockCustomer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlockCustomer $blockCustomer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlockCustomer  $blockCustomer
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlockCustomer $blockCustomer, $id)
    {
        $customer = BlockCustomer::findOrFail($id);
        $customer->delete();
        return redirect()->back()->with('flash_message', 'Customer Unblocked!');
    }

    public function changStatusToCancel($branch_id, $customer_id)
    {
        $statuses = ['reject','canceld','completed'];
        DB::table('orders')->where('branch_id', '=', $branch_id)
            ->where('customer_id', $customer_id)->whereNotIn('status', $statuses)
            ->update(array('status' => "canceld"));
    }
}
