@extends('layouts.main')
@section('page-title')
{{ __('Manage Contract') }}
@endsection
@section('page-breadcrumb')
{{ __('Contract') }}
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
<style>
    .comp-card {
        min-height: 140px;
    }
</style>
@endpush
@section('page-action')
<div>
    @stack('addButtonHook')
     <a href="{{ route('contract.list_liquidation') }}" class="btn btn-sm btn-primary"
      data-bs-toggle="tooltip"title="{{ __('Hợp đồng thanh lý') }}">
      <i class="ti ti-list text-white"></i>
      </a> 
    <!-- <a href="{{ route('contract.grid') }}" class="btn btn-sm btn-primary"
      data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
      <i class="ti ti-layout-grid text-white"></i>
      </a> -->
    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Nhập vào hợp đồng')}}"
        data-url="{{ route('customer.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
            class="ti ti-file-import"></i>
    </a>
    @permission('contract create')
    <a data-url="{{ route('contract.create') }}" data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Create New Contract') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
    @endpermission
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card comp-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-20">{{ __('Total Contracts') }}</h6>
                        <h3 class="text-primary">{{ $cnt_contract['total'] }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake bg-success text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card comp-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-20">{{ __('This Month Total Contracts') }}</h6>
                        <h3 class="text-info">{{ $cnt_contract['this_month'] }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake bg-info text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card comp-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-20">{{ __('This Week Total Contracts') }}</h6>
                        <h3 class="text-warning">{{ $cnt_contract['this_week'] }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake bg-warning text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card comp-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-20">{{ __('Last 30 Days Total Contracts') }}</h6>
                        <h3 class="text-danger">{{ $cnt_contract['last_30days'] }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake bg-danger text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>{{ __('Contract') }}</th>
                                <th>{{ __('subject') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('project') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contracts as $contract)
                            <tr>
                                <td class="Id">
                                    @permission('contract show')
                                    <a href="{{ route('contract.show', $contract->id) }}"
                                        class="btn btn-outline-primary">
                                        {{ Modules\Contract\Entities\Contract::contractNumberFormat($contract->id) }}
                                    </a>
                                    @else
                                    <a class="btn btn-outline-primary">{{
                                        Modules\Contract\Entities\Contract::contractNumberFormat($contract->id) }}</a>
                                    @endif
                                </td>
                                <td>{{ $contract->subject }}</td>
                                <td>{{ !empty($contract->user_name) ? $contract->user_name : '-' }}</td>
                                <td>{{ !empty($contract->project_name) ? $contract->project_name : '-' }}</td>
                                <td>{{ currency_format_with_sym($contract->value) }}</td>
                                <td>{{ $contract->contract_type }}</td>
                                <td>{{ company_date_formate($contract->start_date) }}</td>
                                <td>{{ company_date_formate($contract->end_date) }}</td>
                                <td>
                                    @if ($contract->status == 'accept')
                                    <span class="status_badge badge bg-primary  p-2 px-3 rounded">{{ __('Accept')
                                        }}</span>
                                    @elseif($contract->status == 'decline')
                                    <span class="status_badge badge bg-danger p-2 px-3 rounded">{{ __('Decline')
                                        }}</span>
                                    @elseif($contract->status == 'pending')
                                    <span class="status_badge badge bg-warning p-2 px-3 rounded">{{ __('Pending')
                                        }}</span>
                                    @endif
                                </td>
                                @if (Laratrust::hasPermission('contract create') || Laratrust::hasPermission('contract
                                show') || Laratrust::hasPermission('contract edit') ||
                                Laratrust::hasPermission('contract delete'))
                                <td class="Action">
                                    <span>
                                        @permission('contract create')
                                        @if (\Auth::user()->type == 'company')
                                        <div class="action-btn bg-primary">
                                            <a data-size="lg" data-url="{{ route('contracts.copy', $contract->id) }}"
                                                data-ajax-popup="true" data-title="{{ __('Duplicate') }}"
                                                class="btn btn-sm d-inline-flex align-items-center"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Duplicate') }}"><i class="ti ti-copy text-white"></i></a>
                                        </div>
                                        @endif
                                        @endpermission
                                        @permission('contract show')
                                        <div class="action-btn bg-warning">
                                            <a href="{{ route('contract.show', $contract->id) }}"
                                                class="btn btn-sm d-inline-flex align-items-center"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('View') }}"><i class="ti ti-eye text-white"></i></a>
                                        </div>
                                        @endpermission
                                        @permission('contract edit')
                                        <div class="action-btn bg-info">
                                            <a data-size="lg"
                                                data-url="{{ URL::to('contract/' . $contract->id . '/edit') }}"
                                                data-ajax-popup="true" data-title="{{ __('Edit Contract') }}"
                                                class="btn btn-sm d-inline-flex align-items-center"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Edit') }}"><i class="ti ti-pencil text-white"></i></a>
                                        </div>
                                        @endpermission
                                        @permission('contract delete')
                                        <div class="action-btn bg-danger">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['contract.destroy',
                                            $contract->id]]) !!}
                                            <a href="#!"
                                                class="btn btn-sm d-inline-flex align-items-center show_confirm"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Delete') }}">
                                                <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                {!! Form::close() !!}
                                            </a>
                                        </div>
                                        @endpermission
                                        @permission('contract delete')
                                        <div class="action-btn bg-secondary">
                                            {!! Form::open(['method' => 'POST', 'route' => ['contract.liquidation',
                                            $contract->id]]) !!}
                                            <a href="#!"
                                                class="btn btn-sm show_confirm"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Thanh lý') }}">
                                                <span class="text-white">&#10006;</span>
                                                {!! Form::close() !!}
                                            </a>
                                        </div>
                                        @endpermission
                                    </span>
                                </td>
                                @else
                                <td>
                                    @if($contract->status == 'pending')
                                    <a title="Chấp nhận" href="{{route('contract.accept', $contract->id)}}"
                                        class="btn btn-success btn-sm text-white d-inline-flex align-items-center accept-btn">
                                        <i class="ti ti-check"></i>
                                    </a>
                                    <a title="Từ chối" href="{{route('contract.decline', $contract->id)}}"
                                        class="btn btn-danger btn-sm text-white d-inline-flex align-items-center decline-btn">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    @else
                                    <span class="">Đã {{ __($contract->status)
                                        }}</span>
                                    @endif
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
@push('scripts')

@endpush