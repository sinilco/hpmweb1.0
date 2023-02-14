<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    use HasFactory;
    
    protected $table = "product_material";

    // public function purchaseOrders()
    // {
    // 	return $this->hasMany(PurchaseOrder::class);
    // }
}
