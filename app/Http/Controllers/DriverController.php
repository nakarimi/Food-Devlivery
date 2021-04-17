<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class DriverController extends Controller
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
            $driver = Driver::where('title', 'LIKE', "%$keyword%")
                ->orWhere('contact', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('token', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $driver = Driver::latest()->paginate($perPage);
        }

        return view('driver.driver.index', compact('driver'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = $this->dropdown_data();
        return view('driver.driver.create', $data);
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
            'user_id' => 'required',
            'status' => 'required'
        ]);
        $requestData = $request->all();

        Driver::create($requestData);

        return redirect('driver')->with('flash_message', 'Driver added!');
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
        $driver = Driver::findOrFail($id);

        return view('driver.driver.show', compact('driver'));
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

        return view('driver.driver.edit', $data);
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
            'user_id' => 'required',
            'status' => 'required'
        ]);
        $requestData = $request->all();

        $driver = Driver::findOrFail($id);
        $driver->update($requestData);

        return redirect('driver')->with('flash_message', 'Driver updated!');
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
        Driver::where('id', $id)->update(['status' => 'inactive']);
        return redirect('driver')->with('flash_message', 'Driver Inactivated!');
    }

    public function dropdown_data($id = false)
    {

        // Pass Users for dropdown list form.
        $data['users'] = User::all();

        // Pass drivers to view. (For Edit form)
        $data['driver'] = ($id) ? Driver::findOrFail($id) : [];

        return $data;
    }
    public function driverPaymentRecived(Request $request)
    {
        DB::table('recieved_driver_payments')->insert([
            'orders_id' => $request->orders,
            'total_money_received' => $request->total,
            'driver_id' => $request->driver,
            'finance_manager_id' => $request->user()->id,
        ]);
        return Redirect::back()->with('flash_message', 'Payment received!');
    }
}
