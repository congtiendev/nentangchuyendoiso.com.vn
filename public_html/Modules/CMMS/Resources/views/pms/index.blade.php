@extends('layouts.main')
@section('page-title')
    {{ __('Manage Preventive Maintenance') }}
@endsection
@section('page-breadcrumb')
{{ __('Pms') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush

@section('page-action')
<div>
    <div class="text-end">
        <div class="d-flex justify-content-end drp-languages">
            @if($locations->isempty())
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
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                            aria-labelledby="dropdownLanguage">
                            @foreach ($locations as $key => $value)
                                <a href="{{ route('change-location', $key) }}"
                                class="dropdown-item {{ $currentLocation == $key? 'text-primary' : '' }}">{{ Str::ucfirst($value) }}</a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            @endif
            <ul class="list-unstyled mb-0 mt-2 ms-2">
                <li class="dropdown dash-h-item drp-language">
                    @permission('pms create')
                        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Pms') }}" data-url="{{route('pms.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Location') }}</th>
                                <th width="200px"> {{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pms as $pms_val)
                            <tr>
                                <td>{{ $pms_val->name }}</td>                                
                                <td>{{ !empty($pms_val->getLocation) ? $pms_val->getLocation->name : ''}}</td>

                                @if (Laratrust::hasPermission('pms edit') || Laratrust::hasPermission('pms delete') || Laratrust::hasPermission('pms show'))
                                    <td class="Action">
                                        <span>
                                            @permission('pms show')
                                            <div class="action-btn bg-warning ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                   href="{{ route('pms.show', $pms_val->id) }}"
                                                     data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('View Pms') }}"
                                                    data-bs-original-title="{{ __('View') }}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('pms edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('pms.edit', $pms_val->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Pms') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('pms delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('pms.destroy', $pms_val->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$pms_val->id}}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                {{Form::close()}}
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

