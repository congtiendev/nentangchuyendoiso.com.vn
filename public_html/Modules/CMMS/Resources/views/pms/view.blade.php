@extends('layouts.main')
@section('page-title')
    {{ __('Preventive Maintenance') }}
@endsection
@section('page-breadcrumb')
    {{ __('Pms') }} , {{ __('Pms Details') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/dropzone/dist/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Modules/CMMS/Resources/assets/jqueryform/css/demo.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('Modules/CMMS/Resources/assets/jqueryform/css/jquery.rateyo.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!-- details card-->
    <div class="row">
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#useradd-1"
                                    class="list-group-item list-group-item-action border-0 active">{{ __('Parts') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-2"
                                    class="list-group-item list-group-item-action border-0">{{ __('Instruction') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-3"
                                    class="list-group-item list-group-item-action border-0">{{ __('Invoice') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-4"
                                    class="list-group-item list-group-item-action border-0">{{ __('Log Time') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9">
                        <div id="useradd-1">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="mb-0">{{ __('Parts') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            @permission('parts create')
                                                <a class="btn btn-sm btn-primary"
                                                    data-url="{{ route('parts.associate.create', ['pms_parts', $Pms->id]) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                    title="" data-title="{{ __('Create') }}"
                                                    data-bs-original-title="{{ __('Create Part') }}">
                                                    <i class="ti ti-plus text-white"></i>
                                                </a>
                                            @endpermission
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body text-end">
                                    <table class="table dataTable3 mt-3">
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
                                                        $avatar = get_file('parts/thumbnail/');
                                                    @endphp
                                                    <td width="100">
                                                        <a href="#" class="hover-translate-y-n3 ">
                                                            <img src="{{ get_file($parts_val->thumbnail) }}"
                                                                alt="{{ $parts_val->name }}" width="60" height="60"
                                                                class="rounded-circle">
                                                        </a>
                                                    </td>
                                                    <td>{{ $parts_val->name }}</td>
                                                    <td class="action">
                                                        <span class="table_btn">
                                                            @permission('parts show')
                                                                <div class="action-btn bg-warning ms-2">
                                                                    <a href="{{ route('parts.show', [$parts_val->id]) }}"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('View') }}">
                                                                        <i class="ti ti-eye text-white"></i>
                                                                    </a>
                                                                </div>
                                                            @endpermission
                                                            @permission('parts delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['parts.associate_remove', $module, $parts_val->id],
                                                                        'id' => 'delete-form-' . $parts_val->id,
                                                                    ]) !!}
                                                                    {!! Form::hidden('pms_id', $Pms->id) !!}
                                                                    <a href="#!"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Delete') }}">
                                                                        <i class="ti ti-trash text-white"></i>
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

                        <div id="useradd-2">
                            <div class="card">
                                <div class="view_instruction">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <h5 class="mb-0">{{ __('Instruction') }}</h5>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                                <a href="javascript:;"
                                                    class="btn btn-sm btn-primary btn-icon add_instruction1"
                                                    data-toggle="tooltip" data-title="{{ __('Create Instruction') }}">
                                                    <i class="ti ti-plus text-white" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Add') }}"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="document_label">
                                        <div class="card-body">
                                            @if (!empty($view_instruction))
                                                @foreach ($view_instruction as $item)
                                                    @if ($item->type == 'text')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $item->label }} </label>
                                                            <input type={{ !empty($item->type) ? $item->type : '' }}
                                                                class={{ $item->className }}
                                                                placeholder='{{ !empty($item->placeholder) ? $item->placeholder : '' }}'
                                                                name={{ $item->name }} disabled
                                                                value={{ !empty($item->value) ? $item->value : '-' }}>
                                                        </div>
                                                    @endif
                                                    @if ($item->type == 'textarea')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $item->label }} </label>
                                                            <{{ $item->type }} class={{ $item->className }}
                                                                rows={{ !empty($item->rows) ? $item->rows : 0 }}
                                                                name={{ $item->name }} disabled>
                                                                {{ !empty($item->value) ? $item->value : '' }}
                                                                </{{ $item->type }}>
                                                        </div>
                                                    @endif
                                                    @if ($item->type == 'radio-group')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $item->label }}</label><br>
                                                            @foreach ($item->values as $value)
                                                                @php
                                                                    $selected = '';
                                                                    if ($value->selected == 'true') {
                                                                        $selected = 'checked';
                                                                    }
                                                                @endphp
                                                                <input type="radio" value={{ $value->value }} disabled
                                                                    {{ $selected }}>
                                                                <label>{{ $value->label }}</label>
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    @if ($item->type == 'checkbox-group')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $item->label }}</label><br>
                                                            @foreach ($item->values as $value)
                                                                @php
                                                                    $selected = '';
                                                                    if ($value->selected == 'true') {
                                                                        $selected = 'checked';
                                                                    }
                                                                @endphp
                                                                <input type="checkbox" name=""
                                                                    value={{ $value->value }} disabled
                                                                    {{ $selected }}>
                                                                <label>{{ $value->label }}</label>
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    @if ($item->type == 'date')
                                                        <div class="form-group">
                                                            <label class="form-label"> {{ $item->label }}</label>
                                                            <input type={{ $item->type }} class={{ $item->className }}
                                                                value={{ !empty($item->value) ? $item->value : '' }}
                                                                disabled>
                                                        </div>
                                                    @endif
                                                    @if ($item->type == 'number')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $item->label }}</label>
                                                            <input type={{ $item->type }}
                                                                placeholder='{{ !empty($item->placeholder) ? $item->placeholder : '' }}'
                                                                class={{ $item->className }} disabled
                                                                value={{ !empty($item->value) ? $item->value : '-' }}>
                                                        </div>
                                                    @endif
                                                    @if ($item->type == 'select')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $item->label }}</label>
                                                            <select name="{{ $item->name }}" id=""
                                                                aria-placeholder='{{ !empty($item->placeholder) ? $item->placeholder : '' }}'
                                                                class={{ $item->className }} disabled><br>
                                                                @foreach ($item->values as $value)
                                                                    <option value="{{ $value->value }}">
                                                                        {{ $value->label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                    @if ($item->type == 'file')
                                                        <div class="form-group">
                                                            <label class="form-label">{{ $item->label }}</label><br>

                                                            @if (!empty($item->extension))
                                                                @if ($item->extension == 'jpg' || $item->extension == 'png' || $item->extension == 'jpeg')
                                                                    @if (!empty($item->path))
                                                                        <a href="{{ asset(Storage::url($item->path)) }}"
                                                                            class="instruction_document" download="test">
                                                                            <img src="{{ asset(Storage::url($item->path)) }}"
                                                                                width="10%">
                                                                            <i class="fas fa-download"></i>
                                                                            <br>
                                                                        </a>
                                                                    @endif
                                                                @endif

                                                                @if ($item->extension == 'xlsx')
                                                                    <a href="{{ asset(Storage::url($item->path)) }}"
                                                                        class="instruction_document" download="test">
                                                                        <img src="{{ asset('assets/img/icons/files/xls.png') }}"
                                                                            alt="">
                                                                        <i class="fas fa-download"></i>
                                                                        <br>
                                                                    </a>
                                                                @endif

                                                                @if ($item->extension == 'zip' || $item->extension == 'rar')
                                                                    <a href="{{ asset(Storage::url($item->path)) }}"
                                                                        class="instruction_document" download="test">
                                                                        <img src="{{ asset('assets/img/icons/files/zip.png') }}"
                                                                            alt="">
                                                                        <i class="fas fa-download"></i>
                                                                        <br>
                                                                    </a>
                                                                @endif

                                                                @if ($item->extension == 'doc' || $item->extension == 'docx')
                                                                    <a href="{{ asset(Storage::url($item->path)) }}"
                                                                        class="instruction_document" download="test">
                                                                        <img src="{{ asset('assets/img/icons/files/doc.png') }}"
                                                                            alt="">
                                                                        <i class="fas fa-download"></i>
                                                                        <br>
                                                                    </a>
                                                                @endif

                                                                @if ($item->extension == 'pdf')
                                                                    <a href="{{ asset(Storage::url($item->path)) }}"
                                                                        class="instruction_document" download="test">
                                                                        <img src="{{ asset('assets/img/icons/files/pdf.png') }}"
                                                                            alt="">
                                                                        <i class="fas fa-download"></i>
                                                                        <br>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12 add_instruction_section " style="display: none; padding: 30px;">
                                    <div class="justify-content-between align-items-center d-flex">
                                        <h4 class="h4 font-weight-400 float-left instruction_heading">
                                            {{ __(' Add Instruction') }}</h4>
                                        <a href="javascript:;"
                                            class="btn btn-sm btn-primary btn-icon add_instruction1 mb-2"
                                            data-toggle="tooltip"
                                            data-title="{{ __('View Instruction') }}">{{ __('View') }}
                                        </a>
                                    </div>
                                    <section class="section ">
                                        <div class="section-body">
                                            {{ Form::model($form, ['route' => ['forms.design.update', $Pms->id], 'method' => 'PUT', 'id' => 'design-form', 'enctype' => 'multipart/form-data']) }}
                                            <div class="row">
                                                <div class="col-xl-12 order-xl-1">
                                                    <div class="bg-white">
                                                        <div class="">
                                                            <div class="">
                                                                <div class=" row">
                                                                    <div class="col-lg-12">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div id="stage1" class="build-wrap">
                                                                                </div>
                                                                                <input type="hidden" name="json"
                                                                                    value="{{ $form[0]['json'] }}">
                                                                                <br>
                                                                                <div class="action-buttons">
                                                                                    <button id="showData" class="d-none"
                                                                                        type="button">{{ __('Show Data') }}</button>
                                                                                    <button id="clearFields"
                                                                                        class="d-none"
                                                                                        type="button">{{ __('Clear All Fields') }}</button>
                                                                                    <button id="getData" class="d-none"
                                                                                        type="button">{{ __('Get Data') }}</button>
                                                                                    <button id="getXML" class="d-none"
                                                                                        type="button">{{ __('Get XML Data') }}</button>
                                                                                    <button id="getJSON"
                                                                                        class="btn btn-sm"
                                                                                        style="line-height: 29px;
                                                                                            color: #ffffff;
                                                                                            width: 83px;
                                                                                            background-color: #0CAF60;
                                                                                            margin-bottom: 22px;
                                                                                            margin-left: 10px;"
                                                                                        type="button">{{ __('Save') }}</button>
                                                                                    <button id="getJSONs" class="d-none"
                                                                                        onClick="javascript:history.go(-1)"
                                                                                        type="button">{{ __('Back') }}</button>
                                                                                    <button id="getJS" class="d-none"
                                                                                        type="button">{{ __('Get JS Data') }}</button>
                                                                                    <button id="setData" class="d-none"
                                                                                        type="button">{{ __('Set Data') }}</button>
                                                                                    <button id="addField" class="d-none"
                                                                                        type="button">{{ __('Add Field') }}</button>
                                                                                    <button id="removeField"
                                                                                        class="d-none"
                                                                                        type="button">{{ __('Remove Field') }}</button>
                                                                                    <button id="testSubmit" class="d-none"
                                                                                        type="submit">{{ __('Test Submit') }}</button>
                                                                                    <button id="resetDemo" class="d-none"
                                                                                        type="button">{{ __('Reset Demo') }}</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </section>
                                </div>


                            </div>
                        </div>

                        <div id="useradd-3">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="mb-0">{{ __('Invoice') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            <a class="btn btn-sm btn-primary"
                                                data-url="{{ route('pmsinvoice.create', ['pms_id' => $Pms->id]) }}"
                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                title="" data-title="{{ __('Create') }}"
                                                data-bs-original-title="{{ __('Create Invoice') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body text-end">
                                    <table class="table dataTable3 mt-3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice Cost') }}</th>
                                                <th class="">{{ __('Description') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pmsinvoice as $pmsinvoice)
                                                <tr>
                                                    <td style="width: 10%;">
                                                        {{ $pmsinvoice->invoice_cost }}
                                                    </td>
                                                    <td style="white-space: inherit;">
                                                        <a>{{ $pmsinvoice->description }} </a>
                                                    </td>

                                                    @php
                                                        $psinvoice = get_file('/');
                                                    @endphp
                                                    <td class="action w-10">
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ get_file($pmsinvoice->invoice_file) }}"
                                                                download="dsa"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-toggle="tooltip"
                                                                data-title="{{ __('Download Invoice') }}"><i
                                                                    class="ti ti-download text-white"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Download') }}"></i></a>
                                                        </div>

                                                        <div class="action-btn bg-secondary ms-2">
                                                            <a class="mx-3 btn btn-sm align-items-center"
                                                                href="{{ get_file($pmsinvoice->invoice_file) }}"
                                                                target="_blank">
                                                                <i class="ti ti-crosshair text-white"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Preview') }}"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-title="{{ __('Edit Invoice') }}"
                                                                data-url="{{ route('pmsinvoice.edit', $pmsinvoice->id) }}"
                                                                data-toggle="tooltip" title="{{ __('Edit') }}">
                                                                <i class="ti ti-edit text-white"></i>
                                                            </a>
                                                        </div>
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['pmsinvoice.destroy', $pmsinvoice->id]]) !!}
                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
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

                        <div id="useradd-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="mb-0">{{ __('Log Time') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            <a class="btn btn-sm btn-primary"
                                                data-url="{{ route('pmslogtime.create', ['pms_id' => $Pms->id]) }}"
                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                title="" data-title="{{ __('Create') }}"
                                                data-bs-original-title="{{ __('Create Log Time') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body text-end">
                                    <table class="table dataTable3 mt-3">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Hours') }}</th>
                                                <th>{{ __('Minute') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pmslogtime as $pmslogtime_val)
                                                <tr>
                                                    <td style="width: 5%">
                                                        {{ $pmslogtime_val->hours }}
                                                    </td>
                                                    <td style="width: 5%">
                                                        {{ $pmslogtime_val->minute }}
                                                    </td>
                                                    <td style="width: 15%">
                                                        {{ $pmslogtime_val->date }}
                                                    </td>
                                                    <td style="white-space: inherit;">
                                                        <a class="text-dark"> {{ $pmslogtime_val->description }}</a>
                                                    </td>

                                                    <td class="action">

                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" data-size="lg"
                                                                data-url="{{ route('pmslogtime.edit', $pmslogtime_val->id) }}"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-whatever="{{ __('Edit Logtime') }}"
                                                                data-title="{{ __('Edit Logtime') }}"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-toggle="tooltip">
                                                                <i class="ti ti-edit text-white" data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Edit') }}"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['pmslogtime.destroy', $pmslogtime_val->id]]) !!}
                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
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
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('Modules/CMMS/Resources/assets/jqueryform/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('Modules/CMMS/Resources/assets/jqueryform/js/demoFirst.js') }}"></script>
    <script src="{{ asset('Modules/CMMS/Resources/assets/jqueryform/js/form-builder.min.js') }}"></script>
    <script src="{{ asset('Modules/CMMS/Resources/assets/jqueryform/js/vendor.js') }}"></script>
    <script src="{{ asset('Modules/CMMS/Resources/assets/jqueryform/js/form-render.min.js') }}"></script>
    <script src="{{ asset('Modules/CMMS/Resources/assets/jqueryform/js/jquery.rateyo.min.js') }}"></script>




    <script>
        $(document).ready(function() {

            setTimeout(function() {
                $("#setData").trigger('click');
            }, 30);
        });
        $(document).on('click', '.add_instruction1', function() {
            $('.view_instruction').slideToggle('slow');
            $('.add_instruction_section').slideToggle('slow');
        });
    </script>

    <script>
        $(document).ready(function() {
            var tab = 'parts';
            @if ($tab = Session::get('tab-status'))
                var tab = '{{ $tab }}';
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
    </script>

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
