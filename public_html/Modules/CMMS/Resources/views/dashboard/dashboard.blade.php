@extends('layouts.main')
@php
    if (Auth::user()->type == 'company') {
        $currentlocation = Modules\CMMS\Entities\Location::userCurrentLocation();
        $userlocation = Modules\CMMS\Entities\Location::find($currentlocation);
        if ($userlocation) {
            if ($currentlocation == $userlocation->id) {
                $location_name = $userlocation->name;
            }
        } else {
            $location_name = '';
        }
    }
    
@endphp

@section('page-title')
    {{ __('Dashboard') }}
    @if (Auth::user()->type == 'company')
        {{ __(' - ') }} {{ Str::ucfirst($location_name) }}
    @endif
@endsection

@push('scripts')
    <script>
        var today = new Date()
        var curHr = today.getHours()
        var target = document.getElementById("greetings");
        if (curHr < 12) {
            target.innerHTML = "{{ __('Good Morning,') }}";
        } else if (curHr < 17) {
            target.innerHTML = "{{ __('Good Afternoon,') }}";
        } else {
            target.innerHTML = "{{ __('Good Evening,') }}";
        }
    </script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush

<style>
    .qrcode canvas {
        width: 100%;
        height: 100%;
        padding: 15px 15px;
    }
</style>

@section('page-action')
    <div>
        <div class="text-end">
            <div class="d-flex justify-content-end drp-languages">
                @if (count($locations) > 0)
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language">
                            <a class="dash-head-link dropdown-toggle arrow-none me-0 location_name" data-bs-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                id="dropdownLanguage">
                                <i class="ti ti-current-location text-primary me-2"></i>
                                @foreach ($locations as $key => $value)
                                    <span
                                        class="drp-text hide-mob text-primary">{{ $currentlocation == $key ? Str::ucfirst($value) : '' }}</span>
                                @endforeach
                                <i class="ti ti-chevron-down drp-arrow nocolor ms-3"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                                @foreach ($locations as $key => $value)
                                    <a href="{{ route('change-location', $key) }}"
                                        class="dropdown-item {{ $currentlocation == $key ? 'text-primary' : '' }}">{{ Str::ucfirst($value) }}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-6">
                    <div class="row">
                        <div class="col-lg-8 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h3 class="mb-1 col-12" id="greetings"></h3>
                                    <h6> {{ __(str::ucfirst($location_name)) }} </h6>
                                    <p>{{ __('Have a nice day! Did you know that you can quickly add your favorite product or category to the store?') }}
                                    </p>
                                    <div class="stats">
                                        <a href="#" class="btn btn-primary btn-q-add cp_link"
                                            data-link="{{ route('work_request.portal', ['id' => \Illuminate\Support\Facades\Crypt::encrypt($currentlocation), 'lang' => Auth::user()->lang]) }}"
                                            data-bs-whatever="{{ __('Copy Link') }}" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Copy Link') }}"
                                            title="{{ __('Click to copy link') }}">
                                            <i class="ti ti-link"></i>
                                            {{ __('Location Link') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="qrcode"style="width: 177px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6">
                    <div class="row">
                        <div class="col-lg-3 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-clipboard-check"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 class="mb-3">{{ __('Total Open Work Order') }}</h6>
                                    <h3 class="mb-0">{{ $open_workOrder }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="theme-avtar bg-info">
                                        <i class="ti ti-click"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 class="mb-3">{{ __('Complete Work Order') }}</h6>
                                    <h3 class="mb-0">{{ $complete_workOrder }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-box"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 style="margin-bottom:33px;">{{ __('Components') }}</h6>
                                    <h3 class="mb-0">{{ $total_components }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-tools"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 style="margin-bottom:33px;">{{ __('PMs') }}</h6>
                                    <h3 class="mb-0">{{ $total_pms }} </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6 d-flex">
                    <div class="card w-100">
                        <div class="card-header">
                            <h5>{{ __('Total Work Order') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">

                                    @forelse ($arrProcessPer as $index => $value)
                                        <div class="col-md-12">
                                            <i class="fas fa-chart {{ $arrProcessClass[$index] }} mt-3 h3"></i>
                                            <div class="row">
                                                <h6 class="font-weight-bold">
                                                    <span>{{ $value }}%</span>
                                                    <p class="text-muted mb-0">{{ __($arrProcessLabel[$index]) }}</p>
                                                </h6>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-md-12">
                                            <h5>{{ __('No work order found !') }}</h5>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="col-6">
                                    <div id="total_work_order"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Work Order Overview') }}</h5>
                                </div>
                                <div id="task-chart-work-order"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card card-fluid">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">{{ __('Work Order') }}</h5>
                                    <p> <b> {{ $completeTask }}</b> {{ __('Work Order completed out of') }}
                                        {{ $totalTask }} </p>
                                </div>
                            </div>
                        </div>
                        <table class="table table-centered table-hover mb-0 animated">
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <div class="font-14 my-1"><a
                                                    href="{{ route('workorder.show', [$task->id]) }}"
                                                    class="text-body">{{ $task->wo_name }}</a></div>

                                            @php($date = '<span class="text-' . ($task->date < date('Y-m-d') ? 'danger' : 'primary') . '">' . company_date_formate($task->date) . '</span> ')
                                            <span class="text-muted font-13">{{ __('Due Date') }} :
                                                {!! $date !!}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Status') }}</span> <br />
                                            @if ($task->status == '1')
                                                <span class="badge bg-success p-2 px-3 rounded">{{ __('Open') }}</span>
                                            @else
                                                <span
                                                    class="badge bg-primary p-2 px-3 rounded">{{ __('Complete') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Project') }}</span>
                                            <div class="font-14 mt-1 font-weight-normal">{{ $task->wo_name }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td>
                                            <h6 class="text-center font-13">{{ __('No Work order found') }}</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script type="text/javascript" src="https://www.jqueryscript.net/demo/Canvas-Table-QR-Code-Generator/jquery.qrcode.js">
    </script>
    <script type="text/javascript" src="https://www.jqueryscript.net/demo/Canvas-Table-QR-Code-Generator/qrcode.js">
    </script>

    <script type="text/javascript">
        $('.qrcode').qrcode(
            "{{ route('work_request.portal', [\Illuminate\Support\Facades\Crypt::encrypt($currentlocation), 'en']) }}");
    </script>

    <script type="text/javascript">
        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('Success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>
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
                    }],
                    xaxis: {
                        categories: {!! json_encode($chartData['label']) !!},
                        title: {
                            text: '{{ __('Date') }}'
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
                var chart = new ApexCharts(document.querySelector("#task-chart-work-order"), options);
                chart.render();
            })();

            (function() {
                var options = {
                    chart: {
                        height: 210,
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
                    series: {!! json_encode($arrProcessPer) !!},
                    colors: ["#3ec9d6", "#6fd943"],
                    labels: {!! json_encode($arrProcessLabel) !!},
                    legend: {
                        show: false
                    }
                };
                var chart = new ApexCharts(document.querySelector("#total_work_order"), options);
                chart.render();
            })();
        </script>
@endpush
