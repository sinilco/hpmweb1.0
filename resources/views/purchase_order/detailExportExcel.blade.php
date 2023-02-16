<table class="display" cellspacing="0">
    <tr>
        <td>PO Number</td>
        <td>{{ $purchaseOrder->number ?? '' }}</td>
    </tr>
    <tr>
        <td>Customer</td>
        <td>{{ $purchaseOrder->user->name ?? '' }}</td>
    </tr>
    <tr>
        <td>Destination</td>
        <td>{{ $purchaseOrder->user->name ?? '' }}</td>
    </tr>
    <tr>
        <td>Date</td>
        <td>{{ $purchaseOrder->date->toDateString() ?? '' }}</td>
    </tr>
    <tr>
        <td>Material</td>
        <td>{{ $purchaseOrder->productMaterial->name ?? '' }}</td>
    </tr>
    <tr>
        <td></td>
        <td>
            @if ($purchaseOrder->is_tax)
                PPN
            @endif
        </td>
    </tr>
    <thead>
        <tr>
            <th style="text-align:center">No</th>
            <th style="text-align:center">Section</th>
            <th style="text-align:center">Description</th>
            <th style="text-align:center">Finish</th>
            <th style="text-align:center">HRD</th>
            <th style="text-align:center">Weight (Kg/m)</th>
            <th style="text-align:center">Length (m)</th>
            <th style="text-align:center">Qty</th>
            <th style="text-align:center">Weight Total (kg)</th>
        </tr>
    </thead>
    @foreach ($purchaseOrder->purchaseOrderItems as $purchaseOrderItem)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $purchaseOrderItem->productSection->name ?? '' }}</td>
            <td>{{ $purchaseOrderItem->productSection->description ?? '' }}</td>
            <td>{{ $purchaseOrderItem->productFinishing->name ?? '' }}</td>
            <td>{{ $purchaseOrderItem->productHardness->name ?? '' }}</td>
            <td>{{ $purchaseOrderItem->weight ?? '' }}</td>
            <td>{{ $purchaseOrderItem->length ?? '' }}</td>
            <td>{{ $purchaseOrderItem->qty ?? '' }}</td>
            <td>{{ $purchaseOrderItem->total_weight ?? '' }}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2" style="text-align: right">Total</td>
        <td>{{ $purchaseOrder->total_qty ?? '' }}</td>
        <td>{{ $purchaseOrder->total_weight ?? '' }}</td>
    </tr>
</table>
