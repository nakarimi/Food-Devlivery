<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    // user with role relationship
    public function role(){
        return $this -> hasOne('App\Models\Role','id','role_id');
    }

    // Relation ship with Branch
    public function payment(){
        return $this->hasMany(Payment::class);
    }

    // Relation ship Blocked Customers (user who blocked customer).
    public function UserBlockedCustomer(){
        return $this->hasOne(BlockCustomer::class, 'user_id');
    }
    // Relation ship with Blocked Customers (Customers who is being blocked).
    public function blockedCustomer(){
        return $this->hasOne(BlockCustomer::class, 'customer_id');
    }
    // user with address
    public function address(){
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }
}
