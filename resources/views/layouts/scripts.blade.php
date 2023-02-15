<!-- REQUIRED JS SCRIPTS -->

{{-- Datatables --}}
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

{{-- select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- cleave js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.5.3/cleave.min.js"></script>

{{-- sum datatable --}}
<script src="https://cdn.datatables.net/plug-ins/1.13.2/api/sum().js"></script>

{{-- photoswipe --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.3.5/umd/photoswipe-lightbox.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.3.5/umd/photoswipe.umd.min.js"></script>

{{-- sweetalert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var baseURL = {!! json_encode(url('/')) !!};    //getting base url
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
