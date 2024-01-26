@extends('layouts.main')
@section('page-title')
    {{ __('Quản lí yêu cầu') }}
@endsection
@section('page-breadcrumb')
    {{ __('Yêu cầu') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush

@section('page-action')
    <div>
        <div class="text-end">
            <div class="d-flex justify-content-end drp-languages">
                @if ($locations->isempty())
                @else
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language">
                            <a class="dash-head-link dropdown-toggle arrow-none me-0 location_name" data-bs-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                id="dropdownLanguage">
                                <i class="ti ti-current-location text-primary me-2"></i>
                                @foreach ($locations as $key => $value)
                                    <span
                                        class="drp-text hide-mob text-primary">{{ $currentLocation == $key ? Str::ucfirst($value) : '' }}</span>
                                @endforeach
                                <i class="ti ti-chevron-down drp-arrow nocolor ms-3"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                                @foreach ($locations as $key => $value)
                                    <a href="{{ route('change-location', $key) }}"
                                        class="dropdown-item {{ $currentLocation == $key ? 'text-primary' : '' }}">{{ Str::ucfirst($value) }}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                @endif
                <ul class="list-unstyled mb-0 mt-2 ms-2">
                    <li class="dropdown dash-h-item drp-language">
                        @permission('components create')
                            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                                data-title="{{ __('Tạo mới yêu cầu') }}" data-url="{{ route('component.create') }}"
                                data-toggle="tooltip" title="{{ __('Create') }}">
                                <i class="ti ti-plus text-white"></i>
                            </a>
                        @endpermission
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="products">
                            <thead>
                                <tr>
                                    <th>{{ __('Hình ảnh') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Mã') }}</th>
                                    <th>{{ __('Vị trí') }}</th>
                                    <th width="200px"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($components as $component)
                                    <tr>
                                        @php
                                            $thumbnail = $component->thumbnail;
                                        @endphp
                                        <td width="200">
                                            @if (!empty($thumbnail))
                                                <a href="{{ get_file($thumbnail) }}" target="_blank">
                                                    <img src="{{ get_file($thumbnail) }}" width="60" height="60"
                                                        class="rounded-circle" />
                                            @endif
                                        </td>
                                        <td>{{ $component->name }}</td>
                                        <td>{{ $component->sku }}</td>
                                        <td>{{ !empty($component->getLocation) ? $component->getLocation->name : '' }}</td>
                                        @if (Laratrust::hasPermission('components edit') ||
                                                Laratrust::hasPermission('components delete') ||
                                                Laratrust::hasPermission('components show'))
                                            <td class="Action">
                                                <span>
                                                    @permission('components show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                href="{{ route('component.show', $component->id) }}"
                                                                data-toggle="tooltip" title="{{ __('View') }}"
                                                                data-title="{{ __('View Component') }}">
                                                                <i class="ti ti-eye text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('components edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('component.edit', $component->id) }}"
                                                                data-ajax-popup="true" data-size="lg" data-toggle="tooltip"
                                                                title="{{ __('Edit') }}"
                                                                data-title="{{ __('Chỉnh sửa thông tin') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('components delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{ Form::open(['route' => ['component.destroy', $component->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-toggle="tooltip" title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $component->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {{ Form::close() }}
                                                        </div>
                                                    @endpermission
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
