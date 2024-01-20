@extends('layouts.main')
@section('page-title')
    {{ __('Work Order Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Work Order') }} , {{ __('Work Order deatils') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone.min.css') }}">
@endpush

@section('page-action')
    <div class="row">
        <div class="col-7">
            @if ($Workorder->status == 1)
                <a class="btn p-2 btn-sm btn-primary btn-icon m-1 header_btns" data-ajax-popup="true" data-size="md"
                    data-title="{{ __('Task Complete') }}"
                    data-url="{{ route('workorder.task.complete', ['task_id' => $Workorder->id]) }}" data-toggle="tooltip"
                    title="{{ __('Create WOs') }}">
                    <span class="btn-inner--icon text-white"><i class="fa fa-check"></i> {{ __('Task Complete') }}</span>
                </a>
            @elseif($Workorder->status == 2)
                {!! Form::open(['method' => 'POST', 'route' => ['workorder.task.reopen', $Workorder->id]]) !!}
                <a href="#!" class="btn p-2 btn-sm btn-primary btn-icon m-1 header_btns show_confirm">
                    <i class="ti ti-lock-open text-white"> {{ __('Reopen Task') }}</i>
                </a>
                {!! Form::close() !!}
            @endif

        </div>
        <div class="col-5">
            @php
                $wosstatus = Modules\CMMS\Entities\Workorder::wosstatus();
            @endphp

            {!! Form::open(['method' => 'POST', 'class' => 'm-0']) !!}
            <input type="hidden" id="wosid" name="wosid" value="{{ $Workorder->id }}">
            <div class="form-group header_btns status_btns" style="width: 130px;">
                <select name="priority" class="form-control select2" id="work_status">
                    @foreach ($wosstatus as $wos_status)
                        <option {{ $wos_status['work_status'] == $Workorder->work_status ? 'selected' : '' }}
                            value="{{ $wos_status['work_status'] }}">{{ __($wos_status['work_status']) }}</option>
                    @endforeach
                </select>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#overview_sidebar"
                                class="list-group-item list-group-item-action active">{{ __('Overview') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#report_sidebar" class="list-group-item list-group-item-action">{{ __('Report') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#pos_sidebar" class="list-group-item list-group-item-action">{{ __('POs') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#parts_sidebar" class="list-group-item list-group-item-action">{{ __('Parts') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#logtime_sidebar" class="list-group-item list-group-item-action">{{ __('Log Time') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#invoice_sidebar" class="list-group-item list-group-item-action">{{ __('Invoice') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#document_sidebar"
                                class="list-group-item list-group-item-action">{{ __('Document and Picture') }}<div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#comment_sidebar" class="list-group-item list-group-item-action">{{ __('Comments') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div id="overview_sidebar">
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-center">{{ __('Components') }}</h5>
                                        @php
                                            $thumbnail = !empty($components_data->thumbnail) ? '' . $components_data->thumbnail : 'avatar/placeholder.jpg';
                                        @endphp
                                        <div class="card_img text-center">
                                            <a href="#" class="hover-translate-y-n3">
                                                <img class="img_setting seo_image " src="{{ get_file($thumbnail) }}"
                                                    alt="{{ !empty($components_data->name) ? '' . $components_data->name : '' }}">
                                            </a>

                                        </div>
                                        <div class="mt-1 text-center">
                                            <h6>{{ !empty($components_data->name) ? '' . $components_data->name : 'Image' }}
                                            </h6>
                                        </div>
                                        @if ($components_data)
                                            <div class="action-btn bg-warning ms-2 mt-3">
                                                <a href="{{ route('component.show', [$components_data->id]) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View') }}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="action-btn bg-info ms-2">
                                            <a class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                data-url="{{ route('wos.componentedit', $Workorder->id) }}"
                                                data-ajax-popup="true" data-size="md" ata-bs-toggle="tooltip" title=""
                                                data-title="{{ __('Edit Work Order') }}"><span class="text-white">
                                                    <i class="ti ti-edit" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Edit') }}"></i></span></a>
                                        </div>
                                        <div class="float-end">
                                            <span class="badge bg-primary p-2 px-3 rounded h6 text-white mt-3">
                                                <span class="">{{ __('Component') }} </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 d-flex">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>{{ __('Details') }}</h5>
                                        <div class="row  mt-4">
                                            <dt class="col-lg-4 h6 text-lg">{{ __('WOs Name') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Workorder->wo_name }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Instructions') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Workorder->instructions }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Due Date') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ company_date_formate($Workorder->date) }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Time') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Workorder->time }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Assigned') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                @if ($Sand_data)
                                                    {{ implode(',', $Sand_data) }}
                                                @else
                                                    {{ Modules\CMMS\Entities\Workorder::assignTo($Workorder->created_by) }}
                                                @endif
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Priority') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Workorder->priority }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Tags') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                @foreach ($Workorder_tag as $workorder_tags)
                                                    <span
                                                        class="badge bg-primary p-2 px-3 rounded">{{ $workorder_tags }}</span>
                                                @endforeach
                                            </dd>



                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="report_sidebar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted">{{ __('Recent Orders') }}</small>

                                            </div>
                                        </div>
                                        <div id="visitors-chart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="float-end">
                                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Refferals"><i class="ti ti-info-circle"></i></a>
                                        </div>
                                        <h5>{{ __('Purchase Parts') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                <div id="projects-chart"></div>
                                            </div>
                                            <div class="col-6">

                                                <div class="row mt-3">

                                                    <div class="col-12">
                                                        <span class="d-flex align-items-center mb-2">
                                                            <i class="f-10 lh-1 fas fa-circle text-warning"></i>
                                                            <h5 class="ms-2 mt-2">
                                                                {{ !empty($arrPartsper[0]) ? $arrPartsper[0] : '0' }}%
                                                            </h5>
                                                            <span class="ms-2 text-sm">{{ __('Not Purchased') }}</span>
                                                        </span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="d-flex align-items-center mb-2">
                                                            <i class="f-10 lh-1 fas fa-circle text-info"></i>
                                                            <h5 class="ms-2 mt-2">
                                                                {{ !empty($arrPartsper[1]) ? $arrPartsper[1] : '0' }}%</h5>
                                                            <span class="ms-2 text-sm">{{ __('Purchased') }}</span>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pos_sidebar">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="mb-0">{{ __('POs') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        @permission('POs purchase order create')
                                            <a href="{{ route('cmms_pos.create', ['wo_id' => $Workorder->id]) }}"
                                                class="btn btn-sm btn-primary btn-icon m-1"
                                                data-bs-whatever="{{ __('Create New POs') }}"> <span class="text-white">
                                                    <i class="ti ti-plus text-white" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Create') }}"></i></span>
                                            </a>
                                        @endpermission
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Supplier Name') }}</th>
                                                <th>{{ __('User Name') }}</th>
                                                <th>{{ __('Purchase Order Date') }}</th>
                                                <th>{{ __('Expected Delivery Date') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($wo_pos as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->supplier_name }}</td>
                                                    <td>{{ $invoice->user_name }}</td>
                                                    <td>{{ company_date_formate($invoice->pos_date) }}</td>
                                                    <td>{{ company_date_formate($invoice->delivery_date) }}</td>

                                                    <td class="Action" style="width: 10%">
                                                        <span>
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('cmms_pos.edit', \Crypt::encrypt($invoice->id)) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-edit text-white"></i>
                                                                </a>
                                                            </div>
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'class' => 'm-0', 'route' => ['cmms_pos.destroy', $invoice->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm ">
                                                                    <i class="ti ti-trash text-white"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Delete') }}"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
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

                    <div id="parts_sidebar">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="mb-0">{{ __('Parts') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        @permission('parts create')
                                            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                                data-title="{{ __('Associate Parts') }}"
                                                data-url="{{ route('parts.associate.create', ['workorder', $Workorder->id]) }}"
                                                data-toggle="tooltip" title="{{ __('Create') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        @endpermission
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Parts Thumbnail') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($parts as $parts_val)
                                                <tr>
                                                    <td width="100">
                                                        <a href="{{ $parts_val->thumbnail ? get_file($parts_val->thumbnail) : get_file('uploads/Parts/thumbnail/placeholder.jpg') }}"
                                                            target="_blank">
                                                            <img src="{{ $parts_val->thumbnail ? get_file($parts_val->thumbnail) : get_file('uploads/Parts/thumbnail/placeholder.jpg') }}"
                                                                width="60" height="60" class="rounded-circle" />
                                                        </a>
                                                    </td>
                                                    <td>{{ $parts_val->name }}</td>
                                                    <td class="action" style="width: 10%">
                                                        <span>
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('parts.show', [$parts_val->id]) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('View') }}">
                                                                    <i class="ti ti-eye text-white"></i>
                                                                </a>
                                                            </div>
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['parts.associate_remove', 'workorder', $parts_val->id],
                                                                    'class' => 'm-0',
                                                                ]) !!}
                                                                {!! Form::hidden('workorder_id', $Workorder->id) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm ">
                                                                    <i class="ti ti-trash text-white"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Delete') }}"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
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

                    <div id="logtime_sidebar">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="mb-0">{{ __('Log Time') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        @permission('parts create')
                                            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                                data-title="{{ __('Create Log Time') }}"
                                                data-url="{{ route('woslogtime.create', ['wo_id' => $Workorder->id]) }}"
                                                data-toggle="tooltip" title="{{ __('Create') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        @endpermission
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($woslogtime as $woslogtime_val)
                                                <tr>
                                                    <td style="white-space: inherit">
                                                        <i class="far fa-clock"></i>
                                                        {{ $woslogtime_val->hours }} {{ __('hr') }}
                                                        {{ $woslogtime_val->minute }} {{ __('min') }} <span
                                                            style="color: black">{{ __('by') }}</span>
                                                        {{ $woslogtime_val->name }}
                                                        {{ company_date_formate($woslogtime_val->date) }}
                                                        - {{ $woslogtime_val->description }}
                                                    </td>
                                                    <td class="Action" style="width:10%">
                                                        <span>
                                                            @permission('logtime edit')
                                                                <div class="action-btn bg-info ms-2">
                                                                    <a class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                        data-url="{{ route('woslogtime.edit', $woslogtime_val->wos_lt) }}"
                                                                        data-ajax-popup="true" data-size="md"
                                                                        data-bs-toggle="tooltip" title=""
                                                                        data-title="{{ __('Edit Log Time') }}"
                                                                        data-bs-original-title="{{ __('Edit') }}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>
                                                            @endpermission
                                                            @permission('logtime delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['woslogtime.destroy', $woslogtime_val->wos_lt],
                                                                        'class' => 'm-0',
                                                                    ]) !!}
                                                                    <a href="#!"
                                                                        class="mx-3 btn btn-sm align-items-center show_confirm">
                                                                        <i class="ti ti-trash text-white"
                                                                            data-bs-toggle="tooltip"
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

                    <div id="invoice_sidebar">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="mb-0">{{ __('Invoice') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        @permission('invoice create')
                                            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                                data-title="{{ __('Create Invoice') }}"
                                                data-url="{{ route('wosinvoice.create', ['wo_id' => $Workorder->id]) }}"
                                                data-toggle="tooltip" title="{{ __('Create') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        @endpermission
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice Cost') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Action') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($wosinvoice as $wosinvoice_val)
                                                <tr>
                                                    <td style="width: 10%">
                                                        <a>{{ $wosinvoice_val->invoice_cost }}</a>
                                                    </td>
                                                    <td style="white-space: inherit">
                                                        <a>{{ $wosinvoice_val->description }}</a>
                                                    </td>

                                                    @php
                                                        $woinvoice = get_file('/');
                                                    @endphp
                                                    <td class="action" style="width: 10%">
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ $woinvoice . $wosinvoice_val->invoice_file }}"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                download="workorder invoice">
                                                                <i class="ti ti-download text-white"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Download') }}"></i>
                                                            </a>

                                                        </div>

                                                        <div class="action-btn bg-secondary ms-2">
                                                            <a class="mx-3 btn btn-sm align-items-center"
                                                                href="{{ $woinvoice . $wosinvoice_val->invoice_file }}"
                                                                target="_blank">
                                                                <i class="ti ti-crosshair text-white"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Preview') }}"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-url="{{ route('wosinvoice.edit', $wosinvoice_val->id) }}"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Edit Invoice') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['wosinvoice.destroy', $wosinvoice_val->id], 'class' => 'm-0']) !!}
                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm  align-items-center show_confirm ">
                                                                <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Delete') }}"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="document_sidebar">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Documents and Picture') }}</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="card-body height-450">
                                    <div class="card-body bg-none top-5-scroll responsive_padding">
                                        <div class="col-md-12 dropzone browse-file" id="dropzonewidget"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="comment_sidebar">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="mb-0">{{ __('Comment') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                            data-title="{{ __('Create Comment') }}"
                                            data-url="{{ route('woscomment.create', ['wo_id' => $Workorder->id]) }}"
                                            data-toggle="tooltip" title="{{ __('Create') }}">
                                            <i class="ti ti-plus text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Image') }}</th>
                                                <th> {{ __('Description') }}</th>
                                                <th> {{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($woscomment as $woscomment_val)
                                                <tr>
                                                    <a href="#">
                                                        <div class="comment_section">
                                                            @php
                                                                $avatar = get_file('/');
                                                            @endphp
                                                            <td width="100">
                                                                <a href="@if ($woscomment_val->file) {{ $avatar . '/' . $woscomment_val->file }} @else {{ asset($avatar . 'placeholder.jpg') }} @endif"
                                                                    target="_blank">
                                                                    <img src="@if ($woscomment_val->file) {{ $avatar . '/' . $woscomment_val->file }} @else {{ asset($avatar . 'placeholder.jpg') }} @endif"
                                                                        width="60" height="60"
                                                                        class="rounded-circle" />
                                                                </a>
                                                            </td>
                                                            <td style="white-space: inherit">
                                                                <p class="">
                                                                    {{ $woscomment_val->description }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'class' => 'm-0', 'route' => ['woscomment.destroy', $woscomment_val->id]]) !!}
                                                                    <a href="#!"
                                                                        class="mx-3 btn btn-sm  align-items-center show_confirm ">
                                                                        <i class="ti ti-trash text-white"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                                    </a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </td>
                                                        </div>
                                                    </a>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone-min.js') }}"></script>

    <script>
        @if (Auth::user()->type != 'Client')
            Dropzone.autoDiscover = false;
            myDropzone = new Dropzone("#dropzonewidget", {
                maxFiles: 20,
                maxFilesize: 20,
                parallelUploads: 1,
                filename: false,
                url: "{{ route('workorder.file.upload', $Workorder->id) }}",
                success: function(file, response) {
                    if (response.is_success) {
                        if (response.status == 1) {
                            toastrs('success', response.success_msg, 'success');
                        } else {
                            dropzoneBtn(file, response);
                            toastrs('Success', '{{ __('Attachment Create Successfully!') }}', 'success')
                        }

                    } else {
                        myDropzone.removeFile(file);
                        toastrs('{{ __('Error') }}', 'File type must be match with Storage setting.',
                            'error');
                    }
                },

            });
            myDropzone.on("sending", function(file, xhr, formData) {
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formData.append("lead_id", {{ $Workorder->id }});
            });


            function dropzoneBtn(file, response) {
                var del = document.createElement('a');
                del.setAttribute('href', response.delete);
                del.setAttribute('class', "action-btn bg-danger ms-2 mx-3 mt-2 btn btn-sm align-items-center");
                del.setAttribute('data-toggle', "tooltip");
                del.setAttribute('data-original-title', "{{ __('Delete') }}");
                del.innerHTML = "<i class='ti ti-trash text-white'></i>";

                var download = document.createElement('a');
                download.setAttribute('href', response.download);
                download.setAttribute('class', "action-btn bg-info mt-2 btn btn-sm align-items-center");
                download.setAttribute('data-toggle', "tooltip");
                download.setAttribute('data-original-title', "{{ __('Download') }}");
                download.innerHTML = "<i class='ti ti-download text-white'></i>";


                del.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (confirm("Are you sure ?")) {
                        var btn = $(this);
                        $.ajax({
                            url: btn.attr('href'),
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'DELETE',
                            success: function(response) {
                                if (response.is_success) {
                                    toastrs('Success', '{{ __('Attachment Deleted Successfully') }}',
                                        'success');
                                    btn.closest('.dz-image-preview').remove();
                                } else {
                                    toastrs('Error', response.error, 'error');
                                }
                            },
                            error: function(response) {
                                response = response.responseJSON;
                                if (response.is_success) {
                                    toastrs('Error', response.error, 'error');
                                } else {
                                    toastrs('Error', response, 'error');
                                }
                            }
                        })
                    }
                });

                var html = document.createElement('div');
                html.appendChild(download);
                html.appendChild(del);

                file.previewTemplate.appendChild(html);
            }

            @foreach ($Workorder_file as $file)
                @if ($file)
                    var mockFile = {
                        name: "{{ $file->image }}",
                        size: {{ \File::size(base_path() . '/uploads/workorder_files/' . $file->image) }}
                    };
                    var file_extension = "{{ \File::extension(storage_path('workorder_files/' . $file->image)) }}";
                    if (file_extension == "png" || file_extension == "jpg" || file_extension == "jpeg") {
                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile,
                            "{{ get_file('uploads/workorder_files/' . $file->image) }}");
                        myDropzone.emit("complete", mockFile);
                    }
                    if (file_extension == "pdf") {
                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile, "{{ asset('assets/img/icons/files/pdf.png') }}");
                        myDropzone.emit("complete", mockFile);
                    }
                    if (file_extension == "docx" || file_extension == "doc") {
                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile, "{{ asset('assets/img/icons/files/doc.png') }}");
                        myDropzone.emit("complete", mockFile);
                    }

                    dropzoneBtn(mockFile, {
                        download: "{{ route('workorder.file.download', $file->id) }}",
                        delete: "{{ route('workorder.file.delete', $file->id) }}"
                    });
                @endif
            @endforeach
        @endif
    </script>

    <script>
        $('#work_status').on('change', function() {

            var workstatus = $('#work_status').val();
            var wosid = $('#wosid').val();
            $.ajax({
                type: "POST",
                url: "{{ route('wos.workstatus') }}",
                data: {
                    work_status: workstatus,
                    wos_id: wosid
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    toastrs('Success', '{{ __('Work Order Status Change Successfully') }}', 'success');
                }
            });
        });
    </script>

    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        (function() {
            var options = {
                chart: {
                    height: 130,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                    name: '{{ __('Order') }}',
                    data: {!! json_encode($chartData['label']) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($chartData['label']) !!},
                },
                colors: ['#ffa21d'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },

            };
            var chart = new ApexCharts(document.querySelector("#visitors-chart"), options);
            chart.render();
        })();
    </script>
    <script>
        (function() {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($arrPartsper) !!},
                colors: ['#FF3A6E', '#3ec9d6'],
                labels: {!! json_encode($arrPartsLabel) !!},
                legend: {
                    show: false
                }
            };
            var chart = new ApexCharts(document.querySelector("#projects-chart"), options);
            chart.render();
        })();
    </script>
    <script>
        if ($('#useradd-sidenav').length > 0) {
            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300,
            });
        }
    </script>
@endpush
