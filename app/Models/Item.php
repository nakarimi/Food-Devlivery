<?php

namespace App\Models;

use App\Models\ItemDetails;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Branch;

class Item extends Model
{
    use LogsActivity;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'items';

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
    protected $fillable = ['branch_id','category_id', 'status'];



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
    public function itemDetails(){
        return $this->hasOne(ItemDetails::class, 'item_id')->where('details_status', 'approved')->latest();
    }

    // Relationship with details table with full data.
    public function itemFullDetails(){
        return $this->hasMany(ItemDetails::class, 'item_id');
    }
}
