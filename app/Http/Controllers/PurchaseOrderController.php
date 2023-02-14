<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\ProductFinishing;
use App\Models\ProductHardness;
use App\Models\ProductMaterial;
use App\Models\ProductSection;

class PurchaseOrderController extends Controller
{
    // list purchase order
    public function index()
    {
        $data['title'] = 'List Purchase Order';
        $data['user'] = Auth::user();
        $data['purchaseOrders'] = PurchaseOrder::with(['user', 'productMaterial', 'purchaseOrderItems'])->get();

        return view('purchase_order.index', $data);
    }

    // create purchase order
    public function create()
    {
        $data['title'] = 'Create Purchase Order';
        $data['user'] = Auth::user();
        $data['productHardness'] = ProductHardness::get();
        $data['productMaterial'] = ProductMaterial::get();
        $data['productSection'] = ProductSection::get();
        // $data['productSectionJson'] = ProductSection::get()
        //                                         ->map(function ($product) {
        //                                             return ['id' => $product->id , 'name' => $product->name, 'description' => $product->description, 'weight' => $product->weight, 'image_path' => $product->image_path];
        //                                         })
        //                                         ->toJson();
        $data['productFinishing'] = ProductFinishing::get();

        return view('purchase_order.create', $data);
    }

    public function getProductSectionAjax(Request $request)
    {
        try {
            $productSection = ProductSection::where('description','LIKE','%'.$request->searchVal.'%')->get();

            return json_encode($productSection);

        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
