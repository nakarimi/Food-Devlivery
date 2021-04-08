<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTimeDetails extends Model
{
    use HasFactory;

    protected $table = 'order_timing';

    protected $fillable = ['order_id', 'processing_time', 'rejected_time', 'promissed_time'];

}
