<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $dates = ['date'];

    protected $appends = ['total_qty', 'total_weight'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    //------------------------------------------- MODEL RELATIONS ------------------------------------

    /**
     * Get the product material that owns the purchase order.
     */
    public function productMaterial()
    {
        return $this->belongsTo(ProductMaterial::class);
    }

    /**
     * Get all of the user that owns the purchase order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the purchase order item that owns the purchase order
     */
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get the purchase order status that owns the purchase order.
     */
    public function purchaseOrderStatus()
    {
        return $this->belongsTo(PurchaseOrderStatus::class);
    }

    //------------------------------------------- ADDED ATTRIBUTES -----------------------------------

    public function getTotalQtyAttribute()
    {
        return $this->purchaseOrderItems->sum('qty');
    }

    public function getTotalWeightAttribute()
    {
        return $this->purchaseOrderItems->sum('total_weight');
    }
}
