{{ Form::open(['route' => ['woslogtime.store'], 'method' => 'post']) }}

<input type="hidden" name="wo_id" value="{{ $wo_id }}">
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'wos_comment','module'=>'CMMS'])
        @endif
    </div>
    <div class="row">

        <div class="col-md-6 form-group">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}
            {{ Form::number('hours', null, ['class' => 'form-control', 'placeholder' => __('Enter Hours'), 'required' => 'required']) }}
        </div>

        <div class="col-md-6 form-group">
            {{ Form::label('minute', __('Minute'), ['class' => 'col-form-label']) }}
            {{ Form::number('minute', null, ['class' => 'form-control', 'placeholder' => __('Enter Minute'), 'required' => 'required']) }}
        </div>
        @if (Auth::user()->type == 'company')
            <div class="col-md-6 form-group">
                {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required']) }}
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('user_id', __('User'), ['class' => 'col-form-label']) }}
                    {{ Form::select('user_id', $users, null, ['class' => 'form-control select2', 'required' => 'required']) }}
                </div>
            </div>
        @endif
        @if (Auth::user()->type != 'company')
            <div class="col-md-12 form-group">
                {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required']) }}
            </div>
        @endif
        <div class="col-md-12 form-group">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'row' => 3]) }}
        </div>
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
    </div>

{{ Form::close() }}
