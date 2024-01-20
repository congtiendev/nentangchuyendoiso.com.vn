@extends('layouts.main')

@section('page-title')
{{ __('Create Employee') }}
@endsection

@section('page-breadcrumb')
{{ __('Employee') }},
{{ __('Create Employee') }}
@endsection
<style>
    .max-with-120 {
        max-width: 120px;
    }

    .em-card {
        min-height: 510px !important;
    }
</style>

@php
$company_settings = getCompanyAllSetting();
@endphp
@section('content')
<div class="row">
    <div class="">
        <div class="">
            {{ Form::open(['route' => ['employee.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Personal Detail') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<span
                                        class="text-danger pl-1">*</span>
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'required' =>
                                    'required' ,'placeholder'=>'Nhập tên nhân viên']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span
                                        class="text-danger pl-1">*</span>
                                    {!! Form::text('phone', old('phone'), ['class' => 'form-control'
                                    ,'placeholder'=>'Nhập số điện thoại', 'required' => 'required']) !!}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<span
                                            class="text-danger pl-1">*</span>
                                        {{ Form::date('dob', date('Y-m-d'), ['class' => 'form-control ', 'required' =>
                                        'required', 'autocomplete' => 'off' ,'placeholder'=>'Select Date of
                                        Birth','max'=>date('Y-m-d')]) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span
                                            class="text-danger pl-1">*</span>
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_male" value="Male" name="gender"
                                                    class="form-check-input" checked="checked">
                                                <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                                            </div>
                                            <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="gender"
                                                    class="form-check-input">
                                                <label class="form-check-label " for="g_female">{{ __('Female')
                                                    }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span
                                        class="text-danger pl-1">*</span>
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'required' =>
                                    'required' ,'placeholder'=>'Nhập email']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('password', __('Password'), ['class' => 'form-label']) !!}<span
                                        class="text-danger pl-1">*</span>
                                    {!! Form::password('password', ['class' => 'form-control', 'required' => 'required'
                                    ,'placeholder'=>'Nhập mật khẩu']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<span
                                    class="text-danger pl-1">*</span>
                                {!! Form::textarea('address', old('address'), ['class' => 'form-control', 'rows' => 2
                                ,'placeholder'=>'Nhập địa chỉ', 'required' => 'required']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('information_of_relatives', "Thông tin người thân", ['class' =>
                                'form-label']) !!}<span class="text-danger pl-1"></span>
                                {!! Form::textarea('information_of_relatives', old('information_of_relatives'), ['class'
                                => 'form-control', 'rows' => 2 ,'placeholder'=>'Nhập thông tin người thân..']) !!}
                            </div>
                                <div class="form-group">
                                    {!! Form::label('internal_work_process', "Quá trình công tác trong tổ chức",
                                    ['class' => 'form-label']) !!}<span class="text-danger pl-1"></span>
                                    {!! Form::textarea('internal_work_process', old('internal_work_process'), ['class'
                                    => 'form-control', 'rows' => 2 ,'placeholder'=>'Nhập quá trình...']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('external_work_process', "Quá trình công tác bên ngoài tổ chức",
                                    ['class' => 'form-label']) !!}<span class="text-danger pl-1"></span>
                                    {!! Form::textarea('external_work_process', old('external_work_process'), ['class'
                                    => 'form-control', 'rows' => 2 ,'placeholder'=>'Nhập quá trình...']) !!}
                                </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="mb-2">Bằng cấp chứng chỉ</h6>
                                    <div class="choose-files">
                                        <label for="certificate">
                                            <div class=" bg-primary document "> <i class="ti ti-upload px-1"></i>{{
                                                __('Choose file here') }}
                                            </div>
                                            <input type="file" name="certificate" id="certificate" accept="image/*"
                                                data-filename="certificate" class="form-control file  d-none">
                                        </label>
                                    </div>
                                    <div class="preview-container mt-2">
                                        <img id="previewCertificate" src="https://phutungnhapkhauchinhhang.com/wp-content/uploads/2020/06/default-thumbnail.jpg" alt="Preview" style="max-width: 100%; max-height: 110px;">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h6 class="mb-2">Chính trị quân đội</h6>
                                    <div class="choose-files">
                                        <label for="military_politics">
                                            <div class=" bg-primary document "> <i class="ti ti-upload px-1"></i>{{
                                                __('Choose file here') }}
                                            </div>
                                            <input accept="image/*" type="file" name="military_politics" id="military_politics"
                                                data-filename="military_politics" class="form-control file  d-none">
                                        </label>
                                    </div>
                                    <div class="preview-container mt-2">
                                        <img id="previewMilitaryPolitics" src="https://phutungnhapkhauchinhhang.com/wp-content/uploads/2020/06/default-thumbnail.jpg" alt="Preview" style="max-width: 100%; max-height: 110px;">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h6 class="mb-2">Sức khỏe</h6>
                                    <div class="choose-files">
                                        <label for="health">
                                            <div class=" bg-primary  "> <i class="ti ti-upload px-1"></i>{{
                                                __('Choose file here') }}
                                            </div>
                                            <input accept="image/*" type="file" name="health" id="health"
                                                data-filename="health" class="form-control file  d-none">
                                        </label>
                                    </div>
                                    <div class="preview-container mt-2">
                                        <img id="previewHealth" src="https://phutungnhapkhauchinhhang.com/wp-content/uploads/2020/06/default-thumbnail.jpg" alt="Preview" style="max-width: 100%; max-height: 110px;">
                                    </div>
                                </div>
    
                            </div>

                        </div>
                    </div>
                </div>

                {{-- -------------------Company------------------- --}}
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Company Detail') }}</h6>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            <div class="row">
                                @csrf
                                <div class="form-group">
                                    {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                    {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled'
                                    => 'disabled']) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('branch_id', !empty($company_settings['hrm_branch_name']) ?
                                    $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'form-label'])
                                    }}<span class="text-danger pl-1">*</span>
                                    {{ Form::select('branch_id', $branches, null, ['class' => 'form-control', 'required'
                                    => 'required', 'placeholder' => __(''.(!empty($company_settings['hrm_branch_name'])
                                    ? $company_settings['hrm_branch_name'] : __('Chọn chi nhánh')))]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('department_id', !empty($company_settings['hrm_department_name']) ?
                                    $company_settings['hrm_department_name'] : __('Department'), ['class' =>
                                    'form-label']) }}<span class="text-danger pl-1">*</span>
                                    {{ Form::select('department_id',[], null, ['class' => 'form-control', 'id' =>
                                    'department_id', 'required' => 'required','placeholder' => __('Chọn
                                    '.(!empty($company_settings['hrm_department_name']) ?
                                    $company_settings['hrm_department_name'] : __('Department')))]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('designation_id', !empty($company_settings['hrm_designation_name']) ?
                                    $company_settings['hrm_designation_name'] : __('Designation'), ['class' =>
                                    'form-label']) }}<span class="text-danger pl-1">*</span>
                                    {{ Form::select('designation_id',[], null, ['class' => 'form-control', 'id' =>
                                    'designation_id', 'required' => 'required','placeholder' => __('Chọn
                                    '.(!empty($company_settings['hrm_designation_name']) ?
                                    $company_settings['hrm_designation_name'] : __('Designation')))]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('role', __('Role'), ['class' => 'form-label']) }}<span
                                        class="text-danger pl-1">*</span>
                                    {{ Form::select('role', $role, null, ['class' => 'form-control', 'required' =>
                                    'required', 'placeholder' => 'Chọn quyền']) }}
                                </div>
                                <div class="form-group ">
                                    {!! Form::label('company_doj', __('Company Date Of Joining'), ['class' =>
                                    'form-label']) !!}
                                    {{ Form::date('company_doj', date('Y-m-d'), ['class' => 'form-control ', 'required'
                                    => 'required', 'autocomplete' => 'off' ,'placeholder'=>'Select company date of
                                    joining']) }}
                                </div>
                                @if(module_is_active('CustomField') && !$customFields->isEmpty())
                                <div class="col-md-12">
                                    <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                        @include('customfield::formBuilder')
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                {{-- -------------------End Company------------------- --}}
            </div>
            <div class="row">
                <div class="col-md-6 ">
                    <div class="card em-card ">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Document') }}</h6>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            @foreach ($documents as $key => $document)
                            <div class="row">
                                <div class="form-group col-12 d-flex">
                                    <div class="float-left col-4">
                                        <label for="document" class="float-left pt-1 form-label">{{ $document->name }}
                                            @if ($document->is_required == 1)
                                            <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                    </div>
                                    <div class="float-right col-8">
                                        <input type="hidden" name="emp_doc_id[{{ $document->id }}]"
                                            value="{{ $document->id }}">
                                        <div class="choose-files ">
                                            <label for="document[{{ $document->id }}]">
                                                <div class=" bg-primary document "> <i class="ti ti-upload px-1"></i>{{
                                                    __('Choose file here') }}
                                                </div>
                                                <input type="file"
                                                    class="form-control file  d-none @error('document') is-invalid @enderror doc_data"
                                                    @if ($document->is_required == 1) data-key="{{$key}}" required
                                                @endif
                                                name="document[{{ $document->id }}]" id="document[{{ $document->id }}]"
                                                data-filename="{{ $document->id . '_filename' }}"
                                                onchange="document.getElementById('{{'blah'.$key}}').src =
                                                window.URL.createObjectURL(this.files[0])">
                                            </label>

                                            <p class="text-danger d-none" id="{{'doc_validation-'.$key}}">{{__('This
                                                filed is required.')}}</p>
                                            <img id="{{'blah'.$key}}" width="50%" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="card em-card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Bank Account Detail') }}</h6>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' =>
                                    'form-label']) !!}
                                    {!! Form::text('account_holder_name', old('account_holder_name'), ['class' =>
                                    'form-control' ,'placeholder'=>'Nhập tên chủ tài khoản']) !!}

                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label'])
                                    !!}
                                    {!! Form::number('account_number', old('account_number'), ['class' => 'form-control'
                                    ,'placeholder'=>'Nhập số tài khoản']) !!}

                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                    {!! Form::text('bank_name', old('bank_name'), ['class' => 'form-control'
                                    ,'placeholder'=>'Tên ngân hàng']) !!}

                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' =>
                                    'form-label']) !!}
                                    {!! Form::text('bank_identifier_code', old('bank_identifier_code'), ['class' =>
                                    'form-control' ,'placeholder'=>'Mã định danh ngân hàng']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label'])
                                    !!}
                                    {!! Form::text('branch_location', old('branch_location'), ['class' => 'form-control'
                                    ,'placeholder'=>'Vị trí chi nhánh']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                    {!! Form::text('tax_payer_id', old('tax_payer_id'), ['class' => 'form-control'
                                    ,'placeholder'=>'Số thuế']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="float-end mb-5">
            <button type="submit" id="submit" class="btn  btn-primary">Lưu thông tin</button>
        </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        // Bắt sự kiện khi thay đổi giá trị của input file
        $("#certificate").change(function () {
            previewImage(this,'previewCertificate');
        });
        $("#military_politics").change(function () {
            previewImage(this,'previewMilitaryPolitics');
        });

        $("#health").change(function () {
            previewImage(this,'previewHealth');
        });
        function previewImage(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    // Hiển thị ảnh trước khi tải lên trong thẻ img
                    $('#' + id).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]); // Đọc dữ liệu tập tin và kích thước của nó
            }
        }
    });

    $(document).on('change', '#branch_id', function() {
                var branch_id = $(this).val();
                getDepartment(branch_id);
            });

            function getDepartment(branch_id)
            {
                var data = {
                    "branch_id": branch_id,
                    "_token": "{{ csrf_token() }}",
                }

                $.ajax({
                    url: '{{ route('employee.getdepartment') }}',
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        $('#department_id').empty();
                        $('#department_id').append('<option value="" disabled>{{ __('Select Department') }}</option>');

                        $.each(data, function(key, value) {
                            $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                        $('#department_id').val('');
                    }
                });
            }

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
        getDesignation(department_id);
        });

        function getDesignation(did) {
        $.ajax({
            url: '{{ route('employee.getdesignation') }}',
            type: 'POST',
            data: {
                "department_id": did,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#designation_id').empty();
                $('#designation_id').append(
                    '<option value="">{{ __('Select Designation') }}</option>');
                $.each(data, function(key, value) {
                    $('#designation_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
            }
        });
        }
        $("#submit").click(function() {
            $(".doc_data").each(function() {
                if(!isNaN(this.value)) {
                    var id ='#doc_validation-'+$(this).data("key");
                    $(id).removeClass('d-none')
                    return false;
                }
            });
        });

</script>
@endpush