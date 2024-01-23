@extends('layouts.main')
@section('page-title')
    {{ __('Manage Attendance List') }}
@endsection
@section('page-breadcrumb')
    {{ __('Attendance') }}
@endsection
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-action')
    <div>
        @permission('attendance import')
            <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-primary" data-title="{{ __('Danh sách') }}"
               data-toggle="tooltip" title="{{ __('Danh sách') }}"><i
                    class="ti ti-list"></i>
            </a>
        @endpermission
        @permission('attendance import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Import') }}"
                data-url="{{ route('attendance.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        @permission('attendance create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Attendance') }}"
                data-url="{{ route('attendance.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                @if (Laratrust::hasPermission('attendance create') || Laratrust::hasPermission('attendance edit'))
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Clock In') }}</th>
                                <th>{{ __('Clock Out') }}</th>
                                <th>{{ __('Late') }}</th>
                                <th>{{ __('Early Leaving') }}</th>
                                <th>{{ __('Overtime') }}</th>
                                @if (Laratrust::hasPermission('attendance edit') || Laratrust::hasPermission('attendance delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    @if (Laratrust::hasPermission('attendance create') || Laratrust::hasPermission('attendance edit'))
                                        <td>{{ !empty($attendance->employees) ? $attendance->employees->name : '' }}</td>
                                    @endif
                                    <td>{{ company_date_formate($attendance->date) }}</td>
                                    <td>{{ $attendance->status }}</td>
                                    <td>{{ $attendance->clock_in != '00:00:00' ? $attendance->clock_in : '00:00' }}
                                    </td>
                                    <td>{{ $attendance->clock_out != '00:00:00' ? $attendance->clock_out : '00:00' }}
                                    </td>
                                    <td>{{ $attendance->late }}</td>
                                    <td>{{ $attendance->early_leaving }}</td>
                                    <td>{{ $attendance->overtime }}</td>
                                    <td class="Action">
                                        @if (Laratrust::hasPermission('attendance edit') || Laratrust::hasPermission('attendance delete'))
                                            <span>
                                                @permission('attendance edit')
                                                    <div class="action-btn bg-success ms-2">
                                                        {{ Form::open(['route' => ['attendance.update_status', $attendance->id], 'class' => 'm-0']) }}
                                                        @method('POST')
                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Duyệt" aria-label="Duyệt"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $attendance->id }}"><i
                                                                class="ti ti-check text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission

                                                @permission('attendance delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['attendance.destroy', $attendance->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $attendance->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                            </span>
                                        @endif
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
@endsection
@push('scripts')
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else {
                $('.date').addClass('d-block');
                $('.date').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });
        $('input[name="type"]:radio:checked').trigger('change');
    </script>
    <script type="text/javascript">
        $(document).on('change', '#branch', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(branch_id) {
            var data = {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            }
            $.ajax({
                url: '{{ route('employee.getdepartment') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department').empty();
                    $('#department').append('<option value="" disabled>{{ __('All') }}</option>');

                    $.each(data, function(key, value) {
                        $('#department').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#department').val('');
                }
            });
        }
    </script>
@endpush
