<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $dates = [];

    protected $appends = [];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    //------------------------------------------- MODEL RELATIONS ------------------------------------

    /**
     * Get the purchase order that owns the purchase order item.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

     /**
     * Get the product section that owns the purchase order item.
     */
    public function productSection()
    {
        return $this->belongsTo(ProductSection::class);
    }

     /**
     * Get the product finishing that owns the purchase order item.
     */
    public function productFinishing()
    {
        return $this->belongsTo(ProductFinishing::class);
    }

     /**
     * Get the product hardness that owns the purchase order item.
     */
    public function productHardness()
    {
        return $this->belongsTo(ProductHardness::class);
    }

}
