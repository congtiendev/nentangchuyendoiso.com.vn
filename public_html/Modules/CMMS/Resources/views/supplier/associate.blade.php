{{ Form::open(['route' => ['supplier.associate', [$module, $id]], 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
<div class="row">

    <div class="col-md-12">
        <div class="form-group">
            <!--Parts detail page in associate vendor -->
            @if ($module == 'parts_supplier')
                {{ Form::label('associate_supplier', __('Associate Supplier'),['class' => 'col-form-label']) }}
            @endif
            {{ Form::select('associate_supplier[]', $supplier,null, array('class' => 'form-control multi-select','id'=>'choices-multiple','multiple'=>'','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="row justify-content-between align-items-center">
    <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-start">
        @if ($module == 'parts_supplier')
            <a href="#" class="btn  btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Supplier') }}" 
                data-url="{{ route('supplier.create', ['parts_id' => $parts_id]) }}" data-size="lg"
                data-ajax-popup="true" data-title="{{ __('Create Supplier') }}">{{ __('Create Supplier') }}</a>

            <!-- parts detail page in associate vendor in create vendor-->
        @elseif($module == 'component_supplier')
            @permission('suppliers create')
                <a  class="btn btn-primary text-white" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Supplier') }}" 
                data-url="{{route('supplier.create', ['components_id' => $parts_id]) }}" data-toggle="tooltip" title="{{ __('Create') }}">
                {{ __('Create Supplier') }}
                </a>
            @endpermission
        @endif
    </div>
</div>

    <div class="text-end">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Associate'), ['class' => 'btn  btn-primary']) }}
    </div>
</div>
</div>
{{ Form::close() }}

<script src="{{asset('assets/js/plugins/choices.min.js')}}"></script>
<script>
    if ($(".multi-select").length > 0) {
              $( $(".multi-select") ).each(function( index,element ) {
                  var id = $(element).attr('id');
                     var multipleCancelButton = new Choices(
                          '#'+id, {
                              removeItemButton: true,
                          }
                      );
              });
         }
  </script>
