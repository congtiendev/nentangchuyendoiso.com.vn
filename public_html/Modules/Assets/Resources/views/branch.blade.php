<div class="form-group col-md-6">
    {{ Form::label('branch', __('Chi nhánh'), ['class' => 'form-label']) }}
    {{ Form::select('branch', $branches,null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Branch']) }}
</div>

