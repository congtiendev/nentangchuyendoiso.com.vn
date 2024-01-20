@extends('layouts.main')
@section('page-title')
    {{ __('Component Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Component') }} , {{ __('Component Details') }}
@endsection


@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush

@push('css')
    <link rel="stylesheet"
        href="{{ asset('Modules/CMMS/Resources/assets/custom/css/datatable/buttons.dataTables.min.css') }}">
@endpush
@section('page-action')
@endsection


@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">{{ __('Image') }}</h4>
                    <div class="card_img text-center">
                        <img class="img_setting seo_image" src="{{ get_file($Components->thumbnail) }}"
                            alt="{{ $Components->name }}" class="w-100">
                    </div>
                    <h5 class="mt-2 text-center"> {{ $Components->name }} </h5>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5> {{ __('Description') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                @if (count($ComponentsField) > 0)
                                    @foreach ($ComponentsField as $detail)
                                        @if ($detail->name == 'Documents and Picture')
                                        @elseif($detail->name == 'Warranty Document')
                                            <div class="col-lg-4 mb-2">
                                                <h5 class="text-sm">{{ $detail->name }}</h5>
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ get_file($detail->value) }}" target="_blank" download
                                                        class="Warranty_Document" style="">
                                                        <i class=" ti ti-download text-white" data-bs-toggle="tooltip"
                                                            data-bs-original-title="Downlode"></i>
                                                    </a>
                                                </div>

                                                <div class="action-btn bg-secondary ms-2">
                                                    <a class="mx-3 btn btn-sm align-items-center"
                                                        href="{{ get_file($detail->value) }}" target="_blank">
                                                        <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Preview') }}"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        @else
                                            <div class="col-lg-4 mb-2">
                                                <span>
                                                    <h5 class="text-sm">{{ $detail->name }}</h5>
                                                    @if ($detail->name == 'Assigned Date' || $detail->name == 'Warranty Exp Date')
                                                        <p class="detail_name">
                                                            {{ $detail->value }}
                                                        </p>
                                                    @else
                                                        <p class="detail_name">{{ $detail->value }}</p>
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <p> {!! DNS2D::getBarcodeHTML(route('component.show', $Components->id), 'QRCODE', 3, 3) !!} </p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>

        <div class="row ju stify-content-center">
            <!-- [ sample-page ] start -->
            <div class="col-lg-12 col-md-12 col-xxl-12">
                <div class="card">
                    <div class="p-3">
                        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-1" type="button">{{ __('Reports') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-2" type="button">{{ __('PMs') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-user-tab-3" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-3" type="button">{{ __('WOS') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-user-tab-4" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-4" type="button">{{ __('Parts') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-user-tab-5" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-5" type="button">{{ __('Suppliers') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-user-tab-6" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-6" type="button">{{ __('Log Time') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-user-tab-7" data-bs-toggle="pill"
                                    data-bs-target="#pills-user-7"
                                    type="button">{{ __('Documents and Picture') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-user-1" role="tabpanel"
                                aria-labelledby="pills-user-tab-1">
                                <h5 class="mb-0">{{ __('Recent Orders') }}</h5>
                                <small class="text-muted text-end">{{ __('Current Year Orders') }}</small>
                                <div id="traffic-chart"></div>
                            </div>

                            <div class="tab-pane fade" id="pills-user-2" role="tabpanel"
                                aria-labelledby="pills-user-tab-2">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h3 class="mb-0 ">{{ __('PMs') }}</h3>
                                    <span class="float-end">
                                        @permission('pms associate')
                                            <a class="btn btn-sm btn-primary btn-icon m-1" data-ajax-popup="true"
                                                data-size="md" data-title="{{ __('Associate PMs') }}"
                                                data-url="{{ route('parts.associate.create', ['pms', $Components->id]) }}"
                                                data-toggle="tooltip" title="{{ __('Associate PMs') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        @endpermission
                                    </span>
                                </div>
                                <div class="col-xl-12 mt-3">
                                    <div class="">
                                        <div class="card-header table-border-style">
                                            <h5></h5>
                                            <div class="table-responsive">
                                                <table class="table pc-dt-simple" id="pc-dt-simple">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Name') }}</th>
                                                            <th class="text-end"> {{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($pms as $pms_val)
                                                            <tr>
                                                                <td>{{ $pms_val->name }}</td>
                                                                <td class="action">
                                                                    <span class="">

                                                                        @permission('pms delete')
                                                                            <div class="action-btn bg-danger ms-2 float-end">
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'class' => 'm-0',
                                                                                    'route' => ['parts.associate_remove', $module_pms, $pms_val->id],
                                                                                ]) !!}
                                                                                {!! Form::hidden('components_id', $Components->id) !!}
                                                                                <a href="#!"
                                                                                    class="mx-3 btn btn-sm align-items-center show_confirm">
                                                                                    <i class="ti ti-trash text-white"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-original-title="{{ __('Delete') }}"></i>
                                                                                </a>
                                                                                {!! Form::close() !!}
                                                                            </div>
                                                                        @endpermission

                                                                        @permission('pms show')
                                                                            <div class="action-btn bg-warning ms-2 float-end">
                                                                                <a href="{{ route('pms.show', [$pms_val->id]) }}"
                                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                                    data-bs-whatever="{{ __('View PMs') }}">
                                                                                    <i class="ti ti-eye text-white"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-original-title="{{ __('View') }}"></i></a>
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

                            <div class="tab-pane fade" id="pills-user-3" role="tabpanel"
                                aria-labelledby="pills-user-tab-3">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{ __('WOs') }}</h4>
                                    @permission('workorder create')
                                        <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                                            data-title="{{ __('Add WOs') }}"
                                            data-url="{{ route('workorder.create', ['components_id' => $Components->id]) }}"
                                            data-toggle="tooltip" title="{{ __('Create') }}">
                                            <i class="ti ti-plus text-white"></i>
                                        </a>
                                    @endpermission
                                </div>
                                <div class="col-xl-12 mt-3">
                                    <div class="">
                                        <div class="card-header table-border-style">
                                            <h5></h5>
                                            <div class="table-responsive">
                                                <table class="table pc-dt-simple" id="pc-dt-simple">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Work Order Name') }}</th>
                                                            <th>{{ __('Priority') }}</th>
                                                            <th>{{ __('Instructions') }}</th>
                                                            <th> {{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $prioritys = Modules\CMMS\Entities\Workorder::priority();
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($wos as $wos_val)
                                                            <tr>
                                                                <td>{{ $wos_val->wo_name }}</td>
                                                                <td>

                                                                    @foreach ($prioritys as $priority)
                                                                        @if ($priority['priority'] == $wos_val->priority)
                                                                            <span
                                                                                class="badge bg-danger p-2 px-3 rounded event-warning">
                                                                                {{ $wos_val->priority }}</span>
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td>{{ $wos_val->instructions }}</td>
                                                                <td class="action" style="width: 10%">
                                                                    <span>
                                                                        <div class="action-btn bg-warning">
                                                                            <a href="{{ route('workorder.show', [$wos_val->id]) }}"
                                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                                data-bs-whatever="{{ __('View WOs') }}"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ __('View WOs') }}"
                                                                                data-bs-original-title="{{ __('View WOs') }}">
                                                                                <span class="text-white"> <i
                                                                                        class="ti ti-eye"></i></span></a>
                                                                        </div>
                                                                        <div class="action-btn bg-danger ms-2">
                                                                            {!! Form::open(['method' => 'DELETE', 'class' => 'm-0', 'route' => ['workorder.destroy', $wos_val->id]]) !!}
                                                                            <a href="#!"
                                                                                class="mx-3 btn btn-sm align-items-center show_confirm ">
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
                            </div>

                            <div class="tab-pane fade" id="pills-user-4" role="tabpanel"
                                aria-labelledby="pills-user-tab-4">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{ __('Associated Parts') }}</h4>
                                    @permission('parts associate')
                                        <a class="btn btn-sm btn-primary btn-icon m-1" data-ajax-popup="true" data-size="md"
                                            data-title="{{ __('Create New Parts') }}"
                                            data-url="{{ route('parts.associate.create', ['parts', $Components->id]) }}"
                                            data-toggle="tooltip" title="{{ __('Create') }}">
                                            <i class="ti ti-plus text-white"></i>
                                        </a>
                                    @endpermission
                                </div>

                                <div class="col-xl-12 mt-3">
                                    <div class="">
                                        <div class="card-header table-border-style">
                                            <h5></h5>
                                            <div class="table-responsive">
                                                <table class="table pc-dt-simple" id="pc-dt-simple">
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
                                                                @php
                                                                    $avatar = get_file('Component/thumbnail/');
                                                                @endphp
                                                                <td width="100">
                                                                    <a href="@if ($parts_val->thumbnail) {{ get_file($parts_val->thumbnail) }} @else {{ asset($avatar . 'placeholder.jpg') }} @endif"
                                                                        target="_blank">
                                                                        <img src="@if ($parts_val->thumbnail) {{ get_file($parts_val->thumbnail) }} @else {{ asset($avatar . 'placeholder.jpg') }} @endif"
                                                                            width="60" height="60"
                                                                            class="rounded-circle" />
                                                                </td>
                                                                </a>
                                                                </td>
                                                                <td>{{ $parts_val->name }}</td>
                                                                <td class="action" style="width: 10%">
                                                                    <span>
                                                                        @permission('parts show')
                                                                            <div class="action-btn bg-warning ms-2">
                                                                                <a href="{{ route('parts.show', [$parts_val->id]) }}"
                                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                                    data-bs-whatever="{{ __('View Parts') }}"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('View Parts') }}"
                                                                                    data-bs-original-title="{{ __('View Parts') }}">
                                                                                    <span class="text-white"> <i
                                                                                            class="ti ti-eye"></i></span></a>
                                                                            </div>
                                                                        @endpermission
                                                                        @permission('parts delete')
                                                                            <div class="action-btn bg-danger ms-2">
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'class' => 'm-0',
                                                                                    'route' => ['parts.associate_remove', $module_components, $parts_val->id],
                                                                                ]) !!}
                                                                                {!! Form::hidden('components_id', $Components->id) !!}
                                                                                <a href="#!"
                                                                                    class="mx-3 btn btn-sm align-items-center show_confirm ">
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
                            </div>

                            <div class="tab-pane fade" id="pills-user-5" role="tabpanel"
                                aria-labelledby="pills-user-tab-4">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{ __('Associate Supplier') }}</h4>
                                    @permission('suppliers associate')
                                        <a class="btn btn-sm btn-primary btn-icon m-1" data-ajax-popup="true" data-size="md"
                                            data-title="{{ __('Create New Supplier') }}"
                                            data-url="{{ route('supplier.associate.create', ['component_supplier', $Components->id]) }}"
                                            data-toggle="tooltip" title="{{ __('Create') }}">
                                            <i class="ti ti-plus text-white"></i>
                                        </a>
                                    @endpermission
                                </div>

                                <div class="col-xl-12 mt-3">
                                    <div class="">
                                        <div class="card-header table-border-style">
                                            <h5></h5>
                                            <div class="table-responsive">
                                                <table class="table pc-dt-simple" id="pc-dt-simple">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Supplier Thumbnail') }}</th>
                                                            <th>{{ __('Name') }}</th>
                                                            <th class="text-end">{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($suppliers as $supplier)
                                                            <tr>
                                                                <td>
                                                                    <a href="@if ($supplier->thumbnail) {{ get_file($supplier->thumbnail) }} @else {{ asset('uploads/Components/thumbnail/placeholder.jpg') }} @endif"
                                                                        target="-blank"><img
                                                                            src="@if ($supplier->thumbnail) {{ get_file($supplier->thumbnail) }} @else {{ asset('uploads/Components/thumbnail/placeholder.jpg') }} @endif"
                                                                            width="60" height="60"
                                                                            class="rounded-circle" />
                                                                    </a>
                                                                </td>
                                                                <td>{{ $supplier->name }}</td>
                                                                <td class="action text-end">
                                                                    <span>
                                                                        @permission('suppliers show')
                                                                            <div class="action-btn bg-warning ms-2">
                                                                                <a href="{{ route('supplier.show', [$supplier->id]) }}"
                                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                                    data-bs-whatever="{{ __('View Supplier') }}"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('View Supplier') }}"
                                                                                    data-bs-original-title="{{ __('View Supplier') }}">
                                                                                    <span class="text-white"> <i
                                                                                            class="ti ti-eye"></i></span></a>
                                                                            </div>
                                                                        @endpermission

                                                                        @permission('suppliers delete')
                                                                            <div class="action-btn bg-danger ms-2">
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'class' => 'm-0',
                                                                                    'route' => ['supplier.associate_remove', 'component_supplier', $supplier->id],
                                                                                ]) !!}
                                                                                {!! Form::hidden('components_id', $Components->id) !!}
                                                                                <a href="#!"
                                                                                    class="mx-3 btn btn-sm align-items-center show_confirm ">
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


                            </div>

                            <div class="tab-pane fade" id="pills-user-6" role="tabpanel"
                                aria-labelledby="pills-user-tab-4">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{ __('Log Time') }}</h4>
                                    @permission('logtime create')
                                        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal"
                                            data-title="{{ __('Create Log Time') }}"
                                            data-url="{{ route('componentslogtime.create', ['components_id' => $Components->id]) }}"
                                            data-size="md" data-ajax-popup="true" title="{{ __('Log Time') }}"
                                            data-bs-toggle="tooltip"> <i class="ti ti-plus text-white"></i>

                                        </a>
                                    @endpermission
                                </div>

                                <div class="col-xl-12 mt-3">
                                    <div class="">
                                        <div class="card-header table-border-style">
                                            <h5></h5>
                                            <div class="table-responsive">
                                                <table class="table pc-dt-simple" id="pc-dt-simple">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Description') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($componentslogtime as $logtime_val)
                                                            <tr>
                                                                <td style="white-space: inherit">
                                                                    <a href="#"
                                                                        data-title="{{ __('Edit Log Time') }}"
                                                                        data-url="{{ route('componentslogtime.edit', $logtime_val->id) }}"
                                                                        data-size="lg" data-ajax-popup="true"
                                                                        title="{{ __('Log Time') }}"
                                                                        data-bs-toggle="tooltip">

                                                                        <i class="far fa-clock"></i>
                                                                        {{ $logtime_val->hours }}
                                                                        {{ __('hr') }}
                                                                        {{ $logtime_val->minute }}
                                                                        {{ __('min') }}
                                                                        <span style="color: black"></span>
                                                                        {{ $logtime_val->name }}
                                                                        {{ company_date_formate($logtime_val->date) }}
                                                                        - {{ $logtime_val->description }}
                                                                    </a>
                                                                </td>
                                                                <td class="action" style="width: 10%">
                                                                    <span>
                                                                        @permission('logtime delete')
                                                                            <div class="action-btn bg-danger ms-2">
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'class' => 'm-0',
                                                                                    'route' => ['componentslogtime.destroy', $logtime_val->id],
                                                                                ]) !!}
                                                                                <a href="#!"
                                                                                    class="mx-3 btn btn-sm align-items-center show_confirm ">
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




                            </div>

                            <div class="tab-pane fade" id="pills-user-7" role="tabpanel"
                                aria-labelledby="pills-user-tab-4">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{ __('Documents and Picture') }}</h4>

                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class=" height-450">
                                            <div class="card-body bg-none top-5-scroll">
                                                <div class="col-md-12 dropzone browse-file" id="dropzonewidget"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>

    @endsection

    @push('scripts')
        <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
        <script>
            (function() {
                var options = {
                    chart: {
                        height: 250,
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
                        data: {!! json_encode($chartData['data']) !!}
                    }, ],
                    xaxis: {
                        categories: {!! json_encode($chartData['label']) !!},
                        title: {
                            text: '{{ __('Month') }}'
                        }
                    },
                    colors: ['#ffa21d'],

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },
                };
                var chart = new ApexCharts(document.querySelector("#traffic-chart"), options);
                chart.render();
            })();


            //         var tab = 'pms';
        </script>

        <script src="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone-min.js') }}"></script>

        <script>
            //asset detail page in document and picture dropzone
            @if (Auth::user()->type != 'Client')
                Dropzone.autoDiscover = false;
                myDropzone = new Dropzone("#dropzonewidget", {
                    maxFiles: 20,
                    maxFilesize: 20,
                    parallelUploads: 1,
                    filename: false,
                    url: "{{ route('component.file.upload', $Components->id) }} ",
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
                    formData.append("lead_id", {{ $Components->id }});
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
                                        toastrs('Success', '{{ __('Attachment Delete Successfully!') }}',
                                            'success');
                                        btn.closest('.dz-image-preview').remove();
                                    } else {
                                        show_toastr('Error', response.error, 'error');
                                    }
                                },
                                error: function(response) {
                                    response = response.responseJSON;
                                    if (response.is_success) {
                                        show_toastr('Error', response.error, 'error');
                                    } else {
                                        show_toastr('Error', response, 'error');
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



                //
                var Asset_other_document_extesnion =
                    "{{ \File::extension(storage_path('Assets/' . !empty($Asset_Warranty_document->value))) }}";
                //all file which are added using dropzon is shown in doxument & picture tab those file data and file path
                @foreach ($Components_file as $file)
                    @if ($file)
                        // Create the mock file:
                        var mockFile = {
                            name: "{{ $file->value }}",
                            size: {{ \File::size(base_path() . '/uploads/documents_files/' . $file->value) }}
                        };

                        var file_extension = "{{ \File::extension(storage_path('documents_files/' . $file->value)) }}";

                        if (file_extension == "png" || file_extension == "jpg" || file_extension == "jpeg") {
                            // Call the default addedfile event handler
                            myDropzone.emit("addedfile", mockFile);
                            // And optionally show the thumbnail of the file:
                            myDropzone.emit("thumbnail", mockFile,
                                "{{ get_file('uploads/documents_files/' . $file->value) }}");
                            myDropzone.emit("complete", mockFile);
                        }
                        if (file_extension == "pdf") {
                            // Call the default addedfile event handler
                            myDropzone.emit("addedfile", mockFile);
                            // And optionally show the thumbnail of the file:
                            myDropzone.emit("thumbnail", mockFile, "{{ asset('assets/img/icons/files/pdf.png') }}");
                            myDropzone.emit("complete", mockFile);
                        }
                        if (file_extension == "docx" || file_extension == "doc") {
                            // Call the default addedfile event handler
                            myDropzone.emit("addedfile", mockFile);
                            // And optionally show the thumbnail of the file:
                            myDropzone.emit("thumbnail", mockFile, "{{ asset('assets/img/icons/files/doc.png') }}");
                            myDropzone.emit("complete", mockFile);
                        }


                        dropzoneBtn(mockFile, {
                            download: "{{ route('component.file.download', $file->id) }}",
                            delete: "{{ route('component.file.delete', $file->id) }}"
                        });
                    @endif
                @endforeach
            @endif
        </script>



        <script>
            function changetab(tabname) {
                var someTabTriggerEl = document.querySelector('button[data-bs-target="' + tabname + '"]');
                // bootstrap.Tab.getInstance(someTabTriggerEl).show();
                var actTab = new bootstrap.Tab(someTabTriggerEl);
                actTab.show();
            }
        </script>
    @endpush
