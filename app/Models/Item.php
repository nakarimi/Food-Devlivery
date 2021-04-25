<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Category;

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
    public function approvedItemDetails(){
        return $this->hasOne(ItemDetails::class, 'item_id')->where('details_status', 'approved')->latest();
    }

    // Relationship with details table.
    public function pendingItemDetails(){
        return $this->hasOne(ItemDetails::class, 'item_id')->where('details_status', 'pending')->latest();
    }

    // Relationship with details table.
    public function rejectedItemDetails(){
        return $this->hasOne(ItemDetails::class, 'item_id')->where('details_status', 'rejected')->latest();
    }

    // Relationship with details table with full data.
    public function itemFullDetails(){
        return $this->hasMany(ItemDetails::class, 'item_id')->orderBy('id', 'desc');
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id')->select('type');
    }

    
}
