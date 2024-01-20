@extends('layouts.main')
@section('page-title')
{{ __('Manage Insurance') }}
@endsection
@section('page-breadcrumb')
{{ __('Insurance') }}
@endsection
@section('page-action')
<div>
    @stack('addButtonHook')
    @permission('employee create')
    <button id="showCreateInsuranceModal" data-title="{{ __('Create New Employee') }}" class="btn btn-sm btn-primary"
        data-toggle="modal" data-target="#createInsuranceModal">
        <i class="ti ti-plus"></i>
    </button>
    @endpermission
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
                                <th>Mã bảo hiểm</th>
                                <th>Tên bảo hiểm</th>
                                <th>Phòng ban</th>
                                <th>Chức danh</th>
                                <th>Khấu trừ</th>
                                @if (Laratrust::hasPermission('employee edit') || Laratrust::hasPermission('employee
                                delete'))
                                <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($insurances as $insurance)
                            <tr>
                                <td>{{ $insurance->id }}</td>
                                <td>{{ $insurance->insurance_name }}</td>
                                <td>{{ getDepartmentNameByDesignation($insurance->designation_id) }}</td>
                                <td>{{ getDesignationNameById($insurance->designation_id) }}</td>
                                <td>{{ $insurance->discount }}%</td>
                                @if (Laratrust::hasPermission('employee edit') || Laratrust::hasPermission('employee
                                delete'))
                                <td>
                                    <div class="action-btn">
                                        <a href="{{ route('insurance.edit', $insurance->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    </div>
                             
                                    <div class="action-btn bg-danger ms-2">
                                    <form method="POST" action="{{ route('insurance.destroy',['id' => $insurance->id]) }}" accept-charset="UTF-8" class="m-0">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">                                                                    <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete" data-confirm="Bạn có chắc chắn?" data-text="Hành động này không thể được hoàn tác. Bạn có muốn tiếp tục?" data-confirm-yes="delete-form-30">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                        </form>
                                    </div>
                                </td>
                                @endif
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="createInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="createInsuranceModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('insurance.store') }}">
            @csrf
            <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
            <div class="modal-header">
                <h5 class="modal-title" id="createInsuranceModalLabel">Thêm bảo hiểm</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Tên bảo hiểm</label>
                            <input class="form-control" required="required" name="insurance_name" type="text"
                                id="insurance_name">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="designation" class="col-form-label">Chức danh</label>
                            <select class="form-control" required="required" name="designation_id" id="designation_id">
                                <option value="">Chọn chức danh</option>
                                @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group">
                            <label for="department" class="col-form-label">Phòng ban</label>
                            <select @readonly(true) class="form-control" required="required" id="department_id">
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="discount" class="col-form-label">Khấu trừ</label>
                            <input class="form-control" required="required" name="discount" type="number" id="discount">
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
    $('#showCreateInsuranceModal').click(function () {
       $('#createInsuranceModal').modal('show');
    });
    $('.btn-close-modal').click(function () {
       $('#createInsuranceModal').modal('hide');
    });
    $('#designation_id').change(function () {
        var designation_id = $(this).val();
        $.ajax({
            url: '{{ route('insurance.getDepartment') }}',
            type: 'POST',
            data: {
                designation_id: designation_id
            },
            success: function (data) {
                if(data.success ==1){
                    $('#department_id').html(data.option);
                }else{
                    $('#department_id').html('<option value="">Chọn phòng ban</option>');
                }
            }
        });
    });
</script>
@endsection