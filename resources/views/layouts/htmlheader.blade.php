<!-- for ajax post method security token in laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}" />

{{-- Datatables --}}
<link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

{{-- select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{{-- photoswipe --}}
<link rel="stylesheet" href="{{ url('vendor/photoswipe/photoswipe.css') }}">

{{-- sweetalert2 --}}
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">

<style>
    .select2-selection__rendered {
        line-height: 25px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
    }

    .pswp img {
        max-width: none;
        object-fit: contain;
    }

    .pswp__img--placeholder--blank{
        display: none !important;
    }
</style>
