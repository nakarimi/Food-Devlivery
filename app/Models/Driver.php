<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Driver extends Model
{
    use LogsActivity;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'drivers';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'user_id', 'contact', 'status', 'token'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function delivered()
    {
        // Load the IDs of orders that has recieved_driver_payments records.
        // And ignore those orders to be loaded on the Drivers Who have money table.
        $orders = [];
        $recived_orders = DB::table('recieved_driver_payments')->get()->pluck('orders_id');

        // Merge all Orders Ids in a sinle array.
        foreach ($recived_orders as $key => &$value) {
            $orders = array_merge($orders, json_decode($value));
        }
        return $this->hasMany(DeliveryDetails::class, 'driver_id')->whereNotIn('order_id', $orders);
    }
}
