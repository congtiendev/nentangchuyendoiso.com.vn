{{ Form::open(['url' => 'procedures/store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'document','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control','required' => 'required', 'placeholder' => __('Nhập tên quy trình')]) }}
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                {{ Form::label('procedure_type', __('Loại quy trình'), ['class' => 'form-label']) }}
                {{ Form::select('procedure_type', $procedure_types, null, ['class' => 'form-control ','required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary submit']) }}
</div>
{{ Form::close() }}
<script>
     $(".submit").click(function() {
            var documents = $('.doc_data').val();
            if(!isNaN(documents)) {
                    $('#doc_validation').removeClass('d-none')
                    return false;
            }else{
                $('#doc_validation').addClass('d-none')
            }
        });
</script>
