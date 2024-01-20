@extends('layouts.main')
@section('page-title')
    {{ __('Create Purchase Orders') }}
@endsection
@section('page-breadcrumb')
    {{ __('POs') }}
@endsection



@section('page-breadcrumb')
    {{ __('POs Create') }}
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
                initEmpty: false,
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

                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
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
                    'id': id,
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

            var iteams_id = $(this).val();

            var url = $(this).data('url');
            var el = $(this);
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function(data) {
                    var item = JSON.parse(data);

                    $(el.parent().parent().find('.quantity')).val(1);
                    $(el.parent().parent().find('#price')).val(item.product.price);
                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;

                    if (item.taxes == 0) {
                        taxes += '-';
                    } else {
                        for (var i = 0; i < item.taxes.length; i++) {
                            taxes += '<span class="badge badge-pill badge-primary mt-1 mr-1">' + item
                                .taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                            tax.push(item.taxes[i].id);
                            totalItemTaxRate += parseFloat(item.taxes[i].rate);
                        }
                    }
                    var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.product.sale_price *
                        1));
                    $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                    $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                    $(el.parent().parent().find('.taxes')).html(taxes);
                    $(el.parent().parent().find('.tax')).val(tax);
                    $(el.parent().parent().find('.unit')).html(item.unit);
                    $(el.parent().parent().find('.discount')).val(0);
                    $(el.parent().parent().find('.shipping')).val(0);

                    $(el.parent().parent().find('.amount')).html(item.totalAmount);


                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));


                    var totalItemPrice = 0;
                    var priceInput = $('.price');
                    for (var j = 0; j < priceInput.length; j++) {
                        totalItemPrice += parseFloat(priceInput[j].value);
                    }

                    var totalItemTaxPrice = 0;
                    var itemTaxPriceInput = $('.itemTaxPrice');
                    for (var j = 0; j < itemTaxPriceInput.length; j++) {
                        totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                    }

                    $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                    $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice))
                        .toFixed(2));

                },
            });
        });

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
            var el = $(this).parent().parent().parent().parent();
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

            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

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

            var totalItemTaxRate = $(el.find('.tax')).val();
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
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));


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

        var customerId = '{{ $customerId }}';
        if (customerId > 0) {
            $('#customer').val(customerId).change();
        }

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
                    $('#parts_id').append(
                            `<option value="0" selected disabled>Select Part</option>`);
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
        {{ Form::open(['route' => ['cmms_pos.store'], 'enctype' => 'multipart/form-data', 'class' => 'w-100']) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="partsid" value="{{ $partsid }}">
            <input type="hidden" name="wo_id" value="{{ $wo_id }}">
            <input type="hidden" name="SupplierId" value="{{ $supplier_id }}">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('location', __('Location'),['class'=>'form-label']) }}
                                <select name="location" class="form-control select2" id="location_id" required>
                                    @foreach ($locations as $key => $value)
                                    <option value="{{$key}}" @if($currentLocation == $key) selected @endif> {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class=" col-lg-3">
                            <div class="form-group" id="customer-box">

                                {{ Form::label('supplier_id', __('Supplier'), ['class' => 'form-label']) }}
                                {{ Form::select('supplier_id', $Supplier, null, ['class' => 'form-control select2 ',  'required' => 'required', 'id' => 'supplier_id']) }}

                            </div>
                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('user_id', __('User'), ['class' => 'form-label']) }}
                                {{ Form::select('user_id', $User, null, ['class' => 'form-control select2 ', 'required' => 'required']) }}
                            </div>
                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                {{ Form::label('po_date', __('Purchase Order Date'), ['class' => 'form-label']) }}
                                {{ Form::date('pos_date', '', ['class' => 'form-control datepicker w-100', 'required' => 'required']) }}

                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                {{ Form::label('delivery_date', __('Expected Delivery Date'), ['class' => 'form-label']) }}
                                {{ Form::date('delivery_date', '', ['class' => 'form-control datepicker w-100', 'required' => 'required']) }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Parts & Services') }}</h5>
            <div class="card repeater">
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
                
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Items') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    

                                  
                                    <th></th>
                                </tr>
                            </thead>
                            
                            <tbody class="ui-sortable" data-repeater-item>
                                <tr>
                                    <td>
                                    {{ Form::select('item', $Parts,null, array('class' => 'form-group form-control select2 item','required'=>'required','id'=>'part_id', 'placeholder' => 'Select Item' , 'style' => 'width: 184px;')) }}
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            {{ Form::text('description', 'null', ['class' => 'form-control', 'rows' => '2', 'placeholder' => __('Description')]) }}
                                        </div>
                                    </td>

                                    
                                </tr>

                            </tbody>
                            @php
                                $site_currency_symbol_position = isset($setting['site_currency_symbol_position']) ? $setting['site_currency_symbol_position'] : 'pre';
                            @endphp
                          
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-light"
                onclick="location.href ='{{ route('cmms_pos.index') }}'">{{ __('Close') }}</button>
            {{ Form::submit(__('Create'), ['class' => 'btn btn-primary']) }}
        </div>

        {{ Form::close() }}
    </div>
@endsection
