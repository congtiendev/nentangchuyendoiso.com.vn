@extends('layouts.main')
@section('page-title')
Quản lý ca làm việc
@endsection
@section('page-breadcrumb')
Ca làm việc
@endsection
@section('page-action')
<div>
    <button id="showCreateWorkShiftModal" data-title="{{ __('Create New Work Shift') }}" class="btn btn-sm btn-primary"
        data-toggle="modal" data-target="#createWorkShiftModal">
        <i class="ti ti-plus"></i>
    </button>
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
                                @if(Auth::user()->type == 'company' || Auth::user()->type == 'hr')
                                <th>Tên phòng ban</th>
                                @endif
                                @foreach(dateOfWeek() as $day => $date)
                                <th>{{ ucfirst($day) }}</th>
                                @endforeach
                                @if(Auth::user()->type == 'company' || Auth::user()->type == 'hr')
                                <th>
                                    Thao tác
                                </th>
                                @endif
                            </tr>

                        </thead>
                        <tbody>
                            @foreach($workShifts as $workShift)
                            <tr>
                                @if(Auth::user()->type == 'company' || Auth::user()->type == 'hr')
                                <td>{{ $workShift->department_name }}</td>
                                @endif
                                @foreach(dateOfWeek() as $day => $date)
                                <td>
                                    <select class="form-select shift" name="{{ $day }}[{{ $workShift->department_id }}]"
                                        data-department-id="{{  $workShift->department_id }}" data-date="{{ $date }}">
                                        <option value="">Trống</option>
                                        @foreach($workShiftTypes as $key => $shiftType)
                                        <option value="{{ $shiftType->id }}" @if(optional($workShift->firstWhere(['date'
                                            => $date,
                                            'department_id' => $workShift->department_id]))->shift == $shiftType->id) selected
                                            @endif>{{ $shiftType->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                @endforeach
                                <td>
                                    <div class="action-btn bg-danger ms-2">
                                        <form method="POST"
                                            action="{{ route('workshift-department.destroy',['id' => $workShift->id]) }}"
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


<div class="modal fade" id="createWorkShiftModal" tabindex="-1" role="dialog" aria-labelledby="createWorkShiftModal"
    aria-hidden="true">
    <div id="workshift-group-modal-content" class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('workshift-department.addWorkshiftDepartment') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createInsuranceModalLabel">Thêm ca làm việc cho phòng ban</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="department" class="col-form-label">Phòng ban</label>
                            <select class="form-control" required="required" name="department_id" id="department_id">
                                <option value="">Chọn phòng ban</option>
                                @foreach ($departments as $department)
                                <option  value="{{ $department->id }}">{{ $department->name }} - (Chi nhánh {{ getBranchNameById($department->branch_id) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary">Lưu lại</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
  
        $('#showCreateWorkShiftModal').click(function () {
            $('#createWorkShiftModal').modal('show');
         });

         $('.btn-close-modal').click(function () {
            $('#createWorkShiftModal').modal('hide');
         });


        $(document).on('change', '.shift', function () {
                var departmentId = $(this).data('department-id');
                var date = $(this).data('date');
                var shift = $(this).val();
                $.ajax({
                    url: '{{ route('workshift-department.store') }}',
                    method: 'POST',
                    data: {
                        department_id: departmentId,
                        shifts: {
                            [departmentId]: {
                                [date]: shift
                            }
                        },
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                           toastrs('success', "Cập nhật ca làm việc thành công", 'success')
                        } else {
                            toastrs('error', "Cập nhật ca làm việc thất bại " + response.message)
                        }
                    },
                    error: function (xhr, status, error) {
                        toastrs('error', "Cập nhật ca làm việc thất bại", 'error')
                    }
                });
            });
        });
</script>
@endsection