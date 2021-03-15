<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class DeliveryDetails extends Model
{
    use HasFactory;

    protected $table = 'order_delivery';

    public function driver(){
        return $this->hasOne(Driver::class, 'id', 'driver_id')->latest();
    }
}
