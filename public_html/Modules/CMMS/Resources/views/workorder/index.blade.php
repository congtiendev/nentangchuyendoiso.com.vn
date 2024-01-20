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
            @if($locations->isempty())
            @else
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0 custom_btn location_name" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false"
                        id="dropdownLanguage">
                            <i class="ti ti-current-location text-primary me-2"></i>
                            @foreach ($locations as $key => $value)
                            <span
                                class="drp-text hide-mob">{{ $currentLocation == $key ? Str::ucfirst($value) : '' }}</span>
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
                    @permission('workorder import')
                        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Import WOs') }}" data-url="{{url('workorder_import')}}" data-toggle="tooltip" title="{{ __('Import WOs') }}">
                            <i class="ti ti-file-import"></i>
                        </a>
                    @endpermission
                </li>
            </ul>
            <ul class="list-unstyled mb-0 mt-2 ms-2">
                <li class="dropdown dash-h-item drp-language">
                    @permission('workorder create')
                        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Work Order') }}" data-url="{{route('workorder.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>{{ __('ID lệnh yêu cầu') }}</th>
                                <th>{{ __('Tên yêu cầu') }}</th>
                                <th>{{ __('Ưu tiên') }}</th>
                                <th>{{ __('Mô tả') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Vị trí')}}</th>
                                <th width="200px"> {{ __('Action') }}</th>
                            </tr>
                        </thead>
                        
                        @php($prioritys = Modules\CMMS\Entities\Workorder::priority())
                        
                        <tbody>
                            @if (Auth::user()->type == 'company')
                            @foreach ($work_order as $assign_work_orders)
                            <tr>
                                <td style="white-space: normal;width: 500px;">{{ $assign_work_orders->wo_id}}</td>
                                <td>{{ $assign_work_orders->wo_name }}</td>
                                <td>
                                    @foreach ($prioritys as $priority)
                                        @if ($priority['priority'] == $assign_work_orders->priority)
                                            <span
                                                class="badge bg-{{ $priority['color'] }} p-2 px-3 rounded">
                                                {{ __($assign_work_orders->priority) }}</span>
                                        @endif
                                    @endforeach
                                </td>
               
                                <td width="200px">{{ $assign_work_orders->instructions }}</td>
                                <td width="200px">{{ __($assign_work_orders->work_status) }}</td>
                                <td>{{ !empty($assign_work_orders->getLocation) ? $assign_work_orders->getLocation->name : ''}}</td>
                                @if (Laratrust::hasPermission('workorder edit') || Laratrust::hasPermission('workorder delete') || Laratrust::hasPermission('workorder show'))
                                    <td class="Action">
                                        <span>
                                            @permission('workorder show')
                                            <div class="action-btn bg-warning ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    href="{{ route('workorder.show', $assign_work_orders->id) }}"
                                                     data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('View Workorder') }}"
                                                    data-bs-original-title="{{ __('View') }}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('workorder edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('workorder.edit', $assign_work_orders->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Workorder') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('workorder delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('workorder.destroy', $assign_work_orders->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$assign_work_orders->id}}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                {{Form::close()}}
                                            </div>
                                            @endpermission
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                       

                        @else

                                
                        @foreach ($total_work_order as $assign_work_orders)
                            <tr>
                                <td>{{ $assign_work_orders['wo_id'] }}</td>
                                <td>{{ $assign_work_orders['wo_name'] }}</td>
                                <td>
                                    @foreach ($prioritys as $priority)
                                    @if ($priority['priority'] == $assign_work_orders['priority'])
                                    <span
                                    class="badge bg-danger p-2 px-3 rounded
                                    
                                    {{ $priority['color'] }}">
                                    {{ $assign_work_orders['priority'] }}</span>
                                 
                                    @endif
                                    @endforeach
                                   
                                </td>
                                        <td>{{ $assign_work_orders['instructions'] }}</td>
                                        <td>{{ $assign_work_orders['work_status'] }}</td>
                                        <td>{{ !empty($assign_work_orders->getLocation) ? $assign_work_orders->getLocation->name : ''}}</td>

                                        <td class="action">
                                            <span>
                                                @permission('workorder show')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        href="{{ route('workorder.show', $assign_work_orders['id']) }}"
                                                         data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('View Workorder') }}"
                                                        data-bs-original-title="{{ __('View') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>
                                                @endpermission
                                                @permission('workorder edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ route('workorder.edit', $assign_work_orders['id']) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Workorder') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                                @endpermission
                                                @permission('workorder delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('workorder.destroy', $assign_work_orders['id']),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$assign_work_orders['id']}}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                    {{Form::close()}}
                                                </div>
                                                @endpermission
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

