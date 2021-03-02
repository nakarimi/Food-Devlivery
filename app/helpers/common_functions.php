<?php

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
            $path = $request->file('logo')->storeAs('profile_images', $fileNameToStore);

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

