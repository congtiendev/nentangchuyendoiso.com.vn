{{ Form::open([ 'method' => 'post', 'enctype' => 'multipart/form-data' , 'id' => 'upload_form']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-6">
            {{ Form::label('file', __('Download sample Workorder CSV file'), ['class' => 'col-form-label']) }}
            <span class="float-end">
            @if(check_file('uploads/sample/sample_workorder.csv'))
                <a href="{{ asset('uploads/sample/sample_workorder.csv') }}" class="btn  btn-primary">
                    <i class="ti ti-download"></i> {{ __('Download') }}
                </a>
            @endif
            </span>
        </div>
        <div class="col-md-12">
            <div class="form-group">
            {{ Form::label('file', __('Select CSV File'), ['class' => 'col-form-label']) }}
                    <input type="file" class="form-control" name="file" id="file" data-filename="upload_file"
                        required>
                <p class="upload_file"></p>
        </div>
    </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Upload'), ['class' => 'btn  btn-primary']) }}
    </div>
    <a href="" data-url="{{ route('workorder.import.modal') }}" data-ajax-popup-over="true" title="{{ __('Create') }}" data-size="xl" data-title="{{ __('Import workorder CSV Data') }}"  class="d-none import_modal_show"></a>
</div>
{{ Form::close() }}

<script>
    $('#upload_form').on('submit', function(event) {

        event.preventDefault();
        let data = new FormData(this);
        data.append('_token', "{{ csrf_token() }}");
        $.ajax({
            url: "{{ route('workorder.importcreate') }}",
            method: "POST",
            data: data,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.error != '')
                {
                    toastrs('Error',data.error, 'error');
                } else {
                    $('#commonModal').modal('hide');
                    $(".import_modal_show").trigger( "click" );
                    setTimeout(function() {
                        SetData(data.output);
                    }, 700);
                }
            }
        });

    });
</script>