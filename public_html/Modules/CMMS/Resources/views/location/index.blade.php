@extends('layouts.main')
@section('page-title')
    {{ __('Manage Location') }}
@endsection
@section('page-breadcrumb')
    {{ __('Location') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/CMMS/Resources/assets/custom/css/custom.css') }}">
@endpush
@section('page-action')
    <div>
        @permission('location create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Location') }}"
                data-url="{{ route('location.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus text-white"></i>
            </a>
        @endpermission


    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th width="200px"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($locations as $location_val)
                                    <tr>
                                        <td>{{ $location_val->name }}</td>
                                        <td>{{ $location_val->address }}</td>
                                        @if (Laratrust::hasPermission('location edit') || Laratrust::hasPermission('location delete'))
                                            <td class="Action">
                                                <span>
                                                    <div class="action-btn bg-dark ms-2">
                                                        <a class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-url="{{ route('work_request.QRCode' , $location_val->id) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Submit a Work Request') }}"
                                                        data-bs-original-title="{{ __('Submit a Work Request') }}">
                                                        <i class="ti ti-qrcode text-white"></i>
                                                    </a>
                                                    </div>
        
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link"
                                                            data-link="{{ route('work_request.portal', ['id' => \Illuminate\Support\Facades\Crypt::encrypt($location_val->id), 'lang' => Auth::user()->lang]) }}"
                                                            data-bs-whatever="{{ __('Copy Link') }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Copy Link') }}"> <span
                                                                class="text-white"> <i class="ti ti-link"></i></span></a>
                                                    </div>
                                                    @permission('location edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('location.edit', $location_val->id) }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Location') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('location delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{ Form::open(['route' => ['location.destroy', $location_val->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $location_val->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {{ Form::close() }}
                                                        </div>
                                                    @endpermission
                                                </span>
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
    <script type="text/javascript" src="https://www.jqueryscript.net/demo/Canvas-Table-QR-Code-Generator/jquery.qrcode.js">
    </script>
    <script type="text/javascript" src="https://www.jqueryscript.net/demo/Canvas-Table-QR-Code-Generator/qrcode.js">
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.cp_link').on('click', function() {
                var value = $(this).attr('data-link');
                alert
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('Success', '{{ __('Link Copy on Clipboard') }}', 'success')
            });
        });
    </script>
@endpush
