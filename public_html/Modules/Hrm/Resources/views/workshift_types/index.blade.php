@extends('layouts.main')
@section('page-title')
Quản lý loại ca làm việc
@endsection
@section('page-breadcrumb')
Loại ca làm việc
@endsection
@section('page-action')
<div>
    @if(Auth::user()->type == 'company' || Auth::user()->type == 'hr')
    <button 
    id="showCreateEditWorkShiftTypeModal"
    data-action="{{ route('workshift-type.store') }}"
        class="btn btn-sm" style="background-color:#0CAF60;">
        <i class="ti ti-plus"></i>
    </button>
    @endif
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>Tên loại ca</th>
                                <th>Thời gian bắt đầu</th>
                                <th>Thời gian kết thúc</th>
                                <th>
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workshiftTypes as $workshiftType)
                            <tr>
                                <td>{{ $workshiftType->name }}</td>
                                <td>{{ $workshiftType->start_time }}</td>
                                <td>{{ $workshiftType->end_time }}</td>
                                <td>
                                    <div class="action-btn bg-success">
                                        <button
                                            data-action="{{ route('workshift-type.update', ['id' => $workshiftType->id]) }}"
                                            data-name="{{ $workshiftType->name }}"
                                            data-start_time="{{ $workshiftType->start_time }}"
                                            data-end_time="{{ $workshiftType->end_time }}"
                                            class="btn-show-edit-modal mx-3 btn btn-sm  align-items-center"
                                            data-toggle="modal">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                    @if($workshiftType->is_default == 0)
                                    <div class="action-btn bg-danger ms-2">
                                        <form method="POST"
                                            action="{{ route('workshift-type.destroy',['id' => $workshiftType->id]) }}"
                                            accept-charset="UTF-8" class="m-0">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"> <a
                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                aria-label="Delete" data-confirm="Bạn có chắc chắn?"
                                                data-text="Hành động này không thể được hoàn tác. Bạn có muốn tiếp tục?"
                                                data-confirm-yes="delete-form-30">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>


                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="createEditWorkShiftTypeModal" tabindex="-1" role="dialog"
    aria-labelledby="createEditWorkShiftTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('workshift-type.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createEditWorkShiftTypeModalLabel">Thêm loại làm việc</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Tên loại ca</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Tên loại ca...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_time" class="col-form-label">Thời gian bắt đầu</label>
                            <input type="time" class="form-control" name="start_time" id="start_time"
                                placeholder="Thời gian bắt đầu...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_time" class="col-form-label">Thời gian kết thúc</label>
                            <input type="time" class="form-control" name="end_time" id="end_time"
                                placeholder="Thời gian kết thúc...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
       $(document).on('click', '.btn-show-edit-modal', function () {
            $('#createEditWorkShiftTypeModal').modal('show');
            $('#createEditWorkShiftTypeModal').find('form').attr('action', $(this).data('action'));
            $('#createEditWorkShiftTypeModal').find('#name').val($(this).data('name'));
            $('#createEditWorkShiftTypeModal').find('#start_time').val($(this).data('start_time'));
            $('#createEditWorkShiftTypeModal').find('#end_time').val($(this).data('end_time'));
         });

         $('.btn-close-modal').click(function () {
            $('#createEditWorkShiftTypeModal').modal('hide');
         });

        $('#showCreateEditWorkShiftTypeModal').click(function () {
            $('#createEditWorkShiftTypeModal').find('form').attr('action', $(this).data('action'));
            $('#createEditWorkShiftTypeModal').find('#name').val('');
            $('#createEditWorkShiftTypeModal').find('#start_time').val('');
            $('#createEditWorkShiftTypeModal').find('#end_time').val('');
            $('#createEditWorkShiftTypeModal').modal('show');
         });
      ;
    });
</script>
@endsection