@extends('adminlte::page')

@section('title', $title)

@section('content_header')

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title ?? '' }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-order-list') }}">Purchase Order</a></li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <a class="btn btn-success" name="btn" value="btnExcel" href="{{ route('purchase-order-list-export-excel') }}">Export Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="purchase-order-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Number</th>
                                    <th scope="col">Catatan</th>
                                    <th scope="col">Material</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrders as $purchaseOrder)
                                    <tr>
                                        <th scope="row">
                                            {{ $loop->iteration }}
                                        </th>
                                        <td>
                                            {{ $purchaseOrder->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $purchaseOrder->number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $purchaseOrder->notes ?? '' }}
                                        </td>
                                        <td>
                                            {{ $purchaseOrder->productMaterial->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $purchaseOrder->date->toDateString() ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $purchaseOrder->purchaseOrderStatus->name ?? '-' }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-success" name="btnDetailPo" href="{{ route('purchase-order-export-excel', ['id' => $purchaseOrder->id]) }}">Excel</a>
                                            <a class="btn btn-sm btn-info" name="btnPdfDetailPo" href="{{ route('purchase-order-export-pdf', ['id' => $purchaseOrder->id]) }}">Pdf</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script>
        $(document).ready( function () {
            $('#purchase-order-table').DataTable();
        } );
    </script>
@stop
