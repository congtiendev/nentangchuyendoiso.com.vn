@extends('layouts.main')
@section('page-title')
    {{ __('Manage Purchase Orders') }}
@endsection
@section('page-breadcrumb')
    {{ __('POs') }}
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
                            <a class="dash-head-link dropdown-toggle arrow-none me-0 location_name" data-bs-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
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
                        @permission('POs purchase order create')
                            <a class="btn btn-sm btn-primary" data-size="md" data-title="{{ __('Create New POs') }}"
                                href="{{ route('cmms_pos.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Supplier Name') }}</th>
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('Location') }}</th>
                                    <th>{{ __('Purchase Order Date') }}</th>
                                    <th>{{ __('Expected Delivery Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            @php
                                foreach ($location_name as $location)
                                {
                                    $location = $location;
                                }
                            @endphp
                            <tbody>
                                @foreach ($pos as $invoice)
                                        <tr>
                                            <td>{{ $invoice->supplier_name }}</td>
                                            <td>{{ $invoice->user_name }}</td>
                                            <td>{{ !empty($location->getLocation) ? $location->getLocation->name : '' }}</td>
                                            <td>{{ company_date_formate($invoice->pos_date) }}</td>
                                            <td>{{ company_date_formate($invoice->delivery_date) }}</td>

                                            <td class="Action">
                                                <span>
                                                    @permission('POs purchase order edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('cmms_pos.edit', \Crypt::encrypt($invoice->id)) }}"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-bs-whatever="{{ __('Edit POs') }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-bs-original-title="{{ __('Edit POs') }}"> <span
                                                                    class="text-white"> <i class="ti ti-edit"></i></span></a>
                                                        </div>
                                                    @endpermission
                                                    @permission('POs purchase order delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['cmms_pos.destroy', $invoice->id], 'class' => 'm-0']) !!}

                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm ">
                                                                <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Delete') }}"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endpermission
                                                </span>
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
@endsection
