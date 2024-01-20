{{ Form::open(['route' => ['component.associate', [$module, $id]], 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                @if ($module == 'parts_component')
                {{ Form::label('associate_parts', __('Associate Parts'), ['class' => 'col-form-label']) }}
                @elseif($module == 'pms')
                    {{ Form::label('pms', __('PMs'), ['class' => 'col-form-label']) }}
                @elseif($module == 'suppliers')
                    {{ Form::label('associate_components', __('Associate Components'), ['class' => 'col-form-label']) }}
                @endif
                    {{ Form::select('associate_parts[]', $components, null, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}

            </div>
        </div>
    </div>
    <div class="row justify-content-between align-items-center">
        <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-start">
            @if ($module == 'parts_component')
                @if (Laratrust::hasPermission('components create'))
                    <a href="#" class="btn btn-primary"
                        data-url="{{ route('component.create', ['parts_id' => $parts_id]) }}" data-size="lg"
                        data-bs-whatever="{{ __('Create Components') }}" data-ajax-popup="true"
                        data-title="{{ __('Create Components') }}">{{ __('Create Components') }}</a>

                @endif
            @endif
            @if ($module == 'suppliers')
                @if (Laratrust::hasPermission('components create'))
                    <a href="#" class="btn btn-primary"
                    data-url="{{  route('component.create', ['supplier_id' => $supplier_id]) }}" data-size="lg"
                     data-ajax-popup="true" data-toggle="tooltip"
                    data-bs-original-title="{{ __('Create Components') }}">{{ __('Create Components') }}</a>
                @endif
            @endif
        </div>
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Associate'), ['class' => 'btn btn-primary']) }}
    </div>
</div>
{{ Form::close() }}

<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script>
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function(index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
</script>
