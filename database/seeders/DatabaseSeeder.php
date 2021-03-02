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
                'password' => bcrypt('business'),
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
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'branch_id' => '4','category_id' => '1', 'status' => '1'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => '4','category_id' => '1', 'status' => '1'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'branch_id' => '5','category_id' => '2','status' => '1'),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'branch_id' => '5','category_id' => '2','status' => '1')
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
            array('id' => '1','created_at' => '2021-03-01 10:44:29','updated_at' => '2021-03-01 10:44:29','title' => 'Menu 1','branch_id' => '4','status' => '1','items' => '["1", "2"]')
        ));

        // \App\Models\User::factory(10)->create();
    }
}
