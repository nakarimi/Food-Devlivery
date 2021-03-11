<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(array(
            array(
                'id'    => '1',
                'name' => 'admin',
                'label' => 'Admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id'    => '2',
                'name' => 'support',
                'label' => 'Support',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id'    => '3',
                'name' => 'driver',
                'label' => 'Driver Account',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id'    => '4',
                'name' => 'restaurant',
                'label' => 'Restaurant Account',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id'    => '5',
                'name' => 'customer',
                'label' => 'Customer Account',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
        ));

        DB::table('users')->insert([
            array (
                'name'     => 'Admin',
                'email'    => 'admin@pomtech.com',
                'password' => bcrypt('admin'),
                'role_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array (
                'name'     => 'Support',
                'email'    => 'support@pomtech.com',
                'password' => bcrypt('support'),
                'role_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array (
                'name'     => 'Driver',
                'email'    => 'driver@pomtech.com',
                'password' => bcrypt('driver'),
                'role_id' => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array (
                'name'     => 'Fifty-Fifty',
                'email'    => '5050@pomtech.com',
                'password' => bcrypt('5050'),
                'role_id' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array (
                'name'     => 'Herat Super Market',
                'email'    => 'herat@pomtech.com',
                'password' => bcrypt('herat'),
                'role_id' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array (
                'name'     => 'Paik Motor1',
                'email'    => 'paik@pomtech.com',
                'password' => bcrypt('paik'),
                'role_id' => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array (
                'name'     => 'Customer',
                'email'    => 'customer@pomtech.com',
                'password' => bcrypt('customer'),
                'role_id' => 5,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )

        ]);

        DB::table('commissions')->insert([
            array('id' => '1','created_at' => '2021-03-01 04:54:30','updated_at' => '2021-03-01 04:54:30','type' => 'general','value' => NULL,'percentage' => '10','status' => '1','title' => 'General commisssion 10%'),
            array('id' => '2','created_at' => '2021-03-01 04:54:56','updated_at' => '2021-03-01 04:54:56','type' => 'delivery','value' => NULL,'percentage' => '5','status' => '1','title' => 'Delivery 5%')
        ]);

        DB::table('branches')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'user_id' => '4','business_type' => 'food','main_commission_id' => '1','deliver_commission_id' => '2','status' => '1'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'user_id' => '5','business_type' => 'food','main_commission_id' => '1','deliver_commission_id' => NULL,'status' => '1')
        ));

        DB::table('branche_main_info')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'business_id' => '1','title' => 'Fifty-Fifty','description' => 'Eat anything with 50 Afs.','logo' => 'test_1614666474.png','contact' => '+937909090','location' => 'Herat-Chawk Golha','status' => 'approved'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'business_id' => '2','title' => 'Herat Super Market','description' => 'Any you need, we have.','logo' => 'logo_1614666570.png','contact' => '+937303030','location' => 'Herat-Chawk Shahre Now','status' => 'approved')
        ));

        DB::table('items')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'branch_id' => '1','category_id' => '1', 'status' => '1'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => '1','category_id' => '1', 'status' => '1'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'branch_id' => '2','category_id' => '2','status' => '1'),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'branch_id' => '2','category_id' => '2','status' => '1')
          ));

        DB::table('item_details')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'item_id' => '1','title' => 'Pizza','description' => 'پیتزا مخصوص با سوس','thumbnail' => 'noimage.jpg','price' => '50','package_price' => '0','unit' => 'piece','details_status' => 'approved'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'item_id' => '2','title' => 'همبرگر','description' => 'همبرگر با نون اضافی','thumbnail' => 'noimage.jpg','price' => '70','package_price' => '10','unit' => 'piece','details_status' => 'approved'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'item_id' => '3','title' => 'روغن','description' => '1 کیلو','thumbnail' => 'noimage.jpg','price' => '120','package_price' => '0','unit' => 'bottle','details_status' => 'approved'),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'item_id' => '4','title' => 'کچالو','description' => 'کچالوی غزنی','thumbnail' => 'noimage.jpg','price' => '25','package_price' => '0','unit' => 'کیلو','details_status' => 'approved')
          ));

        DB::table('categories')->insert(array(
            array('id' => '1','created_at' => '2021-03-01 10:40:55','updated_at' => '2021-03-01 10:40:55','title' => 'پیتزا','description' => NULL,'thumbnail' => 'noimage.jpg','status' => '1'),
            array('id' => '2','created_at' => '2021-03-01 10:41:06','updated_at' => '2021-03-01 10:41:06','title' => 'عمومی','description' => NULL,'thumbnail' => 'noimage.jpg','status' => '1')
          ));

        DB::table('menus')->insert(array(
            array('id' => '1','created_at' => '2021-03-01 10:44:29','updated_at' => '2021-03-01 10:44:29','title' => 'Menu 1','branch_id' => '1','status' => '1','items' => '["1", "2"]')
        ));

        DB::table('orders')->insert(array(
            array('id' => '1','created_at' => '2021-03-04 10:52:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'First new order','branch_id' => '1','customer_id' => '6','has_delivery' => '1','total' => '200','commission_value' => '20','status' => 'pending','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "4", "price": "100", "item_id": "2"}}, {"item_3": {"count": "8", "price": "200", "item_id": "5"}}]}'),
            array('id' => '2','created_at' => '2021-03-04 11:12:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Second new order','branch_id' => '1','customer_id' => '6','has_delivery' => '0','total' => '200','commission_value' => '20','status' => 'approved','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "4", "price": "100", "item_id": "2"}}, {"item_3": {"count": "8", "price": "200", "item_id": "5"}}]}'),
            array('id' => '3','created_at' => '2021-03-04 2:02:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Third new order','branch_id' => '1','customer_id' => '6','has_delivery' => '1','total' => '200','commission_value' => '20','status' => 'reject','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "4", "price": "100", "item_id": "2"}}, {"item_3": {"count": "8", "price": "200", "item_id": "5"}}]}'),
            array('id' => '4','created_at' => '2021-03-04 10:25:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Fourth new order','branch_id' => '1','customer_id' => '6','has_delivery' => '0','total' => '200','commission_value' => '20','status' => 'processing','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "4", "price": "100", "item_id": "2"}}, {"item_3": {"count": "8", "price": "200", "item_id": "5"}}]}'),
            array('id' => '5','created_at' => '2021-03-04 10:00:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Fifth order','branch_id' => '1','customer_id' => '6','has_delivery' => '0','total' => '200','commission_value' => '20','status' => 'completed','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "4", "price": "100", "item_id": "2"}}, {"item_3": {"count": "8", "price": "200", "item_id": "5"}}]}')
        ));

        DB::table('order_timing')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'order_id' => '1','approved_time' => '2021-03-04 04:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL), 
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'order_id' => '2','approved_time' => '2021-03-04 05:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'order_id' => '3','approved_time' => '2021-03-04 10:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'order_id' => '4','approved_time' => '2021-03-04 11:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '5','created_at' => NULL,'updated_at' => NULL,'order_id' => '5','approved_time' => '2021-03-04 10:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL)            
        ));

        DB::table('order_delivery')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'order_id' => '1','delivery_type' => 'own','delivery_adress' => 'Herat chawk.','driver_id' => NULL,'delivery_commission' => '0'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'order_id' => '3','delivery_type' => 'company','delivery_adress' => 'Herat chawk.','driver_id' => NULL,'delivery_commission' => '0')
            
        ));

        DB::table('drivers')->insert(array(
            array('id' => '1','created_at' => '2021-03-11 05:17:44','updated_at' => '2021-03-11 05:17:44','title' => 'Driver','user_id' => '3','contact' => '45854654','status' => 'free','token' => NULL),
            array('id' => '2','created_at' => '2021-03-11 05:18:32','updated_at' => '2021-03-11 05:18:32','title' => 'Paik Motor1','user_id' => '7','contact' => '45854654','status' => 'free','token' => NULL)          
        ));
        // \App\Models\User::factory(10)->create();
    }
}
