@extends('layouts.main')
@section('page-title')
    {{ __('Edit Purchase Orders') }}
@endsection
@section('page-breadcrumb')
    {{ __('POs') }}
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>

    <script>
        @if (module_is_active('CMMS'))
            $(document).on('change', 'select[name=location]', function() {
                var location_id = $(this).val();
                getsupplier(location_id);
            });
    
            function getsupplier(did) {
                $.ajax({
                    url: '{{ route('getsupplier') }}',
                    type: 'POST',
                    data: {
                        "location_id": did,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $('#supplier_id').empty();
                        $('#supplier_id').append();
                        $.each(data, function(key, value) {
                            $('#supplier_id').append('<option value="' + key + '">' + value +
                                '</option>');
                        });
                    }
                });
            }
        @endif
    </script>

<script>
    @if (module_is_active('CMMS'))
        $(document).on('change', 'select[name=location]', function() {
            var location_id = $(this).val();
            getitems(location_id);
        });

        function getitems(did) {
            $.ajax({
                url: '{{ route('getitems') }}',
                type: 'POST',
                data: {
                    "location_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#part_id').empty();
                    $('#part_id').append();
                    $.each(data, function(key, value) {
                        $('#part_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }
    @endif
</script>

    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: true,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    $('.select2').select();
                },
                hide: function(deleteElement) {

                    $(this).slideUp(deleteElement);
                    $(this).remove();
                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));
                    $('.totalAmount').html(subTotal.toFixed(2));

                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');

            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                    tr.find('.item').val(value[i].parts_id);
                    changeItem(tr.find('.item'));
                }
            }
        }

        $(document).on('change', '#customer', function() {
            $('#customer_detail').removeClass('d-none');
            $('#customer_detail').addClass('d-block');
            $('#customer-box').removeClass('d-block');
            $('#customer-box').addClass('d-none');
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {
                    if (data != '') {
                        $('#customer_detail').html(data);
                    } else {
                        $('#customer-box').removeClass('d-none');
                        $('#customer-box').addClass('d-block');
                        $('#customer_detail').removeClass('d-block');
                        $('#customer_detail').addClass('d-none');
                    }
                },

            });
        });

        $(document).on('click', '#remove', function() {
            $('#customer-box').removeClass('d-none');
            $('#customer-box').addClass('d-block');
            $('#customer_detail').removeClass('d-block');
            $('#customer_detail').addClass('d-none');
        })

        $(document).on('change', '.item', function() {
            changeItem($(this));
        });

        var invoice_id = '{{ $invoice->id }}';

        function changeItem(element) {
            var iteams_id = element.val();
            var url = element.data('url');
            var el = element;

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function(data) {       
                    var item = JSON.parse(data);
                    $.ajax({
                        url: '{{route('cmmsPos.items')}}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'invoice_id': invoice_id,
                            'product_id': iteams_id,
                        },
                        cache: false,
                        success: function(data) {
                            var invoiceItems = JSON.parse(data);
                            if (invoiceItems != null) {
                                var amount = (invoiceItems.price * invoiceItems.quantity);
                                $(el.parent().parent().find('.quantity')).val(invoiceItems
                                .quantity);
                                $(el.parent().parent().find('.price')).val(invoiceItems.price);
                                $(el.parent().parent().find('.tax')).val(invoiceItems.tax);
                                $(el.parent().parent().find('.discount')).val(invoiceItems
                                .discount);
                                $(el.parent().parent().find('.shipping')).val(invoiceItems
                                .shipping);
                                
                            } else {
                                $(el.parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().find('.price')).val(0);
                                $(el.parent().parent().find('.tax')).val(0);
                                $(el.parent().parent().find('.discount')).val(0);
                                $(el.parent().parent().find('.shipping')).val(0);

                            }
                            var taxes = '';
                            var tax = [];

                            var totalItemTaxRate = 0;
                            for (var i = 0; i < item.taxes.length; i++) {
                                taxes += '<span class="badge badge-pill badge-primary mt-1 mr-1">' +
                                    item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                                    '</span>';
                                tax.push(item.taxes[i].id);
                                totalItemTaxRate += parseFloat(item.taxes[i].rate);
                            }

                            if (invoiceItems != null) {
                                
                                var itemTaxPrice = parseFloat((invoiceItems.tax / 100) * (
                                    invoiceItems.price * invoiceItems.quantity));
                                    
                            } else {
                                var itemTaxPrice = parseFloat((invoiceItems.tax / 100) * (0));
                            }


                            el.parent().parent().parent().find('.itemTaxPrice').val(itemTaxPrice.toFixed(2));

                            $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate
                                .toFixed(2));
                            $(el.parent().parent().find('.taxes')).html(taxes);

                            if (invoiceItems != null) {
                               
                                $(el.parent().parent().parent().find('.amount')).html(amount.toFixed(2));

                            } else {
                                $(el.parent().parent().find('.amount')).html(item.totalAmount);
                            }

                            var inputs = $(".amount");
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                            }
                            $('.subTotal').html(subTotal.toFixed(2));

                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');

                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k]
                                    .value);
                            }

                            var totalItemPrice = 0;
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += parseFloat(priceInput[j].value);
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            //console.log(itemTaxPriceInput);
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                            }

                            var totalItemShippingPrice = 0;
                            var itemShippingPriceInput = $('.shipping');

                            for (var k = 0; k < itemShippingPriceInput.length; k++) {
                                totalItemShippingPrice += parseFloat(itemShippingPriceInput[k]
                                    .value);
                            }
                        
                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(
                                    totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)+parseFloat(totalItemShippingPrice))
                                .toFixed(2));
                            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                            $('.totalShipping').html(totalItemShippingPrice.toFixed(2));
                        }
                    });
                },
            });
        }

        $(document).on('keyup', '.quantity', function() {
            var quntityTotalTaxPrice = 0;

            var el = $(this).parent().parent().parent().parent();
            var quantity = $(this).val();
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();
            var shipping = $(el.find('.shipping')).val();
            var tax = $(el.find('.tax')).val();

            var totalItemPrice = (quantity * price);
            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }

            var totalItemShippingPrice = 0;
            var itemShippingPriceInput = $('.shipping');

            for (var k = 0; k < itemShippingPriceInput.length; k++) {
                totalItemShippingPrice += parseFloat(itemShippingPriceInput[k].value);
            }

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })


        $(document).on('keyup', '.price', function() {

            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();
            var discount = $(el.find('.discount')).val();
            var shipping = $(el.find('.shipping')).val();
            var tax = $(el.find('.tax')).val();

            var totalItemPrice = (quantity * price);

            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }

            var totalItemShippingPrice = 0;
            var itemShippingPriceInput = $('.shipping');

            for (var k = 0; k < itemShippingPriceInput.length; k++) {
                totalItemShippingPrice += parseFloat(itemShippingPriceInput[k].value);
            }



            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })

        $(document).on('keyup', '.tax', function() {
            var el = $(this).parent().parent().parent().parent().parent();
            var tax = $(this).val();
            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();
            var discount = $(el.find('.discount')).val();
            var shipping = $(el.find('.shipping')).val();


            var totalItemPrice = (quantity * price);

            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);


            var totalItemTaxRate = $(el.find('.tax')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemShippingPrice = 0;
            var itemShippingPriceInput = $('.shipping');

            for (var k = 0; k < itemShippingPriceInput.length; k++) {
                totalItemShippingPrice += parseFloat(itemShippingPriceInput[k].value);
            }

            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }

            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })

        $(document).on('keyup', '.discount', function() {
            var el = $(this).parent().parent().parent().parent();
            var discount = $(this).val();
            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();
            var shipping = $(el.find('.shipping')).val();
            var tax = $(el.find('.tax')).val();

            var totalItemPrice = (quantity * price);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }

            var totalItemShippingPrice = 0;
            var itemShippingPriceInput = $('.shipping');

            for (var k = 0; k < itemShippingPriceInput.length; k++) {
                totalItemShippingPrice += parseFloat(itemShippingPriceInput[k].value);
            }


            var amount = (totalItemPrice);
           
            $(el.find('.amount')).html(amount);

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(
                totalItemShippingPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
        })


        $(document).on('keyup', '.shipping', function() {
            var el = $(this).parent().parent().parent().parent();
            var shipping = $(this).val();
            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();
            var discount = $(el.find('.discount')).val();
            var tax = $(el.find('.tax')).val();


            var totalItemPrice = (quantity * price);

            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);


            var totalItemTaxRate = $(el.find('.tax')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemShippingPrice = 0;
            var itemShippingPriceInput = $('.shipping');

            for (var k = 0; k < itemShippingPriceInput.length; k++) {
                totalItemShippingPrice += parseFloat(itemShippingPriceInput[k].value);
            }

            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }

            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
            $('.totalShipping').html(totalItemShippingPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));


            $('.subTotal').html(subTotal.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(
                totalItemShippingPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
        })

        $(document).on('click', '[data-repeater-create]', function() {
            $('.item :selected').each(function() {
                var id = $(this).val();
                $(".item option[value=" + id + "]").prop("disabled", true);
            });
        })
        $('.delete_item').click(function() {
            if (confirm('Are you sure you want to delete this element?')) {
                var el = $(this).parent().parent();
                var id = $(el.find('.id')).val();

                $.ajax({
                    url: '{{ route('cmms_pos.product.destroy') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'id': id
                    },
                    cache: false,
                    success: function(data) {

                    },
                });
            }
        });

        $(document).ready(function() {
            var supplier_id = "{{ $invoice->supplier_id }}";

            $.ajax({
                url: "{{ url('get_parts') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    supplier_id: supplier_id
                },
                success: function(data) {
                    $('#stages_id').empty();
                    $.each(data.parts, function(key, part) {

                        var parts = "{{ $invoice->items[0]->parts_id }}";

                        if (parts == part.id) {
                            var opt =
                                `<option value="${part.id}" selected>${part.name}</option>`;
                        } else {
                            var opt = `<option value="${part.id}" >${part.name}</option>`
                        }

                        $('#parts_id').append(opt);
                    })
                }
            });
        });




        $('#SupplierId').on('change', function(e) {
            var supplier_id = e.target.value;
            $.ajax({
                url: "{{ route('get_parts') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    supplier_id: supplier_id
                },
                success: function(data) {
                    $('#parts_id').empty();
                    $.each(data.parts, function(key, part) {
                        $('#parts_id').append(
                            `<option value="${part.id}">${part.name}</option>`);
                    })
                }
            })
        });
    </script>
