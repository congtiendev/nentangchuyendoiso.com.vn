{{ Form::model($woslogtime, ['route' => ['woslogtime.update', $woslogtime->id], 'method' => 'PUT']) }}
<input type="hidden" name="wo_id" value="{{ $woslogtime->wo_id }}">
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'wos_logtime','module'=>'CMMS'])
        @endif
    </div>
    <div class="row">

        @if ($woslogtime->created_by == Auth::user()->id)
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
        @else
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
        @endif
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
