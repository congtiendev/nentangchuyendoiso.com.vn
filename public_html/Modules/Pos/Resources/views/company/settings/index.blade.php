<div class="card" id="pos-sidenav">
    {{ Form::open(array('route' => 'pos.setting.store')) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('POS Settings') }}</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="low_product_stock_threshold" class="form-label">{{ __('Low Product Stock Threshold') }}</label>
                     <input type="number" name="low_product_stock_threshold" class="form-control" placeholder="{{ __('Low Product Stock Threshold')}}"   value="{{ !empty($settings['low_product_stock_threshold']) ? $settings['low_product_stock_threshold'] : '' }}" id="low_product_stock_threshold">
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{Form::close()}}
</div>
<div id="purchase-print-sidenav" class="card">
    <div class="card-header">
        <h5>{{ __('Purchase Print Settings') }}</h5>
        <small class="text-muted">{{ __('Edit details about your Company Bill') }}</small>
    </div>
    <div class="bg-none">
        <div class="row company-setting">
            <form id="setting-form" method="post" action="{{route('purchase.template.setting')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-header card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::label('purchase_prefix',__('Prefix'),array('class'=>'form-label')) }}
                                {{Form::text('purchase_prefix',isset($settings['purchase_prefix']) && !empty($settings['purchase_prefix']) ? $settings['purchase_prefix'] :'#PUR',array('class'=>'form-control', 'placeholder' => 'Enter Purchase Prefix'))}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::label('purchase_footer_title',__('Footer Title'),array('class'=>'form-label')) }}
                                {{Form::text('purchase_footer_title',isset($settings['purchase_footer_title']) && !empty($settings['purchase_footer_title']) ? $settings['purchase_footer_title'] :'',array('class'=>'form-control', 'placeholder' => 'Enter Footer Title'))}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::label('purchase_footer_notes',__('Footer Notes'),array('class'=>'form-label')) }}
                                {{Form::textarea('purchase_footer_notes',isset($settings['purchase_footer_notes']) && !empty($settings['purchase_footer_notes']) ? $settings['purchase_footer_notes'] : '',array('class'=>'form-control','rows'=>'1' ,'placeholder' => 'Enter Purchase Footer Notes'))}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-2">
                                {{Form::label('purchase_shipping_display',__('Shipping Display?'),array('class'=>'form-label')) }}
                                <div class=" form-switch form-switch-left">
                                    <input type="checkbox" class="form-check-input" name="purchase_shipping_display" id="purchase_shipping_display" {{ isset($settings['purchase_shipping_display']) && ($settings['purchase_shipping_display']=='on')?'checked':''}} >
                                    <label class="form-check-label" for="purchase_shipping_display"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header card-body">
                            <div class="form-group">
                                {{Form::label('purchase_template',__('Template'),array('class'=>'form-label')) }}
                                {{ Form::select('purchase_template',\Modules\Pos\Entities\Pos::templateData()['templates'],isset($settings['purchase_template']) && !empty($settings['purchase_template']) ? $settings['purchase_template'] : null, array('class' => 'form-control ','required'=>'required')) }}
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('Color Input')}}</label>
                                <div class="row gutters-xs">
                                    @foreach(\Modules\Pos\Entities\Pos::templateData()['colors'] as $key => $color)
                                        <div class="col-auto">
                                            <label class="colorinput">
                                                <input name="purchase_color" type="radio" value="{{$color}}" class="colorinput-input" {{isset($settings['purchase_color']) && (!empty($settings['purchase_color']) && $settings['purchase_color'] == $color) ? 'checked' : ''}}>
                                                <span class="colorinput-color" style="background: #{{$color}}"></span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('Logo')}}</label>
                                <div class="choose-files mt-3">
                                    <label for="purchase_logo">
                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <img id="blah7" class="mt-3" src=""  width="70%"  />
                                        <input type="file" class="form-control file" name="purchase_logo" id="purchase_logo" data-filename="purchase_logo">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-2 text-end">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if(isset($settings['purchase_template']) && isset($settings['purchase_color']) && !empty( $settings['purchase_template']) && !empty($settings['purchase_color']))
                            <iframe id="purchase_frame" class="w-100 h-100" frameborder="0" src="{{route('purchase.preview',[$settings['purchase_template'], $settings['purchase_color']])}}"></iframe>
                        @else
                            <iframe id="purchase_frame" class="w-100 h-100" frameborder="0" src="{{route('purchase.preview',['template1','fffff'])}}"></iframe>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="pos-print-sidenav" class="card">
    <div class="card-header">
        <h5>{{ __('Pos Print Settings') }}</h5>
        <small class="text-muted">{{ __('Edit details about your Company Bill') }}</small>
    </div>
    <div class="bg-none">
        <div class="row company-setting">
            <form id="setting-form" method="post" action="{{route('pos.template.setting')}}" enctype="multipart/form-data">
                @csrf
                <div class="row ms-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('pos_prefix',__('Prefix'),array('class'=>'form-label')) }}
                            {{Form::text('pos_prefix',isset($settings['pos_prefix']) && !empty($settings['pos_prefix']) ? $settings['pos_prefix'] :'#PUR',array('class'=>'form-control', 'placeholder' => 'Enter Pos Prefix'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('pos_footer_title',__('Footer Title'),array('class'=>'form-label')) }}
                            {{Form::text('pos_footer_title',isset($settings['pos_footer_title']) && !empty($settings['pos_footer_title']) ? $settings['pos_footer_title'] :'',array('class'=>'form-control', 'placeholder' => 'Enter Footer Title'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('pos_footer_notes',__('Footer Notes'),array('class'=>'form-label')) }}
                            {{Form::textarea('pos_footer_notes',isset($settings['pos_footer_notes']) && !empty($settings['pos_footer_notes']) ? $settings['pos_footer_notes'] : '',array('class'=>'form-control','rows'=>'1' ,'placeholder' => 'Enter Pos Footer Notes'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-2">
                            {{Form::label('pos_shipping_display',__('Shipping Display?'),array('class'=>'form-label')) }}
                            <div class=" form-switch form-switch-left">
                                <input type="checkbox" class="form-check-input" name="pos_shipping_display" id="pos_shipping_display" {{ isset($settings['pos_shipping_display']) && ($settings['pos_shipping_display']=='on')?'checked':''}} >
                                <label class="form-check-label" for="pos_shipping_display"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header card-body">
                            <div class="form-group">
                                <label for="address" class="form-label">{{__('POS Template')}}</label>
                                <select class="form-control" name="pos_template">
                                    @foreach(\Modules\Pos\Entities\Pos::templateData()['templates'] as $key => $template)
                                        <option value="{{$key}}" {{(isset($settings['pos_template']) && !empty($settings['pos_template']) && $settings['pos_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('Color Input')}}</label>
                                <div class="row gutters-xs">
                                    @foreach(\Modules\Pos\Entities\Pos::templateData()['colors'] as $key => $color)
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="pos_color" type="radio" value="{{$color}}" class="colorinput-input" {{(!empty($settings['pos_color']) && $settings['pos_color'] == $color) ? 'checked' : ''}}>
                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                        </label>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('Logo')}}</label>
                                <div class="choose-files mt-2 ">
                                    <label for="pos_logo">
                                        <div class=" bg-primary pos_logo_update"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                        <input type="file" class="form-control file" name="pos_logo" id="pos_logo" data-filename="pos_logo_update">
                                        <img id="pos_image" class="mt-2" style="width:25%;"/>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-2 text-end">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if(isset($settings['pos_template']) && isset($settings['pos_color']) && !empty($settings['pos_template']) && !empty($settings['pos_color']))
                            <iframe id="pos_frame" class="w-100 h-100" frameborder="0" src="{{route('pos.preview',[$settings['pos_template'],$settings['pos_color']])}}"></iframe>
                        @else
                            <iframe id="pos_frame" class="w-100 h-100" frameborder="0" src="{{route('pos.preview',['template1','fffff'])}}"></iframe>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

    $(document).on("change", "select[name='purchase_template'], input[name='purchase_color']", function () {
        var template = $("select[name='purchase_template']").val();
        var color = $("input[name='purchase_color']:checked").val();
        $('#purchase_frame').attr('src', '{{url('/purchase/preview')}}/' + template + '/' + color);
    });
    document.getElementById('purchase_logo').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('purchase_image').src = src
    }

    $(document).on("change", "select[name='pos_template'], input[name='pos_color']", function () {
            var template = $("select[name='pos_template']").val();
            var color = $("input[name='pos_color']:checked").val();
            $('#pos_frame').attr('src', '{{url('/pos/preview')}}/' + template + '/' + color);
        });

        document.getElementById('pos_logo').onchange = function () {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('pos_image').src = src
        }

</script>