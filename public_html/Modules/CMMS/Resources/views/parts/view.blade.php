@extends('layouts.main')
@section('page-title')
    {{ __('Parts Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Parts') }} , {{ __('Parts Details') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush


@section('content')
    <div class="row">
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#useradd-0"
                                    class="list-group-item list-group-item-action border-0 active">{{ __('Overview') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-1"
                                    class="list-group-item list-group-item-action border-0">{{ __('Reports') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-2"
                                    class="list-group-item list-group-item-action border-0">{{ __('Component') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-3"
                                    class="list-group-item list-group-item-action border-0">{{ __('Supplier') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-4"
                                    class="list-group-item list-group-item-action border-0">{{ __('POs') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-5"
                                    class="list-group-item list-group-item-action border-0">{{ __('Log Time') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9">
                        <div id="useradd-0">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <div class="card_img text-center">
                                                @php
                                                    $avatar = get_file('Component/thumbnail/');
                                                @endphp
                                                <img class="img_setting seo_image" src="{{ get_file($Parts->thumbnail) }}"
                                                    alt="{{ $Parts->name }}">
                                            </div>
                                            <h6 class="mt-3 text-center"> {{ $Parts->name }} </h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 d-flex">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5>{{ __('Details') }}</h5>
                                            <div class="row  mt-4">
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Name') }}</dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $Parts->name }}
                                                </dd>
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Number') }}</dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $Parts->number }}
                                                </dd>
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Quantity') }}</dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $Parts->quantity }}
                                                </dd>
                                                @php
                                                    $site_currency_symbol_position = isset($setting['site_currency_symbol_position']) ? $setting['site_currency_symbol_position'] : 'pre';
                                                @endphp
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Price') }}</dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $Parts->price }}
                                                </dd>
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Category') }}</dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $Parts->category }}
                                                </dd>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="useradd-1">
                            <div class="card">
                                <div class="card-header">

                                    <h5 class="mb-0">{{ __('Reports') }}</h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row align-items-center justify-content-between">
                                                        <div class="col-auto mb-3 mb-sm-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="theme-avtar bg-warning">
                                                                    <i class="ti ti-shopping-cart"></i>
                                                                </div>
                                                                <div class="ms-3">
                                                                    <small class="text-muted">{{ __('Total') }}</small>
                                                                    <h6 class="m-0">{{ __('Parts Used') }}</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto text-end">
                                                            <h4 class="m-0">{{ $total_parts_purchase }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row align-items-center justify-content-between">
                                                        <div class="col-auto mb-3 mb-sm-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="theme-avtar bg-primary">
                                                                    <i class="ti ti-shopping-cart"></i>
                                                                </div>
                                                                <div class="ms-3">
                                                                    <small class="text-muted">{{ 'Total' }}</small>
                                                                    <h6 class="m-0">{{ 'Cost' }}</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto text-end">
                                                            <h4 class="m-0">
                                                                {{ !empty($total_cost->total_cost) ? $total_cost->total_cost : 0 }}
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="useradd-2">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="mb-0">{{ __('Components') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            @permission('components create')
                                                <a class="btn btn-sm btn-primary btn-icon m-1" data-ajax-popup="true"
                                                    data-size="md" data-title="{{ __('Associate Component') }}"
                                                    data-url="{{ route('component.associate.create', ['parts_component', $Parts->id]) }}"
                                                    data-toggle="tooltip" title="{{ __('Associate') }}">
                                                    <i class="ti ti-plus text-white"></i>
                                                </a>
                                            @endpermission
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table class="table dataTable3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Components Thumbnail') }}</th>
                                                <th class="text-center">{{ __('Name') }}</th>
                                                <th class="text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($componenets as $componenets_val)
                                                <tr>
                                                    @php
                                                        $thumbnail = !empty($componenets_val->thumbnail) ? '/' . $componenets_val->thumbnail : 'avatar/placeholder.jpg';
                                                    @endphp
                                                    <td width="100">
                                                        <a href="{{ get_file($thumbnail) }}" target="-blank"><img
                                                                src="{{ get_file($thumbnail) }}" width="60"
                                                                height="60" class="rounded-circle"></a>
                                                    </td>
                                                    <td class="text-center">{{ $componenets_val->name }}</td>
                                                    <td class="action w-10">
                                                        <span>

                                                            @permission('components delete')
                                                                <div class="action-btn bg-danger ms-2 float-end">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['component.associate_remove', $module, $componenets_val->id]]) !!}
                                                                    {!! Form::hidden('parts_id', $Parts->id) !!}
                                                                    <a href="#!"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
                                                                        <i class="ti ti-trash text-white"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                                    </a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            @endpermission

                                                            @permission('components show')
                                                                <div class="action-btn bg-warning ms-2 float-end">
                                                                    <a href="{{ route('component.show', [$componenets_val->id]) }}"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                        data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                                        data-bs-whatever="{{ __('View Component') }}">
                                                                        <i class="ti ti-eye text-white"></i>
                                                                    </a>
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

                        <div id="useradd-3">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="mb-0">{{ __('Suppliers') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            @permission('suppliers associate')
                                                <div class="float-end">
                                                    <a class="btn btn-sm btn-primary btn-icon m-1" data-ajax-popup="true"
                                                        data-size="md" data-title="{{ __('Associate Supplier') }}"
                                                        data-url="{{ route('supplier.associate.create', ['parts_supplier', $Parts->id]) }}"
                                                        data-toggle="tooltip" title="{{ __('Create') }}">
                                                        <i class="ti ti-plus text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table class="table dataTable3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Supplier Thumbnail') }}</th>
                                                <th class="text-center">{{ __('Name') }}</th>
                                                <th class="text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suppliers as $supplier)
                                                <tr>
                                                    <td width="100">
                                                        <a href="{{ get_file($supplier->thumbnail) }}" target="-blank">
                                                            <img src="{{ get_file($supplier->thumbnail) }}"
                                                                width="60" height="60" class="rounded-circle"></a>
                                                    </td>
                                                    <td class="text-center">{{ $supplier->name }}</td>
                                                    <td class="action">
                                                        <span>
                                                            @permission('suppliers delete')
                                                                <div class="action-btn bg-danger ms-2 float-end">
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['supplier.associate_remove', 'parts_supplier', $supplier->id],
                                                                    ]) !!}
                                                                    {!! Form::hidden('supplier_id', $Parts->id) !!}
                                                                    <a href="#!"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
                                                                        <i class="ti ti-trash text-white"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                                    </a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            @endpermission

                                                            @permission('suppliers show')
                                                                <div class="action-btn bg-warning ms-2 float-end">
                                                                    <a href="{{ route('supplier.show', [$supplier->id]) }}"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                        data-bs-whatever="{{ __('View Supplier') }}"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('View') }}">
                                                                        <span class="text-white"> <i
                                                                                class="ti ti-eye"></i></span></a>
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

                        <div id="useradd-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="mb-0">{{ __('POs') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            @permission('POs purchase order create')
                                                <div class="float-end">
                                                    <a href="{{ route('cmms_pos.create', ['partsid' => $Parts->id]) }}"
                                                        class="btn btn-sm btn-primary btn-icon m-1" data-size="lg"
                                                        data-bs-whatever="{{ __('Create POs') }}"
                                                        data-bs-title="{{ __(' Create') }}" data-bs-toggle="tooltip">
                                                        <i class="ti ti-plus text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table class="table dataTable3 ">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Supplier Name') }}</th>
                                                <th>{{ __('User Name') }}</th>
                                                <th>{{ __('Purchase Order Date') }}</th>
                                                <th>{{ __('Expected Delivery Date') }}</th>
                                                <th class="text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($parts_pos as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->supplier_name }}</td>
                                                    <td>{{ $invoice->user_name }}</td>
                                                    <td>{{ company_date_formate($invoice->pos_date) }}
                                                    </td>

                                                    <td>{{ company_date_formate($invoice->delivery_date) }}
                                                    </td>

                                                    <td class="Action w-10">
                                                        <span>
                                                            @permission('POs purchase order delete')
                                                                <div class="action-btn bg-danger ms-2 float-end">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['cmms_pos.destroy', $invoice->id]]) !!}
                                                                    <a href="#!"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
                                                                        <i class="ti ti-trash text-white"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                                    </a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            @endpermission
                                                            @permission('POs purchase order edit')
                                                                <div class="action-btn bg-info ms-2 float-end">
                                                                    <a href="{{ route('cmms_pos.edit', \Crypt::encrypt($invoice->id)) }}"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                        data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                        data-bs-whatever="{{ __('Edit') }}">
                                                                        <i class="ti ti-edit text-white"></i>
                                                                    </a>
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

                        <div id="useradd-5">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="mb-0">{{ __('Log Time') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            @permission('logtime create')
                                                <div class="float-end">
                                                    <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Create Log Time') }}"
                                                        data-url="{{ route('partslogtime.create', ['parts_id' => $Parts->id]) }}"
                                                        data-toggle="tooltip" title="{{ __('Create') }}">
                                                        <i class="ti ti-plus text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table class="table dataTable3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Description') }}</th>
                                                <th class="text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($partslogtime as $partslogtime_val)
                                                <tr>

                                                    <td style="white-space: inherit;">
                                                        <a href="#" data-size="lg"
                                                            data-url="{{ route('partslogtime.edit', $partslogtime_val->id) }}"
                                                            data-ajax-popup="true" data-size="md"
                                                            data-bs-whatever="{{ __('Edit Log Time') }}"
                                                            data-bs-toggle="tooltip" title="{{ __('Edit Log Time') }}"
                                                            data-title="{{ __('Edit Log Time') }}"
                                                            data-bs-toggle="tooltip" class="text-dark">
                                                            <i class="ti ti-clock"></i>
                                                            {{ company_date_formate($partslogtime_val->date) }}
                                                            - {{ $partslogtime_val->description }}
                                                        </a>
                                                    </td>

                                                    <td class="action w-10">
                                                        <span>
                                                            <div class="action-btn bg-danger ms-2 float-end">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['partslogtime.destroy', $partslogtime_val->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
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
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        if ($('#useradd-sidenav').length > 0) {
            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300,
            });
        }
    </script>
@endpush
