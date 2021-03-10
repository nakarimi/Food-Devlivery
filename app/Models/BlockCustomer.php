<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockCustomer extends Model
{
    use HasFactory;

    // Relation ship with Branch
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // Relation ship with User
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation ship with Customer
    public function customer(){
        return $this->belongsTo(User::class, 'customer_id');
    }
}
