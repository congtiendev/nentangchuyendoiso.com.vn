{{ Form::open(['route' => ['pms.store'], 'method' => 'post']) }}

<input type="hidden" name="components_id" value="{{ $components_id }}">
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'pms','module'=>'CMMS'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('location', __('Location'),['class'=>'col-form-label']) }}
                    <select name="location" class="form-control select2" id="location_id" required>
                        @foreach ($locations as $key => $value)
                        <option value="{{$key}}" @if($currentLocation == $key) selected @endif> {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        <div class="col-md-12 form-group">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'row' => 3]) }}
        </div>
        <div class="form-group col-md-12 switch-width">
            {{ Form::label('parts', __('Parts'), ['class' => 'col-form-label']) }}
            <div class="part_div">
            {{ Form::select('parts[]', $parts, null, ['class' => 'form-control multi-select', 'id' => 'part_id', 'multiple' => '', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('tags', __('Tags'), ['class' => 'col-form-label']) }}
                {{ Form::text('tags', null, ['class' => 'form-control', 'id' => 'choices-text-remove-button']) }}
            </div>
        </div>
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
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
            getparts(location_id);
        });

        function getparts(did) {
            $.ajax({
                url: '{{ route('getparts') }}',
                type: 'POST',
                data: {
                    "location_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                   $('.part_id').empty();
                    var part_select = ` <select class="form-control  part_id" name="parts[]" id="choices-multiple"
                                            placeholder="Select Department" multiple >
                                            </select>`;
                    $('.part_div').html(part_select);

                    $('.part_id').append();
                    $.each(data, function(key, value) {
                        $('.part_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });

                    
                }
            });
        }
    @endif
</script>



