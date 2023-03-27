<style>
    .border {
        border: 1px solid black;
        border-collapse: collapse;
    }

    @page { margin: 10px; }
    body { margin: 10px; }

</style>
<div class="row">
    <div class="col-12">
        <img src="{{ asset('img/logo.png') }}" alt="" style="max-width: 900px">
    </div>
</div>
<div class="row">
    <div class="col-12" style="text-align: right">
        <table class="table" style="font-size: 10; font-weight:bold; width: 100%; line-height: 12px">
            <tr>
                <td style="text-align:right">Office & Factory:</td>
            </tr>
            <tr>
                <td style="text-align:right">Ngoro Industri Persada Blok K-2</td>
            </tr>
            <tr>
                <td style="text-align:right">Mojokerto, Jawa Timur - Indonesia</td>
            </tr>
            <tr>
                <td style="text-align:right">Tel: +62 321 6819 277-78</td>
            </tr>
            <tr>
                <td style="text-align:right">Email: info@hpmindonesia.co.id</td>
            </tr>
            <tr>
                <td style="text-align:right">Web: www.hpmindonesia.co.id</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12" style="text-align: center">
        <h1>Purchase Order</h1>
    </div>
</div>
<table class="table" style="font-size: 10.5; width: 100%">
    <tr>
        <td style="width: 10%">PO Number</td>
        <td style="width: 1%">:</td>
        <td style="width: 63%">{{ $purchaseOrder->number ?? '' }}</td>
        <td style="width: 6%">Date</td>
        <td style="width: 1%">:</td>
        <td style="width: 20%; text-align:right">{{ $purchaseOrder->date->toDateString() ?? '' }}</td>
    </tr>
    <tr>
        <td>Customer</td>
        <td>:</td>
        <td>{{ $purchaseOrder->user->name ?? '' }}</td>
        <td>Material</td>
        <td>:</td>
        <td style="text-align:right">{{ $purchaseOrder->productMaterial->name ?? '' }}</td>
    </tr>
    <tr>
        <td>Destination</td>
        <td>:</td>
        <td>{{ $purchaseOrder->user->name ?? '' }}</td>
        <td></td>
        <td></td>
        <td style="text-align:right">
            @if ($purchaseOrder->is_tax)
                PPN
            @endif
        </td>
    </tr>
</table>
<table class="table border" cellspacing="0" style="font-size: 10; width:100%;">
    <thead class="border">
        <tr>
            <th style="text-align:center" class="border">No</th>
            <th style="text-align:center" class="border">Section</th>
            <th style="text-align:center; width:30%" class="border">Description</th>
            <th style="text-align:center" class="border">Finish</th>
            <th style="text-align:center" class="border">HRD</th>
            <th style="text-align:center" class="border">Weight (Kg/m)</th>
            <th style="text-align:center" class="border">Length (m)</th>
            <th style="text-align:center" class="border">Qty</th>
            <th style="text-align:center" class="border">Weight Total (kg)</th>
        </tr>
    </thead>
    @foreach ($purchaseOrder->purchaseOrderItems as $purchaseOrderItem)
        <tr>
            <td class="border" style="text-align:center; padding-top:10px; padding-bottom: 10px" >{{ $loop->iteration }}</td>
            <td class="border" style="text-align:center; padding-top:10px; padding-bottom: 10px" >{{ $purchaseOrderItem->productSection->name ?? '' }}</td>
            <td class="border" style="padding-top:10px; padding-bottom: 10px">{{ $purchaseOrderItem->productSection->description ?? '' }}</td>
            <td class="border" style="text-align:center; padding-top:10px; padding-bottom: 10px" >{{ $purchaseOrderItem->productFinishing->name ?? '' }}</td>
            <td class="border" style="text-align:center; padding-top:10px; padding-bottom: 10px" >{{ $purchaseOrderItem->productHardness->name ?? '' }}</td>
            <td class="border" style="text-align:right; padding-top:10px; padding-bottom: 10px" >{{ $purchaseOrderItem->weight ?? '' }}</td>
            <td class="border" style="text-align:right; padding-top:10px; padding-bottom: 10px" >{{ $purchaseOrderItem->length ?? '' }}</td>
            <td class="border" style="text-align:right; padding-top:10px; padding-bottom: 10px" >{{ $purchaseOrderItem->qty ?? '' }}</td>
            <td class="border" style="text-align:right; padding-top:10px; padding-bottom: 10px" >{{ $purchaseOrderItem->total_weight ?? '' }}</td>
        </tr>
    @endforeach
    <tr>
        <td class="border" colspan="5"></td>
        <td class="border" colspan="2" style="text-align: center; font-weight:bold">T O T A L</td>
        <td class="border" style="font-weight:bold; text-align:right">{{ $purchaseOrder->total_qty ?? '' }}</td>
        <td class="border" style="font-weight:bold; text-align:right">{{ $purchaseOrder->total_weight ?? '' }}</td>
    </tr>
</table>
<table class="table" style="font-size: 10; width: 50%; border-style:dashed; border-color: black;">
    <tr>
        <td style="width: 50%; font-weight: bold">NOTE:</td>
    </tr>
    <tr>
        <td style="width: 50%; font-weight: bold">KIRIM KE {{ $purchaseOrder->user->name }}</td>
    </tr>
    <tr>
        <td style="width: 50%; font-weight: bold">- HARGA UNTUK EXELCON - CA 59.500 / KG INCLUDE PPN</td>
    </tr>
    <tr>
        <td style="width: 50%; font-weight: bold">- ALAMAT KIRIM : </td>
    </tr>
    <tr>
        <td style="width: 50%; font-weight: bold">- KOMPLIT SET</td>
    </tr>
    <tr>
        <td style="width: 50%; font-weight: bold">- TOLERANSI +/- 10%</td>
    </tr>
</table>
