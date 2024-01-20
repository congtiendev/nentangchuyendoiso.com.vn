@extends('layouts.main')
@section('page-title')
Chỉnh sửa bảo hiểm
@endsection
@section('page-breadcrumb')
Chỉnh sửa bảo hiểm
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="card">
            <div class="card-body table-border-style">
                <form class="modal-content" method="POST" action="{{ route('insurance.update', ['id' => $insurance->id]) }}">
                    @csrf
                    <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createInsuranceModalLabel">Điền thông tin chỉnh sửa</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">Tên bảo hiểm</label>
                                    <input class="form-control" required="required" name="insurance_name" type="text"
                                        id="insurance_name" placeholder="Nhập tên bảo hiểm" value="{{ $insurance->insurance_name }}">
                                </div>
                            </div>
        
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="designation" class="col-form-label">Chức danh</label>
                                    <select class="form-control" required="required" name="designation_id" id="designation_id">
                                        <option value="">Chọn chức danh</option>
                                        @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}" @if ($designation->id == $insurance->designation_id)
                                            selected
                                            @endif
                                            >{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
        
        
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="department" class="col-form-label">Phòng ban</label>
                                    <select @readonly(true) class="form-control" required="required" id="department_id">
                                        <option value="{{ $insurance->department_id }}">{{ getDepartmentNameByDesignation($insurance->designation_id) }}</option>
                                    </select>
                                </div>
                            </div>
        
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="discount" class="col-form-label">Khấu trừ/label>
                                    <input class="form-control" required="required" name="discount" type="number" id="discount" placeholder="Nhập khấu trừ" value="{{ $insurance->discount }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex gap-2">
                        <button type="reset" class="btn btn-secondary">Nhập lại</button>
                        <button type="submit" class="btn btn-primary">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
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