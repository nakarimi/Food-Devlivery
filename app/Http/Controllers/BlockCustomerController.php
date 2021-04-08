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
        $blockCustomer = new BlockCustomer();
        $blockCustomer->customer_id = $request->customer_id;
        $blockCustomer->branch_id = $request->branch_id;
        $blockCustomer->notes = $request->note;
        $blockCustomer->status = 'pending';
        $blockCustomer->save();
        return redirect()->back()->with('flash_message', 'درخواست شما توسط بخش پشتیبانی تعقیب میشود.');

    }

    public function approveLock(BlockCustomer $blockCustomer, $id)
    {
        $blockCustomer = BlockCustomer::findOrFail($id);
        $blockCustomer->status = 'blocked';
        $blockCustomer->save();
        $this->changStatusToCancel($blockCustomer->branch_id, $blockCustomer->customer_id);
        return redirect()->back()->with('flash_message', 'Customer blocked!');

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
        $message = ($customer->status == 'pending') ? 'Customer Block Request Rejected!' : 'Customer Unblocked!'; // Since this function has two usage, so separate messages will be shown.
        $customer->delete();
        return redirect()->back()->with('flash_message', $message);
    }

    public function changStatusToCancel($branch_id, $customer_id)
    {
        $statuses = ['reject','canceld','completed'];
        DB::table('orders')->where('branch_id', '=', $branch_id)
            ->where('customer_id', $customer_id)->whereNotIn('status', $statuses)
            ->update(array('status' => "canceld"));
    }
}
