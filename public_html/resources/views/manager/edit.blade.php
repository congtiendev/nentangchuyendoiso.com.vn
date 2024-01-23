 {{ Form::model($manager, ['route' => ['manager-file.update', $manager->id], 'method' => 'POST']) }}
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
                 {{ Form::label('name', __('Tên hồ sơ'),['class' => 'col-form-label']) }}
                 <select name="type" class="form-control font-style">
                     <option value="">Chọn loại hồ sơ</option>
                     <option value="0" {{ $manager->type == 0 ? 'selected' : '' }}>Hồ sơ mượn LĐ</option>
                     <option value="1" {{ $manager->type == 1 ? 'selected' : '' }}>Hồ sơ mượn DC</option>
                 </select>
             </div>
         </div>
         <div class="col-12">
             <div class="form-group">
                 {{ Form::label('name', __('Trạng thái'),['class' => 'col-form-label']) }}
                 <select name="status" class="form-control font-style">
                     <option value="0" {{ $manager->status == 0 ? 'selected' : '' }}>Chờ phê duyệt</option>
                     <option value="1" {{ $manager->status == 1 ? 'selected' : '' }}>Phê duyệt</option>
                     <option value="2" {{ $manager->status == 2 ? 'selected' : '' }}>Thu hồi</option>
                     <option value="3" {{ $manager->status == 3 ? 'selected' : '' }}>Hủy</option>
                 </select>
             </div>
         </div>
     </div>
 </div>

 <div class="modal-footer">
     <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
     {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
 </div>
 {{ Form::close() }}