@endpush
@section('content')
    <div class="row">
        {{ Form::model($invoice, ['route' => ['cmms_pos.update', $invoice->id], 'method' => 'PUT', 'class' => 'w-100']) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('location', __('Location'),['class'=>'col-form-label']) }}
                                <select name="location" class="form-control select2" id="location_id">
                                    @foreach ($locations as $key => $value)
                                    <option value="{{$key}}" @if($currentLocation == $key) selected @endif> {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class=" col-lg-3">
                            <div class="form-group" id="customer-box">

                                {{ Form::label('supplier_id', __('Supplier'), ['class' => 'col-form-label']) }}


                                {{ Form::select('supplier_id', $Supplier, null, ['class' => 'form-control select2 ', 'required' => 'required', 'id' => 'supplier_id']) }}

                            </div>
                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>

                        <div class=" col-lg-3">
                            <div class="form-group">

                                {{ Form::label('user_id', __('User'), ['class' => 'col-form-label']) }}
                                {{ Form::select('user_id', $user, null, ['class' => 'form-control select2 ', 'required' => 'required']) }}

                            </div>
                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>




                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('po_date', __('Purchase Order Date'), ['class' => 'col-form-label']) }}


                                {{ Form::date('pos_date', null, ['class' => 'form-control datepicker w-100', 'required' => 'required']) }}

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('delivery_date', __('Expected Delivery Date'), ['class' => 'col-form-label']) }}

                                {{ Form::date('delivery_date', null, ['class' => 'form-control datepicker w-100', 'required' => 'required']) }}

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Product & Services') }}</h5>
            <div class="card repeater" data-value='{!! json_encode($invoice->items) !!}'>
                <div class="item-section py-4">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box">
                                <a href="#" data-repeater-create="" class="btn btn-sm btn-primary btn-icon me-3"
                                    data-bs-target="#add-bank">
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">

                    <table class="table mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                        <thead>
                            <tr>
                                <th>{{ __('Items') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Price') }} </th>
                                <th>{{ __('Tax') }} (%)</th>
                                <th>{{ __('Discount') }}</th>
                                <th>{{ __('Shipping') }}</th>

                                <th class="text-right">{{ __('Amount') }} <br><small
                                        class="text-danger font-weight-bold">{{ __('before tax & discount') }}</small></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                {{ Form::hidden('id', null, ['class' => 'form-control id']) }}
                                <td width="25%">
                                    <div class="form-group">
                                        {{ Form::select('item', $parts, null, ['class' => 'form-control item select2', 'id' => 'part_id' , 'data-url'=>route('cmmsPos.product')]) }}

                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        {{ Form::text('description', null, ['class' => 'form-control', 'rows' => '2', 'placeholder' => __('Description')]) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input">
                                        {{ Form::text('quantity', '', ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty'), 'required' => 'required']) }}

                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input">
                                        {{ Form::text('price', '', ['class' => 'form-control price', 'required' => 'required', 'placeholder' => __('Price'), 'required' => 'required']) }}

                                    </div>
                                </td>



                                <td>
                                    <div class="form-group">
                                        <div class="input-group colorpickerinput">
                                            <div class="price-input">
                                                {{ Form::text('tax', '', ['class' => 'form-control tax', 'required' => 'required', 'placeholder' => __('tax'), 'required' => 'required']) }}
                                                {{ Form::hidden('itemTaxPrice', '', ['class' => 'form-control itemTaxPrice']) }}
                                                {{ Form::hidden('itemTaxRate', '', ['class' => 'form-control itemTaxRate']) }}

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input">
                                        {{ Form::text('discount', '', ['class' => 'form-control discount', 'required' => 'required', 'placeholder' => __('Discount')]) }}

                                    </div>
                                </td>

                                <td>
                                    <div class="form-group price-input">
                                        {{ Form::text('shipping', '', ['class' => 'form-control shipping', 'required' => 'required', 'placeholder' => __('Shipping')]) }}

                                    </div>
                                </td>

                                <td class="text-right amount">0.00</td>
                                <td>
                                    <a href="#" class="fas fa-trash text-danger" data-repeater-delete></a>
                                </td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{ __('Sub Total') }} </strong></td>
                                <td class="text-right subTotal">{{ __('0.00') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{ __('Discount') }} </strong></td>
                                <td class="text-right totalDiscount">{{ __('0.00') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{ __('Shipping') }}

                                    </strong></td>
                                <td class="text-right totalShipping">{{ __('0.00') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{ __('Tax') }} </strong></td>
                                <td class="text-right totalTax">{{ __('0.00') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="blue-text"><strong>{{ __('Total Amount') }} </strong></td>
                                <td class="text-right totalAmount blue-text">{{ __('0.00') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-light"
                onclick="location.href ='{{ route('cmms_pos.index') }}'">{{ __('Close') }}</button>
            {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
        </div>
        {{ Form::close() }}
    </div>
@endsection
