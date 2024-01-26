<link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets//dropzone/dist/dropzone.css') }}">
{{ Form::open(['route' => ['component.update', $Components->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
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
                {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('name', $Components->name, ['class' => 'form-control', 'required' => 'required']) }}
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sku', __('SKU'), ['class' => 'col-form-label']) }}
                {{ Form::text('sku', $Components->sku, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('location', __('Location'),['class'=>'col-form-label']) }}
                <select name="location" class="form-control select2">
                    @foreach ($location as $key => $value)
                    <option value="{{$key}}" @if($Components->location_id == $key) selected @endif> {{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
            {{ Form::label('thumbnail', __('Component Thumbnail'), ['class' => 'col-form-label']) }}
                    <input type="file" class="form-control" name="thumbnail" id="thumbnail"
                        accept="image/*,.jpeg,.jpg,.png"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                    <img id="blah" class="mt-2" src="{{ get_file($Components->thumbnail) }}"
                        style="width:25%;" />
        </div>
    </div>
        {{-- @if (count($ComponentsField) > 0)
            @foreach ($ComponentsField as $key => $value)
                <div class="col-md-6">
                    <div class="form-group">
                        @if ($value->type != 'multiple_files/document')
                            @php $field_name = $value->name.'['.$value->name.']'; @endphp
                            @php
                                if (array_key_exists($value->name, $ComponentsFieldValues)) {
                                    $fildval = $ComponentsFieldValues[$value->name];
                                } else {
                                    $fildval = null;
                                }
                            @endphp

                            {{ Form::label($field_name, __($value->name), ['class' => 'col-form-label']) }}
                            @if ($value->type == 'text')
                                {{ Form::text($field_name, $fildval, ['id' => $value->name, 'class' => 'form-control']) }}
                            @elseif($value->type == 'date')
                                {{ Form::date($field_name, $fildval, ['class' => 'form-control']) }}
                            @elseif($value->type == 'time')
                                {{ Form::time($field_name, $fildval, ['class' => 'form-control']) }}
                            @elseif($value->type == 'number')
                                {{ Form::number($field_name, $fildval, ['class' => 'form-control']) }}
                            @elseif($value->type == 'dropdown')
                                {{ Form::select($field_name, [], $fildval, ['class' => 'form-control']) }}
                            @elseif($value->type == 'file/document')

                                        <input type="file" class="form-control" name="{{ $field_name }}"
                                            id="document{{ $key }}"
                                            data-filename="document{{ $key }}"
                                            accept="image/*,.jpeg,.jpg,.png,.pdf,.doc,.txt,.xls,.csv">
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @endif --}}
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
