<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Branch;
use App\Models\User;

class Payment extends Model
{
    use LogsActivity;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

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
    protected $fillable = ['branch_id', 'reciever_id', 'paid_amount', 'date_and_time', 'note', 'status'];



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

    // Relation ship with commission
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'reciever_id');
    }

    public function branchDetails()
    {
        return $this->hasOne(BranchDetails::class, 'business_id', 'branch_id')->where('status', 'approved')->latest();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function orders()
    {        
        $orders = $this->hasMany(Order::class, 'payment_id');
        return $orders;
    }
}
