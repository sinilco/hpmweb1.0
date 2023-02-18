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
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\PurchaseOrderListExport;
use App\Exports\PurchaseOrderExport;

class PurchaseOrderController extends Controller
{
    // list purchase order
    public function index()
    {
        $data['title'] = 'List Purchase Order';
        $user = Auth::user();
        $purchaseOrders = PurchaseOrder::with(['purchaseOrderStatus', 'user', 'productMaterial', 'purchaseOrderItems']);

        if($user->hasRole('customer'))
        {
            $purchaseOrders = $purchaseOrders->where('user_id', $user->id);
        }

        $purchaseOrders = $purchaseOrders->get();
        $data['user'] = $user;
        $data['purchaseOrders'] = $purchaseOrders;
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
                    $purchaseOrderItem->weight = $request->defaultWeight[$i];
                    $purchaseOrderItem->length = $request->length[$i];
                    $purchaseOrderItem->qty = $request->quantity[$i];
                    $purchaseOrderItem->total_weight = $request->defaultWeight[$i] * $request->length[$i] * $request->quantity[$i];
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

    // edit purchase order
    public function edit($id)
    {
        $data['title'] = 'Edit Purchase Order';
        $data['user'] = Auth::user();
        $data['productHardness'] = ProductHardness::get();
        $data['productMaterial'] = ProductMaterial::get();
        $data['productSection'] = ProductSection::get();
        $data['productFinishing'] = ProductFinishing::get();
        $purchaseOrder = PurchaseOrder::with('productMaterial', 'user', 'purchaseOrderItems.productSection', 'purchaseOrderItems.productFinishing', 'purchaseOrderItems.productHardness')->find($id);
        $data['purchaseOrder'] = $purchaseOrder;
        $data['purchaseOrderItems'] = $purchaseOrder->purchaseOrderItems;

        return view('purchase_order.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try
        {
            $user = Auth::user();

            DB::transaction(function () use ($request, $user, $id)
            {
                $requestItems = count($request->hardness);

                // save PO
                $purchaseOrder = PurchaseOrder::with('purchaseOrderItems')->find($id);
                $purchaseOrder->product_material_id = $request->productMaterial;
                $purchaseOrder->purchase_order_status_id = PURCHASE_ORDER_STATUS_SEND;
                $purchaseOrder->notes = $request->notes;
                if($request->tax)
                {
                    $purchaseOrder->is_tax = $request->tax;
                }
                else
                {
                    $purchaseOrder->is_tax = 0;
                }
                $purchaseOrder->save();

                $purchaseOrderItems = $purchaseOrder->purchaseOrderItems;

                for ($i=0; $i < $requestItems; $i++)
                {
                    // condition when request item is equal or greater than saved data
                    if ($i < $purchaseOrderItems->count())
                    {
                        $purchaseOrderItem = $purchaseOrderItems[$i];
                    }
                    else
                    {
                        $purchaseOrderItem = new PurchaseOrderItem();
                    }

                    // purchase order item
                    $purchaseOrderItem->purchase_order_id = $purchaseOrder->id;
                    $purchaseOrderItem->product_section_id = $request->sectionId[$i];
                    $purchaseOrderItem->product_finishing_id = $request->finishing[$i];
                    $purchaseOrderItem->product_hardness_id = $request->hardness[$i];
                    $purchaseOrderItem->weight = $request->defaultWeight[$i];
                    $purchaseOrderItem->length = $request->length[$i];
                    $purchaseOrderItem->qty = $request->quantity[$i];
                    $purchaseOrderItem->total_weight = $request->defaultWeight[$i] * $request->length[$i] * $request->quantity[$i];
                    $purchaseOrderItem->save();
                }

                // condition when request item is less than saved data
                // delete item if request form count less than request item count
                if ($requestItems < $purchaseOrderItems->count())
                {
                    for ($i=$requestItems; $i < $purchaseOrderItems->count(); $i++) {
                        $requestItem = $purchaseOrderItems[$i];
                        $requestItem->delete();
                    }
                }
            });

            return redirect()->back()->withMessage('Update PO Success');
        }
        catch (\Exception $e)
        {
            dd($e);
            \Log::info($e);
            return redirect()->back()->withMessage('Terjadi Kesalahan');
        }
    }

    public function delete($id)
    {
        try
        {
            $user = Auth::user();

            DB::transaction(function () use ($user, $id)
            {
                // get PO
                $purchaseOrder = PurchaseOrder::with('purchaseOrderItems')->find($id);

                // delete item PO
                foreach ($purchaseOrder->purchaseOrderItems as $key => $purchaseOrderItem)
                {
                    $purchaseOrderItem->delete();
                }

                // delete PO
                $purchaseOrder->delete();
            });

            return redirect()->back()->withMessage('Delete PO Success');
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
        $purchaseOrders = PurchaseOrder::with(['purchaseOrderStatus', 'user', 'productMaterial', 'purchaseOrderItems.productSection', 'purchaseOrderItems.productFinishing', 'purchaseOrderItems.productHardness']);

        if($user->hasRole('customer'))
        {
            $purchaseOrders = $purchaseOrders->where('user_id', $user->id);
        }

        $purchaseOrders = $purchaseOrders->get();

        $data = [
                    'purchaseOrders' => $purchaseOrders
                ];

        $filename = 'export-purchase-order-list-'.$date->format('d-m-Y').'.xlsx';
        return Excel::download(new PurchaseOrderListExport($data), $filename);
    }

    /**
     * Export excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function detailExportExcel($id)
    {
        $date = Carbon::now();

        $user = Auth::user();
        $purchaseOrder = PurchaseOrder::with(['purchaseOrderStatus', 'user', 'productMaterial', 'purchaseOrderItems.productSection', 'purchaseOrderItems.productFinishing', 'purchaseOrderItems.productHardness'])
                                        ->find($id);

        $data = [
                    'purchaseOrder' => $purchaseOrder
                ];

        $filename = 'export-purchase-order-'.$purchaseOrder->number.'-'.$date->format('d-m-Y').'.xlsx';
        return Excel::download(new PurchaseOrderExport($data), $filename);
    }

    /**
     * Export pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function detailExportPdf($id)
    {
        $date = Carbon::now();

        $user = Auth::user();
        $purchaseOrder = PurchaseOrder::with(['purchaseOrderStatus', 'user', 'productMaterial', 'purchaseOrderItems.productSection', 'purchaseOrderItems.productFinishing', 'purchaseOrderItems.productHardness'])
                                        ->find($id);

        $data = [
                    'purchaseOrder' => $purchaseOrder
                ];

        $filename = 'export-purchase-order-'.$purchaseOrder->number.'.pdf';

        $pdf = Pdf::loadView('purchase_order.detailExportPdf', $data);
        return $pdf->download($filename);
        return view('purchase_order.detailExportPdf', $data);
    }
}
