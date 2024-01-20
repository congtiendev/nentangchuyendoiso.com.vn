@extends('layouts.main')

@section('page-title')
    {{ __('Work Order') }}
@endsection

@section('page-breadcrumb')
{{ __('Work Order') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone.min.css') }}">
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
                    @permission('workorder create')
                        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Component') }}" data-url="{{route('workorder.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
    <div class="col-xl-12 col-lg-12 col-md-12 d-flex Palign-items-center justify-content-end mb-3">
        <div class="row">
            <div class="btn-group btn-group-toggle" data-toggle="buttons" aria-label="Basic radio toggle button group">
                <label class="btn btn-primary month-label">
                    <a href="{{ route('workorder.index') }}" class="text-white"> {{ __('Open') }} </a>
                </label>

                <label class="btn btn-primary year-label">
                    <a href="{{ route('workorder.index') }}" class="text-white">Đang xử lý</a>
                </label>
                <label class="btn btn-primary year-label ">
                    <a href="{{ route('workorder.complete.task') }}" class="text-white">{{ __('Completed') }}</a>
                </label>
                <label class="btn btn-primary month-label">
                    <a href="{{ route('workorder.complete.task') }}" class="text-white"> Theo dõi</a>
                </label>

              
            </div>
        </div>
    </div>


    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>{{ __('Work Order Id') }}</th>
                                <th>{{ __('Work Order Name') }}</th>
                                <th>{{ __('Priority') }}</th>
                                <th>{{ __('Instructions') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th width="200px"> {{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @php($prioritys =  Modules\CMMS\Entities\Workorder::priority())
                        <tbody>
                            @if (Auth::user()->type != 'company')
                                @foreach ($total_work_order as $workorders)
                                    <tr>
                                       
                                        <td>{{ $workorders['wo_id'] }}</td>
                                        <td>{{ $workorders['wo_name'] }}</td>
                                        <td>
                                            @foreach ($prioritys as $priority)
                                                @if ($priority['priority'] == $workorders['priority'])
                                                    <span class="badge bg-{{ $priority['color'] }} p-2 px-3 rounded">
                                                        {{ $workorders['priority'] }}</span>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $workorders['instructions'] }}</td>
                                        <td>{{ __('Complete') }}</td>

                                        <td class="action">
                                            <span>
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('workorder.show', [$workorders['id']]) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('View') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('workorder.edit', $workorders['id']) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Workorder') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                                </div>
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['workorder.destroy', $workorders['id']]]) !!}
                                                    <a href="#!"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm ">
                                                        <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>

                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach ($work_order as $workorder_val)
                                    <tr>
                                        <td>{{ $workorder_val->wo_id }}</td>
                                        <td>{{ $workorder_val->wo_name }}</td>
                                        <td>
                                            @foreach ($prioritys as $priority)
                                                @if ($priority['priority'] == $workorder_val->priority)
                                                    <span class="badge bg-{{ $priority['color'] }} p-2 px-3 rounded">
                                                        {{ $workorder_val->priority }}</span>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $workorder_val->instructions }}</td>
                                        <td>{{ __('Complete') }}</td>
                                        <td class="action">
                                            <span>

                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('workorder.show', [$workorder_val->id]) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('View') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('workorder.edit', $workorder_val->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Workorder') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                                </div>
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['workorder.destroy', $workorder_val->id]]) !!}
                                                    <a href="#!"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm ">
                                                        <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>

                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
