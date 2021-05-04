<?php

namespace App\Http\Controllers;

use Facade\FlareClient\Http\Response;
use Kreait\Firebase;
use Lcobucci\JWT\Parser;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

class FirebaseController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }
    /**
     * z
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check the user validation by uid.
        try {
            $path = base_path('config/fooddelivery-firebase.json');
            $factory = (new Factory)
                ->withServiceAccount($path)
                ->withDatabaseUri('https://fooddelivery-cc39b-default-rtdb.firebaseio.com');
    
            $auth = $factory->createAuth();
            $user = $auth->getUser($_GET['uid']);
            return $user;
        } catch (\Throwable $th) {
            return Response($th, 404);
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
