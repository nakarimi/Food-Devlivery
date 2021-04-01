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
                'name' => 'Jalil Ahmad Karimi',
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
            ), 
            array (
                'name'     => 'Ghulam Bargar',
                'email'    => 'g.long@bargar.com',
                'password' => bcrypt('asdfasdf'),
                'role_id' => 4,
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
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'user_id' => '5','business_type' => 'food','main_commission_id' => '1','deliver_commission_id' => NULL,'status' => '1'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'user_id' => '8','business_type' => 'food','main_commission_id' => '1','deliver_commission_id' => NULL,'status' => '1')
        ));

        DB::table('branche_main_info')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'business_id' => '1','title' => 'Fifty-Fifty','description' => 'Eat anything with 50 Afs.','logo' => 'test_1614666474.png','contact' => '+937909090','location' => 'Herat-Chawk Golha','status' => 'approved'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'business_id' => '2','title' => 'Herat Super Market','description' => 'Any you need, we have.','logo' => 'logo_1614666570.png','contact' => '+937303030','location' => 'Herat-Chawk Shahre Now','status' => 'old'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'business_id' => '3','title' => 'Ghulam Bargar','description' => 'Any you need, we have.','logo' => 'logo_1614666570.png','contact' => '+937303030','location' => 'Herat-Chawk Shahre Now','status' => 'approved'),
            array('id' => '4','created_at' => NULL,'updated_at' => '2021-03-24 16:05:20','business_id' => '1','title' => 'Fifty-Fifty','description' => 'Eat anything with 50 Afs. and 100','logo' => 'test_1614666474.png','contact' => '+937909090','location' => 'Herat-Chawk Golha','status' => 'rejected'),
            array('id' => '5','created_at' => NULL,'updated_at' => '2021-03-25 12:15:55','business_id' => '2','title' => 'Herat Super Market','description' => 'Any you need, we have.','logo' => 'grocery-store_1616657916.jpg','contact' => '+937303030','location' => 'Herat-Chawk Shahre Now','status' => 'approved')
          ));

        DB::table('items')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'branch_id' => '1','category_id' => '1','status' => '1'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => '1','category_id' => '1','status' => '1'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'branch_id' => '2','category_id' => '2','status' => '1'),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'branch_id' => '2','category_id' => '2','status' => '1'),
            array('id' => '5','created_at' => NULL,'updated_at' => NULL,'branch_id' => '3','category_id' => '2','status' => '1'),
            array('id' => '6','created_at' => NULL,'updated_at' => NULL,'branch_id' => '3','category_id' => '2','status' => '1'),
            array('id' => '7','created_at' => NULL,'updated_at' => NULL,'branch_id' => '1','category_id' => '1','status' => '1'),
            array('id' => '8','created_at' => NULL,'updated_at' => NULL,'branch_id' => '1','category_id' => '1','status' => '1'),
            array('id' => '9','created_at' => NULL,'updated_at' => NULL,'branch_id' => '2','category_id' => '1','status' => '1'),
            array('id' => '10','created_at' => NULL,'updated_at' => NULL,'branch_id' => '2','category_id' => '1','status' => '1'),
            array('id' => '11','created_at' => NULL,'updated_at' => NULL,'branch_id' => '2','category_id' => '1','status' => '1')

          ));

        DB::table('item_details')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'item_id' => '1','title' => 'Pizza','description' => 'پیتزا مخصوص با سوس','thumbnail' => 'noimage.jpg','price' => '50','package_price' => '0','unit' => 'piece','notes' => NULL,'details_status' => 'old'),
  array('id' => '2','created_at' => NULL,'updated_at' => NULL,'item_id' => '2','title' => 'همبرگر','description' => 'همبرگر با نون اضافی','thumbnail' => 'noimage.jpg','price' => '70','package_price' => '10','unit' => 'piece','notes' => NULL,'details_status' => 'old'),
  array('id' => '3','created_at' => NULL,'updated_at' => NULL,'item_id' => '3','title' => 'روغن','description' => '1 کیلو','thumbnail' => 'noimage.jpg','price' => '120','package_price' => '0','unit' => 'bottle','notes' => NULL,'details_status' => 'old'),
  array('id' => '4','created_at' => NULL,'updated_at' => NULL,'item_id' => '4','title' => 'کچالو','description' => 'کچالوی غزنی','thumbnail' => 'noimage.jpg','price' => '25','package_price' => '0','unit' => 'کیلو','notes' => NULL,'details_status' => 'old'),
  array('id' => '5','created_at' => NULL,'updated_at' => NULL,'item_id' => '5','title' => 'شوربا','description' => 'کچالوی غزنی','thumbnail' => 'noimage.jpg','price' => '25','package_price' => '0','unit' => 'کیلو','notes' => NULL,'details_status' => 'old'),
  array('id' => '6','created_at' => NULL,'updated_at' => NULL,'item_id' => '6','title' => 'دیزی','description' => 'کچالوی غزنی','thumbnail' => 'noimage.jpg','price' => '25','package_price' => '0','unit' => 'کیلو','notes' => NULL,'details_status' => 'old'),
  array('id' => '7','created_at' => NULL,'updated_at' => '2021-03-24 15:47:18','item_id' => '1','title' => 'Pizza 2','description' => 'پیتزا مخصوص با سوس','thumbnail' => 'noimage.jpg','price' => '50','package_price' => '0','unit' => 'piece','notes' => NULL,'details_status' => 'old'),
  array('id' => '8','created_at' => NULL,'updated_at' => NULL,'item_id' => '7','title' => 'چای','description' => NULL,'thumbnail' => 'noimage.jpg','price' => '120','package_price' => NULL,'unit' => NULL,'notes' => NULL,'details_status' => 'old'),
  array('id' => '9','created_at' => NULL,'updated_at' => '2021-03-24 16:05:07','item_id' => '7','title' => 'چای ۱','description' => NULL,'thumbnail' => 'noimage.jpg','price' => '120','package_price' => NULL,'unit' => NULL,'notes' => NULL,'details_status' => 'old'),
  array('id' => '10','created_at' => NULL,'updated_at' => '2021-03-25 09:39:36','item_id' => '8','title' => 'همبرگر 4','description' => 'asdfasdf','thumbnail' => 'noimage.jpg','price' => '47','package_price' => NULL,'unit' => 'piece','notes' => NULL,'details_status' => 'old'),
  array('id' => '11','created_at' => NULL,'updated_at' => '2021-03-25 12:03:45','item_id' => '1','title' => 'چپس ساده','description' => 'پیتزا مخصوص با سوس','thumbnail' => 'chips1_1616657218.jpg','price' => '50','package_price' => '0','unit' => 'piece','notes' => NULL,'details_status' => 'approved'),
  array('id' => '12','created_at' => NULL,'updated_at' => '2021-03-25 12:03:47','item_id' => '2','title' => 'همبرگر نونی','description' => 'همبرگر با نون اضافی','thumbnail' => 'bargar_1616657483.jpg','price' => '80','package_price' => '10','unit' => 'piece','notes' => NULL,'details_status' => 'approved'),
  array('id' => '13','created_at' => NULL,'updated_at' => '2021-03-25 12:03:48','item_id' => '7','title' => 'چپس مخصوص','description' => NULL,'thumbnail' => 'chips_1616657523.jpg','price' => '75','package_price' => NULL,'unit' => NULL,'notes' => NULL,'details_status' => 'approved'),
  array('id' => '14','created_at' => NULL,'updated_at' => '2021-03-25 12:03:50','item_id' => '8','title' => 'سمبوسه','description' => 'همراه با چپس','thumbnail' => 'content9555_1616657586.jpg','price' => '120','package_price' => NULL,'unit' => 'piece','notes' => NULL,'details_status' => 'approved'),
  array('id' => '15','created_at' => NULL,'updated_at' => '2021-03-25 12:16:03','item_id' => '3','title' => 'روغن','description' => '1 کیلو','thumbnail' => 'oil_1616657704.jpg','price' => '120','package_price' => '0','unit' => 'bottle','notes' => NULL,'details_status' => 'approved'),
  array('id' => '16','created_at' => NULL,'updated_at' => NULL,'item_id' => '4','title' => 'برنج','description' => '20 کیلو','thumbnail' => 'rice_1616657750.jpg','price' => '25','package_price' => '0','unit' => 'بوجی','notes' => NULL,'details_status' => 'old'),
  array('id' => '17','created_at' => NULL,'updated_at' => '2021-03-25 12:16:07','item_id' => '9','title' => 'آیسکریم','description' => '300 گرم','thumbnail' => 'cream_1616657797.jpg','price' => '60','package_price' => NULL,'unit' => 'عدد','notes' => NULL,'details_status' => 'approved'),
  array('id' => '18','created_at' => NULL,'updated_at' => '2021-03-25 12:16:05','item_id' => '4','title' => 'برنج','description' => '20 کیلو','thumbnail' => 'rice_1616657750.jpg','price' => '1200','package_price' => '0','unit' => 'بوجی','notes' => NULL,'details_status' => 'approved'),
  array('id' => '19','created_at' => NULL,'updated_at' => '2021-03-25 12:16:09','item_id' => '10','title' => 'روغن گیاهی','description' => 'آفتاب گردان','thumbnail' => 'oil2_1616657874.jpg','price' => '140','package_price' => NULL,'unit' => 'بوتل','notes' => NULL,'details_status' => 'approved'),
  array('id' => '20','created_at' => NULL,'updated_at' => '2021-03-25 12:19:46','item_id' => '5','title' => 'کیک','description' => 'کیک نانپزی','thumbnail' => 'image_1616658523.jpg','price' => '40','package_price' => '0','unit' => 'piece','notes' => NULL,'details_status' => 'approved'),
  array('id' => '21','created_at' => NULL,'updated_at' => '2021-03-25 12:19:48','item_id' => '6','title' => 'پیزا مخصوص','description' => NULL,'thumbnail' => 'images_1616658572.jpg','price' => '80','package_price' => '0','unit' => 'piece','notes' => NULL,'details_status' => 'approved'),
  array('id' => '22','created_at' => NULL,'updated_at' => '2021-03-25 12:29:06','item_id' => '11','title' => 'صابون','description' => 'گلنار','thumbnail' => '-زرد._1616659021.jpg','price' => '15','package_price' => NULL,'unit' => 'piece','notes' => NULL,'details_status' => 'approved')
));

        DB::table('categories')->insert(array(
            array('id' => '1','created_at' => '2021-03-01 10:40:55','updated_at' => '2021-03-01 10:40:55','title' => 'پیتزا','description' => NULL,'thumbnail' => 'noimage.jpg','status' => '1'),
            array('id' => '2','created_at' => '2021-03-01 10:41:06','updated_at' => '2021-03-01 10:41:06','title' => 'عمومی','description' => NULL,'thumbnail' => 'noimage.jpg','status' => '1')
          ));

        DB::table('menus')->insert(array(
            array('id' => '1','created_at' => '2021-03-01 10:44:29','updated_at' => '2021-03-01 10:44:29','title' => 'سنی مخصوص','branch_id' => '3','status' => '1','items' => '["5", "6"]')
        ));

        DB::table('orders')->insert(array(
            array('id' => '1','created_at' => '2021-03-01 10:52:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'First new order','branch_id' => '1','customer_id' => '7','has_delivery' => '1','total' => '200','commission_value' => '20','status' => 'pending','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "2", "price": "100", "item_id": "2"}}, {"item_3": {"count": "2", "price": "200", "item_id": "5"}}]}'),
            array('id' => '2','created_at' => '2021-03-02 11:12:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Second new order','branch_id' => '1','customer_id' => '7','has_delivery' => '0','total' => '150','commission_value' => '20','status' => 'approved','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "1", "price": "100", "item_id": "2"}}, {"item_3": {"count": "1", "price": "200", "item_id": "5"}}]}'),
            array('id' => '3','created_at' => '2021-03-03 2:02:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Third new order','branch_id' => '1','customer_id' => '7','has_delivery' => '1','total' => '250','commission_value' => '20','status' => 'reject','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "4", "price": "100", "item_id": "2"}}, {"item_3": {"count": "1", "price": "200", "item_id": "5"}}]}'),
            array('id' => '4','created_at' => '2021-03-04 10:25:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Fourth new order','branch_id' => '1','customer_id' => '7','has_delivery' => '0','total' => '50','commission_value' => '20','status' => 'processing','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}]}'),
            array('id' => '5','created_at' => '2021-03-05 10:00:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Fifth order','branch_id' => '1','customer_id' => '7','has_delivery' => '0','total' => '400','commission_value' => '20','status' => 'completed','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "1"}}, {"item_2": {"count": "8", "price": "100", "item_id": "2"}}, {"item_3": {"count": "6", "price": "200", "item_id": "5"}}]}'),

            array('id' => '6','created_at' => '2021-03-06 10:52:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'First new order','branch_id' => '2','customer_id' => '7','has_delivery' => '1','total' => '100','commission_value' => '20','status' => 'pending','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "9"}}, {"item_2": {"count": "4", "price": "100", "item_id": "3"}}, {"item_3": {"count": "8", "price": "200", "item_id": "4"}}]}'),
            array('id' => '7','created_at' => '2021-03-07 11:12:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Second new order','branch_id' => '2','customer_id' => '7','has_delivery' => '0','total' => '2000','commission_value' => '20','status' => 'approved','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "9"}}, {"item_2": {"count": "4", "price": "100", "item_id": "3"}}, {"item_3": {"count": "8", "price": "200", "item_id": "4"}}]}'),
            array('id' => '8','created_at' => '2021-03-04 2:02:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Third new order','branch_id' => '2','customer_id' => '7','has_delivery' => '1','total' => '1400','commission_value' => '20','status' => 'reject','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "9"}}, {"item_2": {"count": "4", "price": "100", "item_id": "3"}}, {"item_3": {"count": "8", "price": "200", "item_id": "4"}}]}'),
            array('id' => '9','created_at' => '2021-03-08 10:25:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Fourth new order','branch_id' => '2','customer_id' => '7','has_delivery' => '0','total' => '1200','commission_value' => '20','status' => 'processing','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "9"}}, {"item_2": {"count": "4", "price": "100", "item_id": "3"}}, {"item_3": {"count": "8", "price": "200", "item_id": "4"}}]}'),
            array('id' => '10','created_at' => '2021-03-014 10:00:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'Fifth order','branch_id' => '2','customer_id' => '7','has_delivery' => '0','total' => '200','commission_value' => '20','status' => 'completed','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "9"}}, {"item_2": {"count": "4", "price": "100", "item_id": "3"}}, {"item_3": {"count": "8", "price": "200", "item_id": "4"}}]}'),

            array('id' => '11','created_at' => '2021-03-24 10:00:15','updated_at' => NULL,'deleted_at' => NULL,'title' => 'test order by PM','branch_id' => '3','customer_id' => '7','has_delivery' => '0','total' => '300','commission_value' => '10','status' => 'completed','note' => 'test node','reciever_phone' => '0790909090','contents' => '{"contents": [{"item_1": {"count": "2", "price": "100", "item_id": "6"}}]}')
        ));

        DB::table('order_timing')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'order_id' => '1','approved_time' => '2021-03-04 04:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL), 
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'order_id' => '2','approved_time' => '2021-03-04 05:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'order_id' => '3','approved_time' => '2021-03-04 10:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'order_id' => '4','approved_time' => '2021-03-04 11:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '5','created_at' => NULL,'updated_at' => NULL,'order_id' => '5','approved_time' => '2021-03-04 10:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '6','created_at' => NULL,'updated_at' => NULL,'order_id' => '11','approved_time' => '2021-03-04 10:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '7','created_at' => NULL,'updated_at' => NULL,'order_id' => '5','approved_time' => '2021-03-04 04:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL), 
            array('id' => '8','created_at' => NULL,'updated_at' => NULL,'order_id' => '6','approved_time' => '2021-03-04 05:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '9','created_at' => NULL,'updated_at' => NULL,'order_id' => '7','approved_time' => '2021-03-04 10:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '10','created_at' => NULL,'updated_at' => NULL,'order_id' => '8','approved_time' => '2021-03-04 11:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),
            array('id' => '11','created_at' => NULL,'updated_at' => NULL,'order_id' => '9','approved_time' => '2021-03-04 10:52:15','rejected_time' => NULL,'processing_time' => NULL,'caceled_time' => NULL,'delivery_time' => NULL,'completed_time' => NULL),            
        ));

        DB::table('order_delivery')->insert(array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'order_id' => '1','delivery_type' => 'own','delivery_adress' => 'Herat chawk.','driver_id' => NULL,'delivery_commission' => '0'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'order_id' => '3','delivery_type' => 'company','delivery_adress' => 'Herat chawk.','driver_id' => NULL,'delivery_commission' => '0'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'order_id' => '8','delivery_type' => 'own','delivery_adress' => 'Herat chawk.','driver_id' => NULL,'delivery_commission' => '0'),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'order_id' => '6','delivery_type' => 'company','delivery_adress' => 'Herat chawk.','driver_id' => NULL,'delivery_commission' => '0')
            
        ));

        DB::table('drivers')->insert(array(
            array('id' => '1','created_at' => '2021-03-11 05:17:44','updated_at' => '2021-03-11 05:17:44','title' => 'Driver','user_id' => '3','contact' => '45854654','status' => 'free','token' => NULL),
            array('id' => '2','created_at' => '2021-03-11 05:18:32','updated_at' => '2021-03-11 05:18:32','title' => 'Paik Motor1','user_id' => '6','contact' => '45854654','status' => 'free','token' => NULL)          
        ));
        // \App\Models\User::factory(10)->create();
    }
}
