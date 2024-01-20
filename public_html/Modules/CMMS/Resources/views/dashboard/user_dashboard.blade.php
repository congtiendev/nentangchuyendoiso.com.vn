@extends('layouts.main')

@php
    if (Auth::user()->type != 'company') {
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
        {{ __(' - ') }} {{ Str::ucfirst($location_name) }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush

@section('page-action')
    <div>
        <div class="text-end">
            <div class="d-flex justify-content-end drp-languages">
                @if (count($locations) > 0)
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language">
                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                                <i class="ti ti-current-location text-primary me-2"></i>
                                @foreach ($locations as $key => $value)
                                    <span
                                        class="drp-text hide-mob text-primary">{{ $currentlocation == $key ? Str::ucfirst($value) : '' }}</span>
                                @endforeach
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
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

    @if (Auth::user()->user_type != 'company')
        <div class="col-sm-12 mt-3">
            <div class="row">
                <div class="col-xxl-6">
                    <div class="row">
                        <div class="col-lg-4 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-home"></i>
                                    </div>
                                    <h3 class="mt-3">{{ $assign_work_order }} </h3>
                                    <p> {{ __('Total Assign Work Order') }} </p>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-home"></i>
                                    </div>
                                    <h3 class="mt-3">{{ $total_complete_order }} </h3>
                                    <p> {{ __('Total Completed Work Order') }} </p>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-home"></i>
                                    </div>
                                    <h3 class="mt-3">{{ $open_workorder }} </h3>
                                    <p> {{ __('Total Open  Work Order') }} </p>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-xl-6 d-flex">
                    <div class="card w-100">
                        <div class="card-header">
                            <h5>{{ __('Work Order Overview') }}</h5>
                        </div>
                        <div id="traffic-chart1"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Total Work Order') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    @forelse ($arrProcessPer as $index => $value)
                                        <div class="col-6">
                                            <i class="fas fa-chart {{ $arrProcessClass[$index] }} mt-3 h3"></i>
                                            <div class="row">
                                                <h6 class="font-weight-bold">
                                                    <span>{{ $value }}%</span>
                                                    <p class="text-muted mb-0">{{ __($arrProcessLabel[$index]) }}</p>
                                                </h6>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
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
                <div class="col-md-6">
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
                                            <div class="font-14 my-1"><a href="{{ route('workorder.show', [$task['id']]) }}"
                                                    class="text-body">{{ $task['wo_name'] }}</a></div>
        
                                            @php($date = '<span class="text-' . ($task['date'] < date('Y-m-d') ? 'danger' : 'success') . '">' . company_date_formate($task['date']) . '</span> ')
        
                                            <span class="text-muted font-13">{{ __('Due Date') }} :
                                                {!! $date !!}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Status') }}</span> <br />
                                            @if ($task['status'] == '1')
                                                <span class="badge bg-success p-2 px-3 rounded">{{ __('Open') }}</span>
                                            @else
                                                <span class="badge bg-primary p-2 px-3 rounded">{{ __('Complete') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Project') }}</span>
                                            <div class="font-14 mt-1 font-weight-normal">{{ $task['wo_name'] }}
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
       
       
    @endif


@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        $(document).on('click', '.assign_workorder', function() {

            $(".text_header").html("Assign Work order");

        });
    </script>

    <script>
        (function() {
            var options = {
                chart: {
                    height: 150,
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
                    name: 'Visitors',
                    data: {!! json_encode($chartData['data']) !!}
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
            var chart = new ApexCharts(document.querySelector("#traffic-chart1"), options);
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
