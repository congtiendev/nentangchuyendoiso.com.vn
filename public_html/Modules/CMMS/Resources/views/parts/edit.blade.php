{{ Form::open(['route' => ['parts.update', $parts->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', ['template_module' => 'parts', 'module' => 'CMMS'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('name', $parts->name, ['class' => 'form-control', 'required' => 'required']) }}
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('location', __('Location'), ['class' => 'col-form-label']) }}
                <select name="location" class="form-control select2">
                    @foreach ($location as $key => $value)
                        <option value="{{ $key }}" @if ($parts->location_id == $key) selected @endif>
                            {{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('thumbnail', __('Part Thumbnail'), ['class' => 'col-form-label']) }}
                <div class="choose-file">
                    <label for="Component Thumbnail">
                        <input type="file" class="form-control" name="thumbnail" id="thumbnail"
                            data-filename="thumbnail" accept="image/*,.jpeg,.jpg,.png"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            style="width:460px;">
                        <img id="blah" class="mt-2" src="{{ asset(get_file($parts->thumbnail)) }}"
                            style="width:20%;" class="mt-3">
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('number', __('Part Number'), ['class' => 'col-form-label']) }}
                {{ Form::text('number', $parts->number, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('quantity', __('Part Quantity'), ['class' => 'col-form-label']) }}
                {{ Form::number('quantity', $parts->quantity, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('price', __('Price'), ['class' => 'col-form-label']) }}
                {{ Form::number('price', $parts->price, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('category', __('Category'), ['class' => 'col-form-label']) }}
                {{ Form::text('category', $parts->category, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
