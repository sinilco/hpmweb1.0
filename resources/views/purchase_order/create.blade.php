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
                <li class="breadcrumb-item active">Create</li>
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
                <div class="card-body">
                    <form method="post" action="{{ route('purchase-order-store') }}" id="formPo">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-md-2 offset-md-10">
                                        <button type="button" name="save" value="save" id="btnSubmitPo" class="form-control btn btn-sm btn-success">Send PO</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="form-group">
                                    <label>No Purchase Order</label>
                                    <input type="text" name="purchaseOrderNumber" value="" class="form-control" placeholder="No Purchase Order" autocomplete="off" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Send To</label>
                                    <input type="text" name="sendTo" value="{{ $user->name }}" class="form-control" placeholder="Address" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="notes" id="" rows="3" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Material</label>
                                    <select name="productMaterial" class="form-control" id="select-material" required>
                                        <option value="" disabled selected>Pilih</option>
                                        @foreach ($productMaterial as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="tax-checkbox" name="tax" value="1">
                                    <label for="tax-checkbox">Tax</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Section</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" autocomplete="off" value="" name="searchVal" class="form-control" id="searchVal">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <a class="btn btn-fill btn-primary" id="browse-section">Browse</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="overflow-x: auto; width: 100%;">
                                <div class="table-responsive">
                                    <table class="table" id="table-po-item">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Section Name</th>
                                                <th>Description</th>
                                                <th>Hardness</th>
                                                <th>Finish</th>
                                                <th>Length (m)</th>
                                                <th>Qty (pcs)</th>
                                                <th>Weight</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6">TOTAL</th>
                                                <th><span id="totalQty">0</span></th>
                                                <th><span id="totalWeight">0</span></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Section List</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="height: 500px; overflow-y: auto;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12" id="productSectionDiv">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@stop

@section('js')
    <script>
        var table = $('#table-po-item').DataTable(
            {
                bFilter: false,
                ordering: false,
                lengthChange: false,
                targets: 0,
            }
        );

        $(document).ready( function () {
            $('#select-material').select2({
                placeholder: 'Select Material',
                width: 'element',
            });

            $( "#browse-section" ).click(function() {
                var productSection = getProductSection();

                $('#myModal').modal('show');
            });

            // format number to thousand separator
            function formatNumber(field)
            {
                new Cleave(field, {
                        numeralDecimalMark: '.',
                        delimiter: ',',
                        numeralDecimalScale: 0,
                        numeralPositiveOnly: true,
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand',
                    });
            }

            // function for get product section ajax
            function getProductSection()
            {
                // get search value
                var searchVal = $('#searchVal').val();

                // get product section ajax
                $.ajax({
                    type: "GET",
                    url: "{{ route('get-product-section-ajax') }}",
                    data:
                    {
                        'searchVal' : searchVal,
                    },
                    dataType: "json",
                    success: function(productSection) {
                        setProductSection(productSection);
                        return productSection;
                    },
                });
            }

            // set product section to modal
            function setProductSection(data)
            {
                // remove all option in modal
                $('#productSectionDiv').empty();

                // loop through product section
                $.each(data, function(key, section)
                {
                    // insert new card
                    var newCard = '<div class="card card-stats">'+
                                    '<div class="content" style="padding: 15px">'+
                                        '<div class="row">'+
                                            '<div class="col-md-7">'+
                                                '<div>'+
                                                    '<i class="fa fa-plus active-icon add-section" style="color: black; cursor: pointer;" data-section-id="'+section.id+'">'+
                                                    '</i>'+
                                                '</div>'+
                                                '<div class="text-center">'+
                                                    '<a href="'+baseURL+'/img/product_section/'+section.image_path+'" data-pswp-src="'+baseURL+'/img/product_section/'+section.image_path+'" data-pswp-width="300" data-pswp-height="300" data-pswp-srcset="'+baseURL+'/img/product_section/'+section.image_path+'" target="_blank">'+
                                                        '<img class="myAjaxLoadedImage" style="height: 100%; width: 100%; max-width: 150px; max-height: 150px; cursor: pointer;" src="'+baseURL+'/img/product_section/'+section.image_path+'" alt="">'+
                                                    '</a>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="col-md-5">'+
                                                '<div><span style="font-weight: bold">Description :</span></div>'+section.description+
                                                '<div class="text-left">'+
                                                    '<table>'+
                                                        '<tr>'+
                                                            '<th style="width: 50%">Section</th>'+
                                                            '<td style="width: 10%">:</td>'+
                                                            '<td style="width: 40%">'+section.name+'</td>'+
                                                        '</tr>'+
                                                        '<tr>'+
                                                            '<th>OP</th>'+
                                                            '<td>:</td>'+
                                                            '<td>'+section.open_perimeter+'</th>'+
                                                        '</tr>'+
                                                        '<tr>'+
                                                            '<th>W</th>'+
                                                            '<td>:</td>'+
                                                            '<td>'+section.weight+'</td>'+
                                                        '</tr>'+
                                                        '<tr>'+
                                                            '<th>Koli</th>'+
                                                            '<td>:</td>'+
                                                            '<td>'+section.koli+'</td>'+
                                                        '</tr>'+
                                                    '</table>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';

                    $('#productSectionDiv').append(newCard);
                });
            }

            var productSection = {!! json_encode($productSection->toArray(), JSON_HEX_TAG) !!};

            // if add-section is clicked
            $(document).on('click', '.add-section', function ()
            {
                // get the section id
                var dataSectionId =  $(this).attr("data-section-id");

                // close modal
                $('#myModal').modal('toggle');

                // find section data
                var selectedSection = productSection.find(({ id }) => id == dataSectionId);

                // add data to table
                var sectionComponent = '<tr>'+
                                                '<td>'+
                                                '</td>'+
                                                '<td>'+
                                                    '<input type="hidden" name="sectionId[]" class="form-control" autocomplete="off" value="'+selectedSection.id+'">'+
                                                    '<div style="text-align: center;">'+
                                                        '<img class="fullSizeImage" style="max-width: 180px" src="'+baseURL+'/img/product_section/'+selectedSection.image_path+'" alt="">'+
                                                        '<br>'+
                                                        selectedSection.name +
                                                    '</div>'+
                                                '</td>'+
                                                '<td>'+
                                                    selectedSection.description +
                                                '</td>'+
                                                '<td>'+
                                                    '<div class="form-group">'+
                                                        '<select name="hardness[]" class="form-control" id="hardness" style="width: 70px;">'+
                                                            '<option value="2">T4 </option>'+
                                                            '<option value="3">T5 </option>'+
                                                            '<option value="4">T6 </option>'+
                                                            '<option value="6">T52 </option>'+
                                                            '<option value="7">TO </option>'+
                                                            '<option value="8">T61 </option>'+
                                                        '</select>'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class="form-group">'+
                                                        '<select name="finishing[]" class="form-control" id="finish" style="width: 150px;">'+
                                                            '<option value="1">Black Anodizing </option>'+
                                                            '<option value="2">Protective Black Anodizing </option>'+
                                                            '<option value="3">Brown Anodizing </option>'+
                                                            '<option value="4">Protective Brown Anodizing </option>'+
                                                            '<option value="5">Clear Anodizing </option>'+
                                                            '<option value="6">Protective Clear Anodizing </option>'+
                                                            '<option value="7">CA Sand Blast </option>'+
                                                            // '<option value="Dark Brown">Dark Brown </option>'+
                                                            // '<option value="DB Sand Blast">DB Sand Blast </option>'+
                                                            // '<option value="GL01">GL01 </option>'+
                                                            // '<option value="GL02">GL02 </option>'+
                                                            // '<option value="Protective GL02">Protective GL02 </option>'+
                                                            // '<option value="Gold Anodizing">Gold Anodizing </option>'+
                                                            // '<option value="Mill Finish">Mill Finish </option>'+
                                                            // '<option value="Jasa + Powder Coating">Jasa + Powder Coating </option>'+
                                                            // '<option value="Powder Coating">Powder Coating </option>'+
                                                            // '<option value="Polish">Polish </option>'+
                                                            // '<option value="Sand Blast">Sand Blast </option>'+
                                                            // '<option value="Satin Nickle Anodize">Satin Nickle Anodize </option>'+
                                                            // '<option value="Satin Silver">Satin Silver </option>'+
                                                        '</select>'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class="form-group">'+
                                                        '<input type="text" name="length[]" class="form-control inputLength" autocomplete="off" value="1" style="width: 80px;">'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class="form-group">'+
                                                        '<input type="text" name="quantity[]" class="form-control inputQty" autocomplete="off" value="1" style="width: 100px;">'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td>'+
                                                    '<span class="inputWeight">'+selectedSection.weight+'</span>'+
                                                    '<input type="hidden" name="defaultWeight[]" class="form-control inputHiddenDefaultWeight" autocomplete="off" value="'+selectedSection.weight+'">'+
                                                    '<input type="hidden" name="weight[]" class="form-control inputHiddenWeight" autocomplete="off" value="'+selectedSection.weight+'">'+
                                                '</td>'+
                                                '<td>'+
                                                    '<i class="fas fa-times remove" style="cursor: pointer;"></i>'+
                                                '</td>'+
                                            '</tr>';

                // added to table
                table.row.add($(sectionComponent).get(0)).draw();

                // set a format number to all input field (length)
                var inputLengthCollection = document.getElementsByClassName("inputLength");
                var lengthCollections = Array.from(inputLengthCollection);

                lengthCollections.forEach(function (lengthCollection) {
                    formatNumber(lengthCollection);
                });

                // set a format number to all input field (qty)
                var inputQtyCollection = document.getElementsByClassName("inputQty");
                var qtyCollections = Array.from(inputQtyCollection);

                qtyCollections.forEach(function (qtyCollection) {
                    formatNumber(qtyCollection);
                });

                // trigger the inputQty and inputWeight
                $('.inputQty').trigger('keyup');
                $('.inputWeight').trigger('change');
            });
        });

        // triggering for update total qty
        $('#table-po-item tbody').on('keyup', '.inputQty, .inputLength', function ()
        {
            // get input qty on row keyup
            var inputQtyRow = table.row( $(this).closest('tr') ).nodes().reduce( function ( sum, node ) {
                            return sum + parseInt($( node ).find( '.inputQty' ).val().replace(/\,/g,""));
                        }, 0 );

            // get input length on row keyup
            var inputLengthRow = table.row( $(this).closest('tr') ).nodes().reduce( function ( sum, node ) {
                            return sum + parseInt($( node ).find( '.inputLength' ).val().replace(/\,/g,""));
                        }, 0 );

            // get input default weight on row keyup
            var inputDefaultWeightRow = table.row( $(this).closest('tr') ).nodes().reduce( function ( sum, node ) {
                            return sum + parseFloat($( node ).find( '.inputHiddenDefaultWeight' ).val());
                        }, 0 );

            // count weight depend on length and qty
            // qty * length * weight
            var totalWeight = inputQtyRow * inputLengthRow * inputDefaultWeightRow;

            // set total weight to span and hidden input
            table.row( $(this).closest('tr') ).nodes().reduce( function ( sum, node ) {
                    $( node ).find( '.inputWeight' ).html(totalWeight.toLocaleString("en-US"));
                }, 0 );

            table.row( $(this).closest('tr') ).nodes().reduce( function ( sum, node ) {
                    $( node ).find( '.inputHiddenWeight' ).val(totalWeight);
                }, 0 );

            // calculate total qty
            var totalQty = table.column( 6 ).nodes().reduce( function ( sum, node ) {
                            return sum + parseInt($( node ).find( 'input' ).val().replace(/\,/g,""));
                        }, 0 );

            // set total qty to table footer
            $('#totalQty').html(totalQty.toLocaleString("en-US"));

            // calculate total weight
            var totalColumnWeight = table.column( 7 ).nodes().reduce( function ( sum, node ) {
                            return sum + parseFloat($( node ).find( '.inputHiddenWeight' ).val().replace(/\,/g,""));
                        }, 0 );

            // set total weight to table footer
            $('#totalWeight').html(totalColumnWeight.toLocaleString("en-US"));
        });

        // remove row data
        $('#table-po-item').on('click', '.remove', function () {
            table.row($(this).parents('tr')).remove().draw();
		});

        // set numeral index
        table.on( 'draw.dt', function () {
        var pageInfo = $('#table-po-item').DataTable().page.info();
            table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + pageInfo.start;
            } );
        } );

        // form validation
        $('form #btnSubmitPo').click(function(e)
        {
            event.preventDefault();
            if ( ! table.data().count() )
            {
                Swal.fire('Harap masukkan minimal 1 Section')
            }
            else
            {
                Swal.fire({
                title: 'Send PO',
                text: "Apakah anda yakin data PO sudah sesuai?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#formPo').submit();
                    $(':input[type="submit"]').prop('disabled', true);
                }
                })
            }
        });
    </script>

    <script type="module">
        var lightbox = new PhotoSwipeLightbox({
                            gallery: '#productSectionDiv',
                            children: 'a',
                            // dynamic import is not supported in UMD version
                            pswpModule: PhotoSwipe
                        });
                        lightbox.init();
    </script>
@stop
