{{ Form::open(['route' => ['supplier.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

<input name="_token" value="{{ csrf_token() }}" type="hidden">
<input type="hidden" name="parts_id" value="{{ $parts_id }}">
<input type="hidden" name="components_id" value="{{ $components_id }}">
<div class="modal-body">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('location', __('Location'), ['class' => 'col-form-label']) }}
                <select name="location" class="form-control select2" required>
                    @foreach ($locations as $key => $value)
                        <option value="{{ $key }}" @if ($currentLocation == $key) selected @endif>
                            {{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('contact', __('Contact'), ['class' => 'col-form-label']) }}
                {{ Form::text('contact', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'col-form-label']) }}
                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('phone', __('Phone No.'), ['class' => 'col-form-label']) }}
                {{ Form::text('phone', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('image', __('Profile Image'), ['class' => 'col-form-label']) }}
                        <input type="file" class="form-control" name="image" id="image" data-filename="image"
                            accept="image/*,.jpeg,.jpg,.png" required="required"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        <img id="blah" width="50%" class="mt-3">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('address', __('Address'), ['class' => 'col-form-label']) }}
                {{ Form::text('address', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
    </div>

</div>
{{ Form::close() }}



<script src="{{ asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
