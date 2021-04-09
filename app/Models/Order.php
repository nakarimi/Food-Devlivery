<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use LogsActivity;
    use SoftDeletes;
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
    protected $fillable = ['title', 'branch_id', 'customer_id', 'has_delivery', 'total', 'commission_value', 'status', 'note', 'reciever_phone', 'contents'];

    

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

    // Relationship with delivery details table.
    public function deliveryDetails(){
        return $this->hasOne(DeliveryDetails::class, 'order_id');
    }

    // Relationship with order time details table.
    public function timeDetails(){
        return $this->hasOne(OrderTimeDetails::class, 'order_id');
    }

    public function branchDetails(){
        return $this->hasOne(BranchDetails::class, 'business_id', 'branch_id')->where('status', 'approved')->latest();
    }

    // Relation ship with User.
    public function customer(){
        return $this->hasOne(User::class, 'id', 'customer_id');
    }

    // Casting field 'contents' to an array.
    function contentsToArray() {
        return json_decode($this->contents, true);
    }
}
