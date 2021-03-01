<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // This function used to return every role to their dashboard.
    public function authenticate(Request $request)
    {
        $role = Auth::user()->role->name;
        $role=strtolower($role);
        switch ($role) {
            case 'admin':
                return redirect(route('admin.dashboard'));
                break;
            case 'restaurant':
                return redirect(route('restaurant.dashboard'));
                break;

            case 'support':
                return redirect(route('support.dashboard'));
                break;

            case 'driver':
                return redirect(route('driver.dashboard'));
                break;

            case 'customer':
                return redirect(route('customer.dashboard'));
                break;
            default:
                return '/notfound';
                break;
        }

    }

}
