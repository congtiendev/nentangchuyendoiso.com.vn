<link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets//dropzone/dist/dropzone.css') }}">

{{ Form::open(['route' => ['wos.componentsupdate']]) }}
<div class="modal-body">

<div class="row">
    <input name="wo_id" value="{{ $id }}" type="hidden">

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('component_id', __('Components'), ['class' => 'col-form-label']) }}
            {{ Form::select('component_id', $component, $id, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>
</div>
{{ Form::close() }}
