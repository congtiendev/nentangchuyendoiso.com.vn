{{ Form::open(['route' => ['parts.associate', [$module, $id]], 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                @if ($module == 'parts')
                    {{ Form::label('associate_parts', __('Associate Parts'), ['class' => 'col-form-label']) }}
                @elseif($module == 'pms')
                    {{ Form::label('pms', __('PMs'), ['class' => 'col-form-label']) }}
                @endif
                {{ Form::select('associate_parts[]', $parts, null, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}

            </div>
        </div>
    </div>
    <div class="row justify-content-between align-items-center">
        <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-start">
            <div class="d-inline-block">
                <!--Asset detail page in parts associate for create parts -->
                @if ($module == 'parts')
                    @permission('parts create')
                        <a  class="btn btn-primary text-white" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Part') }}" 
                        data-url="{{ route('parts.create', ['components_id' => $id])}}" data-toggle="tooltip" title="{{ __('Create') }}">
                            {{ __('Create Part') }}
                        </a>
                    @endpermission
                    <!--Asset detail page in pms associate for create pms -->
                @elseif($module == 'pms')
                    @permission('pms create')
                        <a  class="btn btn-primary text-white" data-ajax-popup="true" data-size="md" data-title="{{ __('Create PMs') }}" 
                        data-url="{{route('pms.create', ['components_id' => $id])}}" data-toggle="tooltip" title="{{ __('Create') }}">
                            {{ __('Create PMs') }}
                        </a>
                    @endpermission

                    <!--pms detail page in parts associate for create parts -->
                @elseif($module == 'pms_parts')
                    @permission('parts create')
                        <a  class="btn btn-primary text-white" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Parts') }}" 
                        data-url="{{route('parts.create', ['pms_id' => $pms_id])}}" data-toggle="tooltip" title="{{ __('Create') }}">
                            {{ __('Create Parts') }}
                        </a>
                    @endpermission
                    <!-- vendors detail page in associate parts for create parts-->
                @elseif($module == 'suppliers')
                    @permission('parts create')
                        <a  class="btn btn-primary text-white" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Parts') }}" 
                        data-url="{{route('parts.create', ['supplier_id' => $data_id]) }}" data-toggle="tooltip" title="{{ __('Create') }}">
                            {{ __('Create Parts') }}
                        </a>
                    @endpermission
                    <!-- work order detail page in associate parts for create parts -->
                @elseif($module == 'workorder')
                        <a  class="btn btn-primary text-white" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Parts') }}" 
                        data-url="{{route('parts.create', ['workorder_id' => $data_id])}}" data-toggle="tooltip" title="{{ __('Create') }}">
                            {{ __('Create Parts') }}
                        </a>
                @endif
            </div>
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
