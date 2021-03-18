<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

class CustomerRequests extends Controller
{
    public function submit_new_order(Request $request)
    {

		$validator = Validator::make($request->all(), 
			[ 
				'branch_id' => 'required|integer',
				'customer_id' => 'required|integer',
				'has_delivery' => 'required',
				'total' => 'required|integer',
				'commission_value' => 'required',
				'status' => 'required',
				'reciever_phone' => 'required',  
				'contents' => 'required', 
			]
		);  
 
         if ($validator->fails()) {  
           return response()->json(['error'=>$validator->errors()], 401); 
        }   

    	return "weldone";

        $requestData = $request->all();

        $has_delivery = false;

        if ($requestData['delivery_type'] != 'self') {
            $requestData['has_delivery'] = 1;
            $has_delivery = true;
        }

        try {

            DB::beginTransaction();

            $order->insert($requestData);

            if ($has_delivery) {
                $updateDeliveryDetails = [
                    'delivery_type' => $requestData['delivery_type'],
                    'delivery_adress' => $requestData['delivery_adress'],
                    'driver_id' => NULL,
                ];

                // Update delivery details. First check if there is a record for it, if not then insert a new record.
                $record = DeliveryDetails::where('order_id', $id)->count();
                if ($record) {
                    DeliveryDetails::where('order_id', $id)->update($updateDeliveryDetails);
                }
                else {
                    $updateDeliveryDetails['order_id'] = $id;
                    DB::table('order_delivery')->insertGetId($updateDeliveryDetails);
                }

            }
            DB::commit();
            event(new \App\Events\UpdateEvent('Order Updated!'));
            return redirect('/activeOrders')->with('flash_message', 'Order updated!');


        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash_message', 'Sorry,  update faced a problem!');
        }
    }
}
