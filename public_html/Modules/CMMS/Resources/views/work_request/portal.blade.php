@php
    $lang=$lang;
    app()->setLocale($lang);
     
  
    $location_id = Crypt::decrypt($id);
    $location = Modules\CMMS\Entities\Location::find($location_id);
    $user_id  = $location->company_id;
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
<title>@yield('page-title') | {{ !empty(company_setting('title_text' , $user_id)) ? company_setting('title_text' , $user_id) : (!empty(admin_setting('title_text')) ? admin_setting('title_text') :'WorkDo') }}</title>
<link rel="icon" href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png')  }}{{'?'.time()}}" type="image/x-icon" />
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" >



<body class="{{ !empty(company_setting('color' , $user_id))?company_setting('color' , $user_id):'theme-1' }}">
<div class="justify-content-center d-flex pt-4">

    <div class="card col-md-6 col-sm-6">
        <h3 class="text-center pt-4">{{__('Submit a Work Request')}}</h3>
        <div class="card-body"> 
            {{ Form::open(array('route' => ['work_request.sand'], 'id' => 'assets_store', 'enctype' => 'multipart/form-data')) }}
            <div class="row">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input name="location_id" value="{{ $id }}" type="hidden">
                <input name="lang" value="{{ $lang }}" type="hidden">

                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('wo_name', __('Work Request'),['class' => 'col-form-label']) }}
                        {{ Form::text('wo_name', null, ['class' => 'form-control','required'=>'required','placeholder'=>"Title of work request"]) }}
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('instructions', __('Instructions'),['class' => 'col-form-label']) }}
                        {{ Form::textarea('instructions', null, ['class' => 'form-control','required'=>'required','placeholder'=>"Title of work request" , 'row' => 3]) }}
                    </div>
                </div>
    
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('name', __('Name'),['class' => 'col-form-label']) }}
                        {{ Form::text('user_name', null, ['class' => 'form-control','required'=>'required','placeholder'=>"Your Name"]) }}
                    </div>
                </div>
    
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('email', __('Email'),['class' => 'col-form-label']) }}
                        {{ Form::email('user_email', null, ['class' => 'form-control','required'=>'required','placeholder'=>"Your Email"]) }}
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('components_id', __('Problem'),['class' => 'col-form-label']) }}
                        {{ Form::select('components_id',$components,null, ['class' => 'form-control select2','required'=>'required']) }}
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                    {{ Form::label('file', __('Add Picture and Document'),['class' => 'col-form-label']) }}
                    <input type="file" class="form-control" name="file[]" id="file" data-filename="file" accept="image/*,.jpeg,.jpg,.png" required="required" multiple>
                </div>
            </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"> </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
</body>
<script src="{{ asset('js/jquery.min.js') }}"></script>

<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>



<script src="{{ asset('js/custom.js') }}"></script>
<script>
$(document).ready(function () {

function toastrs(text,message,type) {
    var f = document.getElementById('liveToast');
    var a = new bootstrap.Toast(f).show();
    if (type == 'success')
    {
        $('#liveToast').addClass('bg-primary');
    } else {
        $('#liveToast').addClass('bg-danger');
    }
    $('#liveToast .toast-body').html(message);
}
});
</script>

@if($message = Session::get('success'))
<script>
    toastrs('Success', '{!! $message !!}', 'success');
    </script>
@endif
@if($message = Session::get('error'))
<script>
    toastrs('Error', '{!! $message !!}', 'error');
    </script>
@endif


</html>