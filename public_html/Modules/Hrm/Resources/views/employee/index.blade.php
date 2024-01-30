@extends('layouts.main')
@section('page-title')
    {{ __('Manage Employee') }}
@endsection
@section('page-breadcrumb')
    {{ __('Employee') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('employee import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Employee Import') }}"
                data-url="{{ route('employee.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('employee.grid') }}" class="btn btn-sm btn-primary btn-icon"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @permission('employee create')
            <a href="{{ route('employee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip"
                title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
        @permission('employee create')
            <a href="{{ route('activityLog.employee') }}" data-title="{{ __('Lịch sử chỉnh sửa') }}" data-bs-toggle="tooltip"
                title="" class="btn btn-sm btn-primary">
                Lịch sử chỉnh sửa
            </a>
        @endpermission
        @permission('employee create')
            <select class="btn btn-sm btn-primary" onchange="toggleColumn(this.value)">
                <option value="">Chọn để ẩn hiện cột</option>
                <option value="0">Mã nhân viên</option>
                <option value="1">Tên</option>
                <option value="2">Email</option>
                <option value="3">Chi nhánh</option>
                <option value="4">Bộ phận</option>
                <option value="5">Chức danh</option>
                <option value="6">Ngày vào công ty</option>
            </select>
        @endpermission


    </div>
@endsection
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">

                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch') }}
                                    </th>
                                    <th>{{ !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department') }}
                                    </th>
                                    <th>{{ !empty($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation') }}
                                    </th>
                                    <th>{{ __('Date Of Joining') }}</th>
                                    @if (Laratrust::hasPermission('employee edit') || Laratrust::hasPermission('employee delete'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    @if ($employee->employee_id != null)
                                        <tr>
                                            @if (!empty($employee->employee_id))
                                                <td>
                                                    @permission('employee show')
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id) }}</a>
                                                    @else
                                                        <a
                                                            class="btn btn-outline-primary">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id) }}</a>
                                                    @endpermission
                                                </td>
                                            @else
                                                <td>--</td>
                                            @endif
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>
                                                {{ !empty($employee->branch_id) ? $employee->branches_name : '--' }}
                                            </td>
                                            <td>
                                                {{ !empty($employee->department_id) ? $employee->departments_name : '--' }}
                                            </td>
                                            <td>
                                                {{ !empty($employee->designation_id) ? $employee->designations_name : '--' }}
                                            </td>
                                            <td>
                                                {{ !empty($employee->company_doj) ? company_date_formate($employee->company_doj) : '--' }}
                                            </td>
                                            @if (Laratrust::hasPermission('employee edit') || Laratrust::hasPermission('employee delete'))
                                                <td class="Action">
                                                    @if ($employee->is_disable == 1)
                                                        <span>
                                                            @permission('employee edit')
                                                                <div class="action-btn bg-info ms-2">
                                                                    <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->ID)) }}"
                                                                        class="mx-3 btn btn-sm  align-items-center"
                                                                        data-bs-toggle="tooltip" title=""
                                                                        data-bs-original-title="{{ __('Edit') }}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>
                                                            @endpermission
                                                            @if (!empty($employee->employee_id))
                                                                @permission('employee show')
                                                                    <div class="action-btn bg-warning ms-2">
                                                                        <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                                            class="mx-3 btn btn-sm  align-items-center"
                                                                            data-bs-toggle="tooltip" title=""
                                                                            data-bs-original-title="{{ __('Show') }}">
                                                                            <i class="ti ti-eye text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endpermission
                                                                @permission('employee delete')
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        {{ Form::open(['route' => ['employee.destroy', $employee->id], 'class' => 'm-0']) }}
                                                                        @method('DELETE')
                                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                            data-bs-toggle="tooltip" title=""
                                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                            data-confirm-yes="delete-form-{{ $employee->id }}"><i
                                                                                class="ti ti-trash text-white text-white"></i></a>
                                                                        {{ Form::close() }}
                                                                    </div>
                                                                @endpermission
                                                            @endif
                                                        </span>
                                                    @else
                                                        <div class="text-center">
                                                            <i class="ti ti-lock"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        applyStoredColumnState();
    });
    // Hàm để lấy trạng thái đã lưu từ Local Storage
    function getStoredColumnState() {
        var columnStates = localStorage.getItem('columnStates');
        return columnStates ? JSON.parse(columnStates) : {};
    }

    // Hàm để lưu trạng thái của các cột vào Local Storage
    function saveColumnState(columnStates) {
        localStorage.setItem('columnStates', JSON.stringify(columnStates));
    }

    // Hàm để toggle trạng thái của một cột và lưu vào Local Storage
    function toggleColumn(colIndex) {
        var table = document.getElementById('assets');
        var rows = table.rows;
        var columnStates = getStoredColumnState();

        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].cells;
            if (cells.length > colIndex) {
                if (cells[colIndex].style.display === 'none') {
                    cells[colIndex].style.display = '';
                    columnStates[colIndex] = 'visible'; // Lưu trạng thái của cột
                } else {
                    cells[colIndex].style.display = 'none';
                    columnStates[colIndex] = 'hidden'; // Lưu trạng thái của cột
                }
            }
        }

        saveColumnState(columnStates); // Lưu trạng thái của các cột vào Local Storage
    }

    // Hàm để áp dụng trạng thái đã lưu từ Local Storage khi tải lại trang
    function applyStoredColumnState() {
        var columnStates = getStoredColumnState();
        var table = document.getElementById('assets');
        var rows = table.rows;
        for (var colIndex = 0; colIndex < rows[0].cells.length; colIndex++) {
            if (columnStates[colIndex]) {
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].cells;
                    if (cells.length > colIndex) {
                        cells[colIndex].style.display = columnStates[colIndex] === 'hidden' ? 'none' : '';
                    }
                }
            }
        }
    }
</script>
@endpush
