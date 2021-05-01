<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerPostRequests extends Controller
{
    public function submit_new_order(Request $request)
    {
        validateOrderInputs($request);
        $requestData = $request->all();

        // Don't accept order from blocked customers.
        if (get_customer_status($requestData['customer_id']) == 'blocked') {
            return 'blocked customer';
        }

        $has_delivery = ($requestData['delivery_type'] != 'self') ? true : false;

        try {

            // Since we deal with multiple tables, so we use transactions for handling conflicts and other issues.
            DB::beginTransaction();

            $newOrder = [
                'branch_id' => $requestData['branch_id'],
                'customer_id' => $requestData['customer_id'],
                'has_delivery' => 1, //($has_delivery) ? '1' : '0', for now delivery is required.
                'total' => $requestData['total'],
                'commission_value' => $requestData['commission_value'],
                'status' => 'pending',
                'note' => $requestData['note'],
                'reciever_phone' => $requestData['reciever_phone'],
                'contents' => $requestData['contents'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            // This loop here is for speed test issues and will be removed.
            for ($k = 0; $k < 1; $k++) {

                // $newOrder = [
                //     'branch_id' => $requestData['branch_id'],
                //     'customer_id' => $requestData['customer_id'],
                //     'has_delivery' => ($has_delivery) ? '1' : '0',
                //     'total' => $requestData['total'],
                //     'commission_value' => $requestData['commission_value'],
                //     'status' => 'pending',
                //     'note' => $requestData['note'],
                //     'reciever_phone' => $requestData['reciever_phone'],
                //     'contents' => $requestData['contents'],
                //     'created_at' => Carbon::today()->subDays(rand(14, 24)),  // Carbon::now()->format('Y-m-d H:i:s')
                // ];

                // Add order to table.
                $order_id = DB::table('orders')->insertGetId($newOrder);
                $updateDeliveryDetails = [
                    'order_id' => $order_id,
                    'delivery_type' => $requestData['delivery_type'],
                    'delivery_address' => $requestData['address_id'],
                    'driver_id' => NULL,
                ];
                // Insert delivery details.
                DB::table('order_delivery')->insertGetId($updateDeliveryDetails);

                event(new \App\Events\UpdateEvent('New Order Received!', $order_id));
                // if (($k % 5) == 0) {
                // 	sleep(0.25);
                // }
            }

            DB::commit();
            $notifyUser = Branch::find($requestData['branch_id'])->user_id;
            send_notification([$notifyUser], 1, 'سفارش جدید ااضافه شد');
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return -1;
        }
    }

    public function update_order(Request $request)
    {
        validateOrderInputs($request);
        $requestData = $request->all();
        update_order($requestData, $requestData['order_id'], true);
    }

    // Verify the customer phone number, if exist should be redirected to the dashboard page.
    // If not exist should be redirected to the sign up page.
    public function customer_verify_phone(Request $request){
        $customer = DB::table('customers')->where('phone', $request->phone)->first();
        if($customer) {
            return 1; // Means customer with phone number exist, redirect to dashboard.
        }else{
            return 0; // Means customer not exist, should be registerd.
        }

    }
    
    // public function customer_signup(Request $request)
    // {
    //     try {
    //         // Check that customer id exist on users and not exist on customers table.
    //         $validator = Validator::make($request->all(), [
    //             'full_name' => 'required|max:191',
    //             'phone' => 'unique:users|max:10|min:10', // 0761234567
    //             'address_title' => 'min:10',
    //             'address_type' => 'min:10',
    //         ]);

    //         // Since we deal with multiple tables, so we use transactions for handling conflicts and other issues.
    //         DB::beginTransaction();
                        
    //         $user = new User();
    //         $user->name = $request->full_name;
    //         $user->phone = $request->phone;
    //         $user->email = $request->phone . '@customer.com';   // A fake email for customers.
    //         $user->password = bcrypt($user->email);
    //         $user->save();
            
    //         $customerAddressDetails = [
    //             'customer_id' => $user->id,
    //             'address_title' => $request->address_title,
    //             'address_type' => $request->address_type,
    //             'address_details' => $request->address_details,
    //             'latitude' => $request->latitude,
    //             'longitude' => $request->longitude,
    //         ];

    //         DB::table('customers')->insertGetId($customerAddressDetails);

    //         DB::commit();
            
    //         return response()->json([
    //             'success' => true,
    //             'data' => $user
    //         ], Response::HTTP_OK);

    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return [$validator->errors()->all(), $e->getMessage()];
    //     }
    // }
    // public function customer_shipping_address(Request $request)
    // {
    //     try {
    //         // Check that customer id exist on users table.
    //         $validator = Validator::make($request->all(), [
    //             'customer_id' => 'integer|exists:users,id', 
    //             'address' => 'max:250',
    //         ]);

    //         DB::beginTransaction();
    //         $customerAddress = $request;
    //         unset($customerAddress['token']); // To prevent: 1054 Unknown column 'token' in 'field list'.
    //         $address_id = DB::table('customer_addresses')->insertGetId($customerAddress->toArray());

    //         DB::commit();
    //         return $address_id;
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return [$validator->errors()->all(), $e->getMessage()];
    //     }

    // }
}
