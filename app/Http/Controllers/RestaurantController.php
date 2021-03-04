<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{


    public function restaurantProfile()
    {
        $userId = Auth::user()->id;
        $branch = Branch::where('user_id', $userId)->with('branchDetails')->latest()->first();
        return view('dashboards.restaurant.profile.profile', compact('branch'));
    }

}
