<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryDetails extends Model
{
    use HasFactory;

    protected $table = 'order_delivery';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'delivery_address', 'delivery_type'];

    public function driver(){
        return $this->hasOne(Driver::class, 'id', 'driver_id')->latest();
    }
    
    // Definning the order relation
    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
    
}

