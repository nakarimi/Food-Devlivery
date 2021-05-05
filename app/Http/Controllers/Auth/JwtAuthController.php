<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
 
class JwtAuthController extends Controller
{
    public $token = true;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'customer_signup', 'customer_signin']]);
    }
    
  
    // public function register(Request $request)
    // {
 
    //      $validator = Validator::make($request->all(), 
    //                   [ 
    //                   'name' => 'required',
    //                   'email' => 'required|email',
    //                   'password' => 'required',  
    //                   'c_password' => 'required|same:password', 
    //                  ]);  
 
    //      if ($validator->fails()) {  
 
    //            return response()->json(['error'=>$validator->errors()], 401); 
 
    //         }   
 
    //     $user = new User();
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->password = bcrypt($request->password);
    //     $user->save();
  
    //     if ($this->token) {
    //         return $this->login($request);
    //     }
  
    //     return response()->json([
    //         'success' => true,
    //         'data' => $user
    //     ], Response::HTTP_OK);
    // }

    public function customer_signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|max:191',
            'token' => 'required|unique:users,firebase_token',
            'phone' => 'required|unique:users|max:10|min:10',  // 0761234567
            'address_title' => 'required|min:8',
        ]);  
 
         if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }


        try {

            // Here we need to check if provided firebase_token is valid using firebase.
            app('App\Http\Controllers\FirebaseController')->varify_user($request->token);
            
            // Since we deal with multiple tables, so we use transactions for handling conflicts and other issues.
            DB::beginTransaction();

            $user = new User();
            $user->name = $request->full_name;
            $user->firebase_token = $request->token;
            $user->phone = $request->phone;
            $user->email = $request->phone . '@customer.com';   // A fake email for customers. 
            $user->password = bcrypt($user->email);
            $user->role_id = 5;  // 5 is customer role.
            $user->save();
            
            $customerAddressDetails = [
                'customer_id' => $user->id,
                'address_title' => $request->address_title,
                'address_type' => $request->address_type,
                'address_details' => $request->address_details,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];

            DB::table('customer_addresses')->insertGetId($customerAddressDetails);

            DB::commit();
    
            if ($this->token) {
                return $this->customer_signin($request);
            }
    
            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);

         
        } catch (\Exception $e) {
            DB::rollback();
            return [$validator->errors()->all(), $e->getMessage()];
        }
        
    }
  
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;
  
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // If customer logs in, so avoid since customer_signin is there for customer.
    	if (JWTAuth::user()->role_id == 5) {
    		return response()->json([
                'success' => false,
                'message' => 'Wrong End Point!',
        	]);
    	}
  
        $user_data = JWTAuth::user()->only('email', 'name');
        
        return response()->json([
            'success' => true,
            'user_info' => $user_data,
            'token' => $jwt_token,
        ]);
    }

    public function customer_signin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);  
 
         if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }


        $user = User::where('firebase_token', '=', $request->token)->first();
        $jwt_token = null;
        
        
        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not exist in the system!',
            ]);
        }
        else {
            if (!$jwt_token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'success' => true,
                'token' => $jwt_token,
            ]);
        }
    }
  
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
  
        try {
            JWTAuth::invalidate($request->token);
  
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'success_token_referesh' => true,
            'token' => auth()->refresh(),
        ]);
    }
    
}