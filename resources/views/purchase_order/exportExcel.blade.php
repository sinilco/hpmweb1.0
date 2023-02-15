<table class="display" cellspacing="0">
    <thead>
        <tr>
            <th style="text-align:center">No</th>
            <th style="text-align:center">No. PO.</th>
            <th style="text-align:center">User</th>
            <th style="text-align:center">Material</th>
            <th style="text-align:center">Catatan</th>
            <th style="text-align:center">Tanggal</th>
            <th style="text-align:center">Tax</th>
            <th style="text-align:center">Status</th>
        </tr>
    </thead>
    @foreach ($purchaseOrders as $purchaseOrder)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $purchaseOrder->number ?? '' }}</td>
            <td>{{ $purchaseOrder->user->name ?? '' }}</td>
            <td>{{ $purchaseOrder->productMaterial->name ?? '' }}</td>
            <td>{{ $purchaseOrder->notes ?? '' }}</td>
            <td>{{ $purchaseOrder->date->toDateString() ?? '' }}</td>
            <td>
                @if ($purchaseOrder->is_tax)
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td>{{ $purchaseOrder->purchaseOrderStatus->name ?? '' }}</td>
        </tr>
    @endforeach
</table>
