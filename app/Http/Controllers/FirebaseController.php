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
    public function varify_user($uid)
    {
        // Check the user validation by uid.
        $path = base_path('config/fooddelivery-firebase.json');
        $factory = (new Factory)
            ->withServiceAccount($path)
            ->withDatabaseUri('https://fooddelivery-cc39b-default-rtdb.firebaseio.com');
        $auth = $factory->createAuth();
        $user = $auth->getUser($uid);
        return $user;
    }
}
