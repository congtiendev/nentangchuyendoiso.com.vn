    {{ Form::model($pmslogtime, ['route' => ['pmslogtime.update', $pmslogtime->id], 'method' => 'PUT']) }}
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'pms_logtime','module'=>'CMMS'])
            @endif
        </div>
        <input type="hidden" name="pms_id" value="{{ $pmslogtime->pms_id }}">
        @if ($pmslogtime->created_by == Auth::user()->id)
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}
                    {{ Form::number('hours', null, ['class' => 'form-control', 'placeholder' => __('Enter Hours'), 'required' => 'required']) }}
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('minute', __('Minute'), ['class' => 'col-form-label']) }}
                    {{ Form::number('minute', null, ['class' => 'form-control', 'placeholder' => __('Enter Minute'), 'required' => 'required']) }}
                </div>
                <div class="col-md-12 form-group">
                    {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
                    {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required']) }}
                </div>
                <div class="col-md-12 form-group">
                    {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'row' => 3]) }}
                </div>
            </div>
            <div class="modal-footer pr-0">
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
            </div>
    </div>
@else
    <div class="row">

        <div class="d-grid gap-2 d-md-block">
            <a href="#" class=" btn btn-sm btn-primary float-end" data-size="md" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['pms_logtime']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                data-title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                <span><i class="fas fa-robot"></i>{{ __(' Generate With AI') }}</span>
            </a>
        </div>


        <div class="col-md-6 form-group">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}
            {{ Form::number('hours', null, ['class' => 'form-control', 'placeholder' => __('Enter Hours'), 'required' => 'required', 'disabled']) }}
        </div>
        <div class="col-md-6 form-group">
            {{ Form::label('minute', __('Minute'), ['class' => 'col-form-label']) }}
            {{ Form::number('minute', null, ['class' => 'form-control', 'placeholder' => __('Enter Minute'), 'required' => 'required', 'disabled']) }}
        </div>
        <div class="col-md-12 form-group">
            {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
            {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required', 'disabled']) }}
        </div>
        <div class="col-md-12 form-group">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'disabled', 'row' => 3]) }}
        </div>
    </div>
    @endif
    {{ Form::close() }}
