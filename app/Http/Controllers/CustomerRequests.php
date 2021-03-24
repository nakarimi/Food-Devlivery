<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerRequests extends Controller
{
    public function submit_new_order(Request $request)
    {
        validateOrderInputs($request);	
        $requestData = $request->all();
        $has_delivery = ($requestData['delivery_type'] != 'self') ? true : false;

        try {

            // Since we deal with multiple tables, so we use transactions for handling conflicts and other issues.
            DB::beginTransaction();

            $newOrder = [
                'branch_id' => $requestData['branch_id'],
                'customer_id' => $requestData['customer_id'],
                'has_delivery' => ($has_delivery) ? '1' : '0',
                'total' => $requestData['total'],
                'commission_value' => $requestData['commission_value'],
                'status' => 'pending',
                'note' => $requestData['note'],
                'reciever_phone' => $requestData['reciever_phone'],
                'contents' => $requestData['contents'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
    
            // Add order to table.
            $order_id = DB::table('orders')->insertGetId($newOrder);
    
            if ($has_delivery) {
                $updateDeliveryDetails = [
                    'order_id' => $order_id,
                    'delivery_type' => $requestData['delivery_type'],
                    'delivery_adress' => $requestData['delivery_adress'],
                    'driver_id' => NULL,
                ];
                // Insert delivery details.
                DB::table('order_delivery')->insertGetId($updateDeliveryDetails);
            }
            DB::commit();
            // event(new \App\Events\UpdateEvent('Order Updated!'));
            return 1;


        } catch (\Exception $e) {
            DB::rollback();
            return -1;
        }
    }

    public function update_order(Request $request) {
        validateOrderInputs($request);
        $requestData = $request->all();
        update_order($requestData, $requestData['order_id'], true);   
    }
}
