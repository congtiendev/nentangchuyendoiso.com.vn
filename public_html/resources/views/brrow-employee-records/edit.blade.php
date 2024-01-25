@extends('layouts.main')

@section('page-title')
    {{ __('Sửa hồ sơ duyệt mượn') }}
@endsection

@section('page-breadcrumb')
    {{ __('Hồ sơ duyệt mượn') }},{{ __('Edit') }}
@endsection
@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush
@section('content')
    <form action="{{ route('borrow-employee-records.update', $borrowEmployeeRecord->id) }}" class="mt-3" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6" id="">
                                <select class="form-control select_project" name="project_id" required="">
                                    <option value="">{{ __('Select Project') }}</option>
                                    @foreach ($projects as $key => $value)
                                        <option value="{{ $key }}" {{ old('project_id', $borrowEmployeeRecord->project_id) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="form-group col-md-6" id="username">
                                <select class="form-control select_user" name="user_project" required="">
                                    <option value="" >{{ __('Chọn nhân viên') }}</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Tên hồ sơ') }}</label>
                                <input class="form-control" type="text" name="name" value="{{$borrowEmployeeRecord->name}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Số ngày mượn') }}</label>
                                <input class="form-control" type="text" name="borrowed_day" value="{{$borrowEmployeeRecord->borrowed_day}}">
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('borrowed_date', __('Ngày mượn'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::date('borrowed_date', $borrowEmployeeRecord->borrowed_date, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('give_back_day', __('Ngày trả'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::date('give_back_day', $borrowEmployeeRecord->give_back_day, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label class="require form-label">{{ __('Description') }}</label>
                                <input name="description" 
                                class="form-control summernote {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" required 
                                value="{{$borrowEmployeeRecord->description}}">
                                    
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="require form-label">{{ __('Status') }}</label>
                                <select class="form-control select_project" name="status" required="">
                                    @foreach (\App\Models\BorrowEmployeeRecord::$statues as $index => $status)
                                        <option value="{{ $status }}" {{ $borrowEmployeeRecord->status == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end text-end">
                            <a class="btn btn-secondary btn-light btn-submit" href="{{ route('borrow-employee-records.index') }}">{{ __('Cancel') }}</a>
                            <button class="btn btn-primary btn-submit ms-2" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function () {
                $('.select_project').change(function () {
                    var projectId = $(this).val();
                    if (projectId) {
                        // Thực hiện AJAX request để lấy danh sách người dùng dựa trên dự án đã chọn
                        $.ajax({
                            type: 'GET',
                            url: '/get-users-by-project/' + projectId, // Điều này cần được xử lý bởi một route và controller trong Laravel
                            success: function (data) {
                                console.log(data)
                                // Xóa tất cả các option hiện tại
                                $('.select_user').empty();
                                $('.select_user').append('<option value="">{{ __('Chọn nhân viên') }}</option>');

                                // Thêm các option mới dựa trên dữ liệu lấy được từ AJAX
                                $.each(data, function (key, value) {
                                    $('.select_user').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('.select_user').empty();
                        $('.select_user').append('<option value="">{{ __('Chọn nhân viên') }}</option>');
                    }
                });
            });
    </script>
@endpush
