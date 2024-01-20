@extends('layouts.main')
@section('page-title')
    {{ __('Bổ nhiệm') }}
@endsection
@section('page-breadcrumb')
    {{ __('Bổ nhiệm') }}
@endsection
<style>
    .emp-card {
        min-height: 193px !important;
    }
</style>
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Hrm/Resources/assets/css/custom.css') }}">
@endpush
@php
    $company_settings = getCompanyAllSetting();
@endphp

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-12 col-md-6">

                    <div class="card ">
                        <div class="card-body employee-detail-body fulls-card emp-card">
                            <h5>{{ __('Personal Detail') }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Employee ID') }} : </strong>
                                        <span>{{ $employeesId }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm font-style">
                                        <strong class="font-bold">{{ __('Name') }} :</strong>
                                        <span>{{ $employee->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm font-style">
                                        <strong class="font-bold">{{ __('Email') }} :</strong>
                                        <span>{{ $employee->email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Date of Birth') }} :</strong>
                                        <span>{{ !empty($employee->dob) ? company_date_formate($employee->dob) : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Phone') }} :</strong>
                                        <span>{{ $employee->phone }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Address') }} :</strong>
                                        <span>{{ $employee->address }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Salary Type') }} :</strong>
                                        <span>{{ !empty($employee->salaryType) ? $employee->salaryType->name : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Basic Salary') }} :</strong>
                                        <span>{{ $employee->salary }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">

                    <div class="card ">
                        <div class="card-body employee-detail-body fulls-card emp-card">
                            <h5>{{ __('Company Detail') }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong
                                            class="font-bold">{{ !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch') }}
                                            : </strong>
                                        <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm font-style">
                                        <strong
                                            class="font-bold">{{ !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department') }}
                                            :</strong>
                                        <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong
                                            class="font-bold">{{ !empty($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation') }}
                                            :</strong>
                                        <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Date Of Joining') }} :</strong>
                                        <span>{{ !empty($employee->company_doj) ? company_date_formate($employee->company_doj) : '' }}</span>
                                    </div>
                                </div>
                                @if (!empty($customFields) && count($employee->customField) > 0)
                                    @foreach ($customFields as $field)
                                        <div class="info text-sm">
                                            <strong class="font-bold">{{ $field->name }} :</strong>
                                            @if ($field->type == 'attachment')
                                                <a href="{{ isset($employee->customField[$field->id]) ? get_file($employee->customField[$field->id]) : '#' }}"
                                                    target="_blank">
                                                    @if (isset($employee->customField[$field->id]))
                                                        <img src="{{ get_file($employee->customField[$field->id]) }}"
                                                            class="wid-40 rounded me-3">
                                                    @endif
                                                </a>
                                            @else
                                                <span>{{ !empty($employee->customField) ? (isset($employee->customField[$field->id]) ? $employee->customField[$field->id] : '-') : '-' }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
