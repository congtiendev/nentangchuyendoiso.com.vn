{{ Form::open(['url' => 'manager-file/store', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('name', __('Tên hồ sơ'),['class' => 'col-form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control','required'=>'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('name', __('Tên hồ sơ'),['class' => 'col-form-label']) }}
                <select name="type" class="form-control font-style">
                    <option value="">Chọn loại hồ sơ</option>
                    <option value="0">Hồ sơ mượn LĐ</option>
                    <option value="1">Hồ sơ mượn DC</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
