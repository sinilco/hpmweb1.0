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

use Carbon\Carbon;
use DB;
use Redirect;
use Excel;

use App\Exports\PurchaseOrderExport;

class PurchaseOrderController extends Controller
{
    // list purchase order
    public function index()
    {
        $data['title'] = 'List Purchase Order';
        $data['user'] = Auth::user();
        $data['purchaseOrders'] = PurchaseOrder::with(['purchaseOrderStatus', 'user', 'productMaterial', 'purchaseOrderItems'])->get();

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

    public function store(Request $request)
    {
        try
        {
            $user = Auth::user();

            DB::transaction(function () use ($request, $user)
            {
                $requestItems = count($request->hardness);

                // save PO
                $purchaseOrder = new PurchaseOrder();
                $purchaseOrder->user_id = $user->id;
                $purchaseOrder->product_material_id = $request->productMaterial;
                $purchaseOrder->purchase_order_status_id = PURCHASE_ORDER_STATUS_SEND;
                $purchaseOrder->date = Carbon::now()->toDateString();
                $purchaseOrder->notes = $request->notes;
                if($request->tax)
                {
                    $purchaseOrder->is_tax = $request->tax;
                }
                $purchaseOrder->save();

                for ($i=0; $i < $requestItems; $i++)
                {
                    // purchase order item
                    $purchaseOrderItem = new PurchaseOrderItem();
                    $purchaseOrderItem->purchase_order_id = $purchaseOrder->id;
                    $purchaseOrderItem->product_section_id = $request->sectionId[$i];
                    $purchaseOrderItem->product_finishing_id = $request->finishing[$i];
                    $purchaseOrderItem->product_hardness_id = $request->hardness[$i];
                    $purchaseOrderItem->length = $request->length[$i];
                    $purchaseOrderItem->qty = $request->quantity[$i];
                    $purchaseOrderItem->save();
                }
            });

            return redirect()->back()->withMessage('Input PO Success');
        }
        catch (\Exception $e)
        {
            dd($e);
            \Log::info($e);
            return redirect()->back()->withMessage('Terjadi Kesalahan');
        }
    }

    /**
     * Export excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        $date = Carbon::now();

        $user = Auth::user();
        $purchaseOrders = PurchaseOrder::with(['purchaseOrderStatus', 'user', 'productMaterial', 'purchaseOrderItems.productSection', 'purchaseOrderItems.productFinishing', 'purchaseOrderItems.productHardness'])->get();

        $data = [
                    'purchaseOrders' => $purchaseOrders
                ];

        $filename = 'export-purchase-order-data-'.$date->format('d-m-Y').'.xlsx';
        return Excel::download(new PurchaseOrderExport($data), $filename);
    }
}
