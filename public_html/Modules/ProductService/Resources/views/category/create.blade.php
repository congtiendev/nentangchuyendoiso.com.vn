{{ Form::open(['route' => 'category.store']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Tên danh mục'), ['class' => 'form-label']) }}
            {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12">
            <input type="hidden" value="{{$types}}" name="type">
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('color', __('Thể loại màu sắc'), ['class' => 'form-label']) }}
            {{ Form::color('color', '', ['class' => 'form-control jscolor', 'required' => 'required']) }}
            <small>{{ __('Để biểu diễn biểu đồ') }}</small>
        </div>
    </div>
</div>
<div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
