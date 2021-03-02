<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Commission;
use App\Models\Item;

class Branch extends Model
{
    use LogsActivity;
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branches';

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
    protected $fillable = ['user_id', 'business_type', 'main_commission_id', 'deliver_commission_id', 'status'];

    

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

    // Relationship with details table.
    public function branchDetails(){
        return $this->hasOne(BranchDetails::class, 'business_id')->where('status', 'approved')->latest();
    }
    
    // Relation ship with commission
    public function mainCommission(){
        return $this->hasOne(Commission::class, 'id', 'main_commission_id');
    }

    // Relation ship with commission
    public function deliveryCommission(){
        return $this->hasOne(Commission::class, 'id', 'deliver_commission_id');
    }

    // Relation ship with Branch
    public function payment(){
        return $this->hasMany(Payment::class);
    }
    
}
