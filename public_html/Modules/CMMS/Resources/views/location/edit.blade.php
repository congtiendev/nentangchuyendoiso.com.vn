 {{ Form::model($location, ['route' => ['location.update', $location->id], 'method' => 'PUT']) }}
 <div class="modal-body">
     <div class="row">
         <div class="col-12">
             <div class="form-group">
                 {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
                 {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
             </div>
         </div>
         <div class="col-12">
             <div class="form-group">
                 {{ Form::label('address', __('Address'), ['class' => 'col-form-label']) }}
                 {{ Form::textarea('address', null, ['class' => 'form-control', 'required' => 'required', 'rows' => 3]) }}
             </div>
         </div>
     </div>
 </div>

 <div class="modal-footer">
     <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
     {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
 </div>
 {{ Form::close() }}
