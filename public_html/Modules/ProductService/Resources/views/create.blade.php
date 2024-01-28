{{ Form::open(['route' => 'product-service.store', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'product','module'=>'ProductService'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sku', __('Đơn vị trình ký'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::text('sku', '', ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('description', __('Ý kiến của lãnh đạo trình ký'), ['class' => 'form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sale_price', __('Giá bán'), ['class' => 'form-label']) }}<span
                    class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::number('sale_price', '', ['class' => 'form-control', 'required' => 'required', 'step' => '0.01']) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('purchase_price', __('Giá mua'), ['class' => 'form-label']) }}<span
                    class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::number('purchase_price', '', ['class' => 'form-control', 'required' => 'required', 'step' => '0.01']) }}
                </div>
            </div>
        </div> --}}
        {{-- @stack('add_column_in_productservice') --}}

        {{-- <div class="form-group col-md-6">
            {{ Form::label('tax_id', __('Tax'), ['class' => 'form-label']) }}
            {{ Form::select('tax_id[]', $tax, null, ['class' => 'form-control choices tax_data', 'id' => 'choices-multiple1', 'multiple' , 'required' => 'required']) }}
            <p class="text-danger d-none" id="tax_validation">{{ __('Tax filed is required.') }}</p>
        </div> --}}
        <div class="form-group col-md-6 mt-3">
            {{ Form::label('category_id', __('Loại văn bản'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('category_id', $category, null, ['class' => 'form-control', 'required' => 'required']) }}

            <div class=" text-xs">
                {{ __('Please add constant category. ') }}<a
                    href="{{ route('category.index') }}"><b>{{ __('Add Category') }}</b></a>
            </div>
        </div>
        {{-- <div class="form-group col-md-6">
            {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('unit_id', $unit, null, ['class' => 'form-control', 'required' => 'required']) }}
        </div> --}}

        <div class="col-6 form-group">
            {{ Form::label('image', __('Tải tệp'), ['class' => 'col-form-label']) }}
                    <input type="file" class="form-control" name="image"
                        data-filename="image_update"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
        </div>

        <div hidden class="col-md-6">
            <div class="form-group">
                <div class="btn-box">
                    <label class="d-block form-label">{{ __('Type') }}</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio5" name="type" value="product" checked="checked" >
                                <label class="custom-control-label form-label" for="customRadio5">{{__('Product')}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio6" name="type" value="service" >
                                <label class="custom-control-label form-label" for="customRadio6">{{__('Dịch vụ')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div hidden class="form-group col-md-6 quantity">
            {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
            {{ Form::number('quantity', 1000000, ['class' => 'form-control', 'min'=>'0']) }}
        </div>
        @if(module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder')
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
    //hide & show quantity

    $(document).on('click', '.type', function ()
    {
        var type = $(this).val();
        if (type == 'product') {
            $('.quantity').removeClass('d-none')
            $('.quantity').addClass('d-block');
        } else {
            $('.quantity').addClass('d-none')
            $('.quantity').removeClass('d-block');
        }
    });
    $("#submit").click(function() {
        var skill = $('.tax_data').val();
        if (skill == '') {
            $('#tax_validation').removeClass('d-none')
            return false;
        } else {
            $('#tax_validation').addClass('d-none')
        }
    });
</script>

