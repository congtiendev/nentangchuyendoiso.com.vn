@extends('layouts.main')
@section('page-title')
Quản lý ca làm việc
@endsection
@section('page-breadcrumb')
Ca làm việc
@endsection
@section('page-action')
<div>
    @if(Auth::user()->type == 'company' || Auth::user()->type == 'hr')
    <button id="showCreateWorkShiftModal" data-title="{{ __('Create New Work Shift') }}" class="btn btn-sm btn-primary"
        data-toggle="modal" data-target="#createWorkShiftModal">
        <i class="ti ti-plus"></i>
    </button>
    <a href="{{ route('workshift.approval.list') }}" class="btn btn-primary btn-sm">Danh sách ca chờ duyệt</a>
    @else
    <button id="showEmployeeCreateWorkShiftModal"
        class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </button>
    <button id="showEmployeeEditWorkShiftModal"
        class="btn btn-sm btn-primary">
        <i class="ti ti-pencil"></i>
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
                                <th>Tên nhân viên</th>
                                @foreach(dateOfWeek() as $day => $date)
                                <th>{{ ucfirst($day) }}</th>
                                @endforeach
                                <th>
                                    Thao tác
                                </th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach($workShifts as $workShift)
                            <tr>
                                <td>{{ $workShift->user_name }}</td>
                                @foreach(dateOfWeek() as $day => $date)
                                <td>
                                    @if(Auth::user()->type == 'company' || Auth::user()->type == 'hr')
                                    <select class="form-select shift" name="{{ $day }}[{{ $workShift->user_id }}]"
                                        data-user-id="{{  $workShift->user_id }}" data-date="{{ $date }}">
                                        <option value="none" @if(optional($workShift->firstWhere(['date' => $date,
                                            'user_id' => $workShift->user_id]))->shift == '') selected @endif>Trống
                                        </option>
                                        <option value="morning" @if(optional($workShift->firstWhere(['date' => $date,
                                            'user_id' => $workShift->user_id]))->shift == 'morning') selected @endif>Ca
                                            sáng
                                        </option>
                                        <option value="afternoon" @if(optional($workShift->firstWhere(['date' => $date,
                                            'user_id' => $workShift->user_id]))->shift == 'afternoon') selected
                                            @endif>Ca chiều
                                        </option>
                                        <option value="full" @if(optional($workShift->firstWhere(['date' => $date,
                                            'user_id' => $workShift->user_id]))->shift == 'full') selected @endif>Ca
                                            ngày</option>
                                        <option value="off" @if(optional($workShift->firstWhere(['date' => $date,
                                            'user_id' => $workShift->user_id]))->shift == 'off') selected @endif>Nghỉ
                                        </option>
                                    </select>
                                    @else
                                    @if(optional($workShift->firstWhere(['date' => $date,
                                    'user_id' => $workShift->user_id]))->shift == 'morning')
                                    <span class="btn btn-outline-info">Ca sáng</span>
                                    @elseif(optional($workShift->firstWhere(['date' => $date,
                                    'user_id' => $workShift->user_id]))->shift == 'afternoon')
                                    <span class="btn btn-outline-warning">Ca chiều</span>
                                    @elseif(optional($workShift->firstWhere(['date' => $date,
                                    'user_id' => $workShift->user_id]))->shift == 'full')
                                    <span class="btn btn-outline-primary">Cả ngày</span>
                                    @elseif(optional($workShift->firstWhere(['date' => $date,
                                    'user_id' => $workShift->user_id]))->shift == 'off')
                                    <span class="btn btn-outline-primary">Nghỉ</span>
                                    @else
                                    <span class="btn btn-outline-secondary">Trống</span>
                                    @endif
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    <div class="action-btn bg-danger ms-2">
                                        <form method="POST"
                                            action="{{ route('workshift.destroy',['id' => $workShift->id]) }}"
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
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('workshift.addEmployee') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createWorkShiftModalLabel">Thêm vào ca làm việc</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="designation" class="col-form-label">Nhân viên</label>
                            <select class="form-control" required="required" name="user_id" id="user_id">
                                <option value="">Chọn nhân viên</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->user_id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
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

<div class="modal fade" id="employeeCreateWorkShiftModal" tabindex="-2" role="dialog"
    aria-labelledby="employeeCreateWorkShiftModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="employeeCreateWorkShiftForm" class="modal-content" method="POST"
            action="{{ route('workshift.addWorkshiftApproval') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeCreateWorkShiftModalLabel">Đăng ký ca làm việc</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="col-form-label">Ngày </label>
                            <select class="form-control" required="required" name="date" id="date">
                                <option value="">Chọn ngày</option>
                                @foreach (dateOfWeek() as $day => $date)
                                <option value="{{ $date }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="col-form-label">Ca làm việc</label>
                            <select class="form-control" required="required" name="shift" id="shift">
                                <option value="">Chọn ca làm việc</option>
                                <option value="morning">Ca sáng</option>
                                <option value="afternoon">Ca chiều</option>
                                <option value="full">Cả ngày</option>
                                <option value="off">Nghỉ</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12" id="reason">
                        <div class="form-group">
                            <label class="col-form-label">
                                Lý do
                            </label>
                            <textarea class="form-control" name="reason" id="reason" cols="30" rows="5"></textarea>
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
        $('#showCreateWorkShiftModal').click(function () {
            $('#createWorkShiftModal').modal('show');
         });
         $('.btn-close-modal').click(function () {
            $('#createWorkShiftModal').modal('hide');
            $('#employeeCreateWorkShiftModal').modal('hide');
            $('#employeeEditWorkShiftModal').modal('hide');
         });

        $('#showEmployeeCreateWorkShiftModal').click(function () {
            $("#employeeCreateWorkShiftModalLabel").text('Đăng ký ca làm việc');
            $('#reason').show();
            $('#employeeCreateWorkShiftModal').modal('show');
        });
        $('#showEmployeeEditWorkShiftModal').click(function () {
            $("#employeeCreateWorkShiftModalLabel").text('Cập nhật ca làm việc');
            $('#reason').hide();
            $('#employeeCreateWorkShiftModal').modal('show');
        });


        $(document).on('change', '.shift', function () {
                var userId = $(this).data('user-id');
                var date = $(this).data('date');
                var shift = $(this).val();
                $.ajax({
                    url: '{{ route('workshift.store') }}',
                    method: 'POST',
                    data: {
                        user_id: userId,
                        shifts: {
                            [userId]: {
                                [date]: shift
                            }
                        },
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                           toastrs('success', "Cập nhật ca làm việc thành công", 'success')
                        } else {
                            toastrs('error', "Cập nhật ca làm việc thất bại", 'error')
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