{{ Form::model($pmsinvoice, ['route' => ['pmsinvoice.update', $pmsinvoice->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', [
                'template_module' => 'pms_invoice',
                'module' => 'CMMS',
            ])
        @endif
    </div>
    <input type="hidden" name="pms_id" value="{{ $pmsinvoice->pms_id }}">
    <div class="row">
        <div class="col-md-6 form-group">
            {{ Form::label('invoice_cost', __('Invoice Cost'), ['class' => 'col-form-label']) }}
            {{ Form::text('invoice_cost', null, ['class' => 'form-control', 'placeholder' => __('Enter Invoice Cost'), 'required' => 'required']) }}
        </div>
        <div class="col-md-6 mt-2 form-group">
            {{ Form::label('invoive_cost', __('Attach Invoice'), ['class' => 'form-control-label']) }}
            <div class="choose-file mt-2 pb-3">
                <label for="Invoice">
                    <input type="file" class="form-control" name="invoice" id="invoice" data-filename="invoice"
                        accept="image/*,.jpeg,.jpg,.png,.pdf"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                    <img id="blah" class="mt-2" src="{{ get_file($pmsinvoice->invoice_file) }}"
                        style="width:25%;" />

                </label>
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
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>


{{ Form::close() }}
