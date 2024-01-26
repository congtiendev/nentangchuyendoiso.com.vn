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
    <form action="{{ route('borrow-asset-records.update', $borrowAssetRecord->id) }}" class="mt-3" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6" id="">
                                <select class="form-control select_project" name="asset_id" required="">
                                    <option value="">{{ __('Select Project') }}</option>
                                    @foreach ($assets as $key => $value)
                                        <option value="{{ $key }}" {{ old('project_id', $borrowAssetRecord->asset_id) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="form-group col-md-6" id="username">
                                <select class="form-control select_project" name="user_id" required="">
                                    <option value="">{{ __('Select User') }}</option>
                                    @foreach ($users as $key => $value)
                                        <option value="{{ $key }}" {{ old('project_id', $borrowAssetRecord->user_id) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Tên hồ sơ') }}</label>
                                <input class="form-control" type="text" name="name" value="{{$borrowAssetRecord->name}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Số ngày mượn') }}</label>
                                <input class="form-control" type="text" name="borrowed_day" value="{{$borrowAssetRecord->borrowed_day}}">
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('borrowed_date', __('Ngày mượn'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::date('borrowed_date', $borrowAssetRecord->borrowed_date, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('give_back_day', __('Ngày trả'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::date('give_back_day', $borrowAssetRecord->give_back_day, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label class="require form-label">{{ __('Description') }}</label>
                                <input name="description" 
                                class="form-control summernote {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" required 
                                value="{{$borrowAssetRecord->description}}">
                                    
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="require form-label">{{ __('Status') }}</label>
                                <select class="form-control select_project" name="status" required="">
                                    @foreach (\App\Models\borrowAssetRecord::$statues as $index => $status)
                                        <option value="{{ $status }}" {{ $borrowAssetRecord->status == $status ? 'selected' : '' }}>
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
                            <a class="btn btn-secondary btn-light btn-submit" href="{{ route('borrow-asset-records.index') }}">{{ __('Cancel') }}</a>
                            <button class="btn btn-primary btn-submit ms-2" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
@endsection

