    {{ Form::open(['route' => ['wosinvoice.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'wos_logtime','module'=>'CMMS'])
            @endif
        </div>
        <input type="hidden" name="wo_id" value="{{ $wo_id }}">
        <div class="row">
            <div class="col-md-6 form-group">
                {{ Form::label('invoice_cost', __('Invoice Cost'), ['class' => 'col-form-label']) }}
                {{ Form::number('invoice_cost', null, ['class' => 'form-control', 'placeholder' => __('Enter Invoice Cost'), 'required' => 'required', 'stap' => 'any']) }}
            </div>

            <div class="col-md-6 form-group">
                {{ Form::label('invoive', __('Attach Invoice'), ['class' => 'col-form-label']) }}
                <div class="choose-file">
                    <input type="file" class="form-control" name="invoice" id="invoice" data-filename="invoice"
                        accept="image/*,.jpeg,.jpg,.png,.pdf" required="required"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                    <img id="blah" width="25%" />
                </div>
            </div>

            <div class="col-md-12 form-group">
                {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                {{ Form::text('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required']) }}
            </div>
        </div>
    </div>

        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
        </div>
    {{ Form::close() }}
