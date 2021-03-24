<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()){
            $role = Auth::user()->role->name;
            $role=strtolower($role);
            return redirect($role.'/dashboard');
        }
        return redirect('/home');
    }

    public function markNotificationAsRead(Request $request)
    {
        $notificationid = $request->id;
        if ($notificationid == "all"){
            auth()->user()->notifications()->update(['read_at' => now()]);
            return true;
        }
        $notification = auth()->user()->notifications()->find($notificationid);
        if($notification) {
            $notification->markAsRead();
        }
        return true;
    }
}
