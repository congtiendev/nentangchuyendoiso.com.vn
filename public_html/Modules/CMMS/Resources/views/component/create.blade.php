<link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets//dropzone/dist/dropzone.css') }}">
{{ Form::open(['route' => ['component.store'], 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            <a class="btn btn-primary text-white btn-sm" data-size="lg" data-ajax-popup-over="true"
                data-url="{{ route('cmms_aiassistant.generate', ['components', 'CMMS']) }}" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
            </a>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Tên'), ['class' => 'col-form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input type="hidden" name="parts_id" value="{{ $parts_id }}">
                <input type="hidden" name="supplier_id" value="{{ $supplier_id }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sku', __('Mã'), ['class' => 'col-form-label']) }}
                {{ Form::text('sku', '', ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('location', __('Vị trí'), ['class' => 'col-form-label']) }}
                <select name="location" class="form-control select2" required>
                    @foreach ($locations as $key => $value)
                        <option value="{{ $key }}" @if ($currentLocation == $key) selected @endif>
                            {{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('thumbnail', __('Hình ảnh'), ['class' => 'col-form-label']) }}
                <input type="file" class="form-control" name="thumbnail" id="thumbnail" data-filename="thumbnail"
                    accept="image/*,.jpeg,.jpg,.png" required="required"
                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                <img src="" id="blah" style="width:25%;" class="mt-3">
            </div>

        </div>

        @if (count($ComponentsField) > 0)
       <!--     @foreach ($ComponentsField as $key => $value)
                <div class="col-md-6">
                    <div class="form-group">
                        @if ($value->type != 'multiple_files/document')
                            @php $field_name = $value->name.'['.$value->name.']'; @endphp
                            {{ Form::label($field_name, $value->name == 'Component_Tag' ? 'Component Tag' : __($value->name), ['class' => 'col-form-label']) }}
                            @if ($value->type == 'text')
                                {{ Form::text($field_name, null, ['id' => $value->name, 'class' => 'form-control ']) }}
                            @elseif($value->type == 'date')
                                {{ Form::date($field_name, null, ['class' => 'form-control']) }}
                            @elseif($value->type == 'time')
                                {{ Form::time($field_name, null, ['class' => 'form-control']) }}
                            @elseif($value->type == 'number')
                                {{ Form::number($field_name, null, ['class' => 'form-control']) }}
                            @elseif($value->type == 'dropdown')
                                {{ Form::select($field_name, [], null, ['class' => 'form-control']) }}
                            @elseif($value->type == 'file/document')
                                <label for="document{{ $key }}">
                                </label>
                                <input type="file" class="form-control" name="{{ $field_name }}"
                                    id="document{{ $key }}" data-filename="document{{ $key }}"
                                    accept="image/*,.jpeg,.jpg,.png,.pdf,.doc,.txt,.xls,.csv"
                                    onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
                                <img id="blah1" width="25%" />
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach -->
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}
