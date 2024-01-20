<link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets//dropzone/dist/dropzone.css') }}">


{{ Form::open(['route' => ['workorder.store'], 'id' => 'assets_store', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', [
                'template_module' => 'workorder',
                'module' => 'CMMS',
            ])
        @endif
    </div>

    <div class="row">
        <input name="_token" value="{{ csrf_token() }}" type="hidden">

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('wo_name', __('WO Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('wo_name', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>

        @php($prioritys = Modules\CMMS\Entities\Workorder::priority())

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('priority', __('Priority'), ['class' => 'col-form-label']) }}
                <select name="priority" class="form-control select2">
                    @foreach ($prioritys as $priority)
                        <option value="{{ $priority['priority'] }}">{{ __($priority['priority']) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('location', __('Location'), ['class' => 'col-form-label']) }}
                <select name="location" class="form-control select2" id='location_id' required>
                    @foreach ($locations as $key => $value)
                        <option value="{{ $key }}" @if ($currentLocation == $key) selected @endif>
                            {{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        @if ($components_id == 0)
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('components', __('Components'), ['class' => 'col-form-label']) }}
                    {{ Form::select('components', $Components, null, ['class' => 'form-control select2', 'required' => 'required', 'id' => 'component_id']) }}
                </div>
            </div>
        @elseif($components_id != 0)
            {{ Form::hidden('components', $components_id, ['class' => 'form-control', 'required' => 'required']) }}
        @endif

        @if (Auth::user()->type != 'company')
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Due Date'), ['class' => 'col-form-label']) }}
                    {{ Form::date('date', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('time', __('Time'), ['class' => 'col-form-label']) }}
                    {{ Form::time('time', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        @else
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Due Date'), ['class' => 'col-form-label']) }}
                    {{ Form::date('date', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('time', __('Time'), ['class' => 'col-form-label']) }}
                    {{ Form::time('time', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        @endif

        @if (Auth::user()->type == 'company')
            <div class="form-group">
                {{ Form::label('user', __('User'), ['class' => 'col-form-label']) }}
                {{ Form::select('user[]', $user, null, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
            </div>
    </div>
    @endif

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('instructions', __('Instructions'), ['class' => 'col-form-label']) }}
            {{ Form::text('instructions', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('tags', __('Tags'), ['class' => 'col-form-label']) }}
            {{ Form::text('tags', null, ['class' => 'form-control', 'id' => 'choices-text-remove-button']) }}
        </div>
    </div>
    
    @if (module_is_active('Calender') && company_setting('google_calendar_enable') == 'on')
        @include('calender::setting.synchronize')
    @endif
</div>


</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
</div>

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

    var textRemove = new Choices(
        document.getElementById('choices-text-remove-button'), {
            delimiter: ',',
            editItems: true,
            maxItemCount: 5,
            removeItemButton: true,
        }
    );
</script>

<script>
    @if (module_is_active('CMMS'))
        $(document).on('change', 'select[name=location]', function() {
            var location_id = $(this).val();
            getcomponent(location_id);
        });

        function getcomponent(did) {
            $.ajax({
                url: '{{ route('getcomponent') }}',
                type: 'POST',
                data: {
                    "location_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#component_id').empty();
                    $('#component_id').append();
                    $.each(data, function(key, value) {
                        $('#component_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }
    @endif
</script>
