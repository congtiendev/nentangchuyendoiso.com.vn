{{ Form::model($procedure, ['route' => ['procedures.update', $procedure->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'document','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Tên quy trình'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Tên quy trình')]) }}
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                {{ Form::label('procedure_type', __('Loại quy trình'), ['class' => 'form-label']) }}
                <select name="procedure_type" id="procedure_type" class="form-control">
                    <option value="">-- Chọn loại quy trình --</option>
                    @foreach ($procedure_types as $procedure_type)
                        <option value="{{ $procedure_type->id }}" @if ($procedure_type->id == $procedure->procedure_type) selected @endif>{{ $procedure_type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
