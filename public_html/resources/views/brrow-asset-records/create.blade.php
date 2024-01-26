@extends('layouts.main')

@section('page-title')
    {{ __('Tạo hồ sơ duyệt mượn') }}
@endsection

@section('page-breadcrumb')
    {{ __('Hồ sơ duyệt mượn') }},{{ __('Create') }}
@endsection
@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush
@section('content')
    <form action="{{ route('borrow-asset-records.store') }}" class="mt-3" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <select class="form-control select_project" name="asset_id" required="">
                                    <option value="">{{ __('Chọn dụng cụ') }}</option>
                                    @foreach ($assets as $key => $value)
                                        <option value="{{ $key }}" {{ old('asset_id') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="form-group col-md-6" id="username">
                                <select class="form-control select_user" name="user_id" required="">
                                    <option value="">{{ __('Chọn người mượn') }}</option>
                                    @foreach ($users as $key => $value)
                                    <option value="{{ $key }}" {{ old('user_id') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Tên hồ sơ') }}</label>
                                <input class="form-control" type="text" name="name" value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Số ngày mượn') }}</label>
                                <input class="form-control" type="text" name="borrowed_day" value="{{ old('borrowed_day') }}">
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('borrowed_date', __('Ngày mượn'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::date('borrowed_date', old('borrowed_date', date('Y-m-d')), ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('give_back_day', __('Ngày trả'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::date('give_back_day', old('give_back_day', date('Y-m-d')), ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="require form-label">{{ __('Description') }}</label>
                                <textarea name="description" class="form-control" required>
                                </textarea>
                            </div>
                        </div>
                    
                        <div class="d-flex justify-content-end text-end">
                            <a class="btn btn-secondary btn-light btn-submit" href="{{ route('borrow-asset-records.index') }}">{{ __('Cancel') }}</a>
                            <button class="btn btn-primary btn-submit ms-2" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
@endsection

