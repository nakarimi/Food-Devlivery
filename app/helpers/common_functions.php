<?php

use App\Models\Branch;
use App\Models\Item;
use \App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


if (!function_exists('save_file')) {
     /**
     * Store the image and return it's address.
     *
     * @param \Illuminate\Http\Response $request
     * request contains the file object.
     *
     * @return a string which is name of the file with extension and address.
     *
     * */
    function save_file(Request $request) {
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
            $path = $request->file('logo')->storeAs('/public/profile_images', $fileNameToStore);

        }
        else {
            $fileNameToStore = 'noimage.jpg';
        }

        return $fileNameToStore;
    }
}

if (!function_exists('get_role')) {
     /**
     * Return user role name.
     * */
    function get_role() {
        // Handle File Upload
       $role =  auth()->user()->role->name;
        return $role;
    }
}

if (!function_exists('get_item_details')) {
    /**
     * Return an item latest details.
     * */
    function get_item_details($item, $pending = false) {
        return ($pending) ? $item->pendingItemDetails : $item->approvedItemDetails;
    }
}

// This function loads all items of the current user based on status.
// Status can be an array so it will return multiple items of user.
if (!function_exists('loadUserItemsData')) {
    function loadUserItemsData($status, $userId = null)
    {
        if ($userId == null){
            $userId = auth()->user()->id;
        }
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }

        // Get user Items.
        $items =  getUserItemsBasedOnStatus($branchIds, $status);
        return $items;
    }
}

// This will return user branches based on user id.
if (!function_exists('getUserBranches')){

    function getUserBranches($id)
    {
        $branches = Branch::where('user_id', $id)->get();
        return $branches;
    }
}

// This will return items based on status one or multiple status.
if (!function_exists('getUserItemsBasedOnStatus')){

    function getUserItemsBasedOnStatus ($branchIds, $status){
        $item = Item::whereHas(
            'itemFullDetails', function ($query) use ($status) {
            $query->whereIn('details_status', $status);
        })->whereIn('branch_id',$branchIds)->get();
        return $item;
    }
}

// This function get specific  user branches and returns user menus.
if (!function_exists('loadUserMenuData')){
    function loadUserMenuData($userId){
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }

        // Get user Items.
        $menu =  getUserMenus($branchIds);
        return $menu;
    }
}

// This will return menus based on branch ids.
if (!function_exists('getUserMenus')){
    function getUserMenus ($branchIds){
        $menu = Menu::whereIn('branch_id',$branchIds)->latest()->paginate(10);
        return $menu;
    }
}


// This will return menus based on branch ids.
if (!function_exists('getBranchesBasedOnStatus')){
    function getBranchesBasedOnStatus ($status){
        $branches = Branch::whereHas(
            'branchFullDetails', function ($query) use ($status) {
            $query->where('status', '=', $status);
        })->latest()->paginate(10);
        return $branches;
    }
}

// This function abort the process for the role we pass.
if (!function_exists('abortUrlFor')){
    function abortUrlFor ($role = null, $userId = null, $branchId = null){
        if ($role != null){
            if (get_role() == $role){
                abort(404);
            }
        }
        elseif($userId != null && $branchId != null){
            if ($userId != $branchId){
                abort(404);
            }
        }
    }
}




