@extends('layouts.main')
@section('page-title')
    {{ __('Supplier Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Supplier') }} , {{ __('Supplier Details') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush

@section('content')
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
                            @permission('parts manage')
                                <a href="#useradd-1" class="list-group-item list-group-item-action border-0">{{ __('Parts') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endpermission
                            @permission('components manage')
                                <a href="#useradd-2"
                                    class="list-group-item list-group-item-action border-0">{{ __('Component') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endpermission
                            @permission('POs purchase order manage')
                                <a href="#useradd-3" class="list-group-item list-group-item-action border-0">{{ __('POs') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endpermission
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div id="useradd-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card gridBox rounded-25 hover-shadow-lg p-0 card_height">
                                    <div class="card-body text-center">
                                        <div class="card_img text-center">
                                            @php
                                                $Supplier_img = $Supplier->image ?? 'avatar/placeholder.jpg';
                                            @endphp
                                            <img class="img_setting seo_image" src="{{ get_file($Supplier_img) }}"
                                                alt="{{ $Supplier->name }}">
                                        </div>
                                        <h6 class="mt-3 text-center"> {{ $Supplier->name }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 d-flex">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>{{ __('Details') }}</h5>
                                        <div class="row mt-4">
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Name') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Supplier->name }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Contact') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Supplier->contact }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Email') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Supplier->email }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Phone') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Supplier->phone }}
                                            </dd>
                                            <dt class="col-lg-4 h6 text-lg">{{ __('Address') }}</dt>
                                            <dd class="col-lg-8 text-lg">
                                                {{ $Supplier->address }}
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
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="mb-0">{{ __('Parts') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        @permission('parts associate')
                                            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                                data-title="{{ __('Associate Parts') }}"
                                                data-url="{{ route('parts.associate.create', ['suppliers', $Supplier->id]) }}"
                                                data-toggle="tooltip" title="{{ __('Associate') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        @endpermission
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table dataTable3 mt-3">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Parts Thumbnail') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th class="text-end">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parts as $parts_val)
                                            <tr>
                                                @php
                                                    $thumbnail = !empty($parts_val->thumbnail) ? $parts_val->thumbnail : 'avatar/placeholder.jpg';
                                                @endphp
                                                <td width="100">
                                                    <a href="{{ get_file($thumbnail) }}" target="-blank">
                                                        <img src="{{ get_file($thumbnail) }}" width="60" height="60"
                                                            class="rounded-circle">

                                                    </a>
                                                </td>
                                                <td>{{ $parts_val->name }}</td>
                                                <td class="action w-10">
                                                    <span>

                                                        @permission('parts delete')
                                                            <div class="action-btn bg-danger ms-2 float-end">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['parts.associate_remove', $module, $parts_val->id]]) !!}
                                                                {!! Form::hidden('supplier_id', $Supplier->id) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
                                                                    <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Delete') }}"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        @endpermission


                                                        @permission('parts show')
                                                            <div class="action-btn bg-warning ms-2 float-end">
                                                                <a href="{{ route('parts.show', [$parts_val->id]) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                                    data-bs-whatever="{{ __('View Parts') }}">
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

                    <div id="useradd-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5 class="mb-0">{{ __('Component') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        @permission('components associate')
                                            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                                data-title="{{ __('Associate Components') }}"
                                                data-url="{{ route('component.associate.create', ['suppliers', $Supplier->id]) }}"
                                                data-toggle="tooltip" title="{{ __('Associate') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        @endpermission
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table dataTable3 mt-3">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Components Thumbnail') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th class="text-end">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($components as $component)
                                            <tr>
                                                @php
                                                    $thumbnail = !empty($component->thumbnail) ? $component->thumbnail : 'avatar/placeholder.jpg';

                                                @endphp
                                                <td width="100">
                                                    <a href="{{ get_file($thumbnail) }}" target="-blank"> <img
                                                            src="{{ get_file($thumbnail) }}" width="60"
                                                            height="60" class="rounded-circle"></a>
                                                </td>
                                                <td>{{ $component->name }}</td>
                                                <td class="action w-10">
                                                    <span>

                                                        @permission('components delete')
                                                            <div class="action-btn bg-danger ms-2 float-end">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['component.associate_remove', $module, $component->id]]) !!}
                                                                {!! Form::hidden('supplier_id', $Supplier->id) !!}

                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
                                                                    <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Delete') }}"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        @endpermission

                                                        @permission('components show')
                                                            <div class="action-btn bg-warning ms-2 float-end">
                                                                <a href="{{ route('component.show', [$component->id]) }}"
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
                                        <h5 class="mb-0">{{ __('Pos') }}</h5>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                        @permission('POs purchase order create')
                                            <a href="{{ route('cmms_pos.create', ['supplier_id' => $Supplier->id]) }}"
                                                class="btn btn-sm btn-primary btn-icon m-1"
                                                data-bs-whatever="{{ __('Associate Pos') }}"> <span class="text-white">
                                                    <i class="ti ti-plus" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Associate') }}"></i></span>
                                            </a>
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
                                        @foreach ($supplier_pos as $invoice)
                                            <tr>
                                                <td>{{ $invoice->supplier_name }}</td>
                                                <td>{{ $invoice->user_name }}</td>
                                                <td>{{ company_date_formate($invoice->pos_date) }}</td>
                                                <td>{{ company_date_formate($invoice->delivery_date) }}</td>

                                                <td class="action w-10">
                                                    <span>
                                                        @permission('POs purchase order edit')
                                                            <div class="action-btn bg-info ms-2 float-end">
                                                                <a href="{{ route('cmms_pos.edit', \Crypt::encrypt($invoice->id)) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-bs-whatever="{{ __('Edit') }}">
                                                                    <i class="ti ti-edit text-white "></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                        @permission('POs purchase order delete')
                                                            <div class="action-btn bg-danger ms-2 float-end">

                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['cmms_pos.destroy', $invoice->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm">
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#setData").trigger('click');
            }, 30);
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            var tab = 'parts';
            @if ($tab = Session::get('tab-status'))
                var tab = '{{ $tab }}';
            @else
                var tab_name = $('#tabs li a:eq(0)').attr('data-href');
                var tab = tab_name.replace("#tabs-", "");
            @endif
            var nav_tab = '';
            @if ($nav_tab = Session::get('nav-status'))
                var nav_tab = '{{ $nav_tab }}';
            @endif

            setTimeout(function() {
                $("#tabs .list-group-list[data-href='#tabs-" + tab + "']").trigger("click");
                if (nav_tab != '') {
                    $(".nav-item .nav-link[href='#" + nav_tab + "_navigation']").trigger("click");
                }
            }, 10);




            @if (Session::has('success') && Session::has('id') && !empty(Session::get('id')))
                show_toastr('Success', '{{ Session::get('success') }}', 'success');
                $("#tabs-integrations").find("#{{ Session::get('id') }}").trigger("click");
                {{ Session::forget('success') }}
                {{ Session::forget('id') }}
            @endif

            $('.list-group-list').on('click', function() {
                var href = $(this).attr('data-href');
                $('.tabs-card').addClass('d-none');
                $(href).removeClass('d-none');
                $('#tabs .list-group-list').removeClass('text-primary');
                $(this).addClass('text-primary');
            });
        });
    </script> --}}

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
