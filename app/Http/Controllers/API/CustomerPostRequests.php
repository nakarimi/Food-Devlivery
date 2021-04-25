<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                    'delivery_adress' => $requestData['delivery_adress'],
                    'driver_id' => NULL,
                ];

                // Insert delivery details.
                DB::table('order_delivery')->insertGetId($updateDeliveryDetails);

                event(new \App\Events\UpdateEvent('New Order Recieved!', $order_id));
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
            return -1;
        }
    }

    public function update_order(Request $request)
    {
        validateOrderInputs($request);
        $requestData = $request->all();
        update_order($requestData, $requestData['order_id'], true);
    }

    public function customer_signup(Request $request)
    {
        try {

            // Since we deal with multiple tables, so we use transactions for handling conflicts and other issues.
            DB::beginTransaction();

            $customerDetails = $request;
            unset($customerDetails['token']); // To prevent: 1054 Unknown column 'token' in 'field list'.
            $customer_id = DB::table('customers')->insertGetId($customerDetails->toArray());

            event(new \App\Events\UpdateEvent('New Customer Signed Up!', $customer_id));

            DB::commit();
            return $customer_id;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return -1;
        }
    }
    public function customer_shipping_address(Request $request)
    {
        return $request;
    }
}
