@extends('layouts.main')
@section('page-title')
    {{ __('Danh sách mượn') }}
@endsection
@section('page-breadcrumb')
    {{ __('Danh sách mượn') }}
@endsection

@section('page-action')
    <div>
        @permission('location create')
        <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Thêm mới hồ sơ') }}"
           data-url="{{ route('manager-file.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                                <th>{{ __('STT') }}</th>
                                <th>{{ __('Tên hồ sơ') }}</th>
                                <th>{{ __('Loại hồ sơ') }}</th>
                                <th>{{ __('Trạng thái') }}</th>
                                <th>{{ __('Thời gian tạo') }}</th>
                                <th width="200px"> {{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $key => $user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @if ($user->type == 0)
                                            <span>Hồ sơ mượn LĐ</span>
                                        @elseif($user->type == 1)
                                            <span> Hồ sơ mượn DC</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->status == 0)
                                            <span
                                                class="badge fix_badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\ManagerFile::$statues[$user->status]) }}</span>
                                        @elseif($user->status == 1)
                                            <span
                                                class="badge fix_badge  bg-primary p-2 px-3 rounded">{{ __(\App\Models\ManagerFile::$statues[$user->status]) }}</span>
                                        @elseif($user->status == 2)
                                            <span
                                                class="badge fix_badge bg-info p-2 px-3 rounded">{{ __(\App\Models\ManagerFile::$statues[$user->status]) }}</span>
                                        @elseif($user->status == 3)
                                            <span
                                                class="badge fix_badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\ManagerFile::$statues[$user->status]) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ date('H:i d-m-Y', strtotime($user->created_at)) }}</td>
{{--                                    @if (Laratrust::hasPermission('location edit') || Laratrust::hasPermission('location delete'))--}}
                                        <td class="Action">
                                                <span>
{{--                                                    @permission('location edit')--}}
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                               data-url="{{ route('manager-file.edit', $user->id) }}"
                                                               data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                               title="" data-title="{{ __('Edit Location') }}"
                                                               data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
{{--                                                    @endpermission--}}
{{--                                                    @permission('location delete')--}}
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{ Form::open(['route' => ['manager-file.destroy', $user->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                               data-bs-toggle="tooltip" title=""
                                                               data-bs-original-title="Delete" aria-label="Delete"
                                                               data-confirm="{{ __('Are You Sure?') }}"
                                                               data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                               data-confirm-yes="delete-form-{{ $user->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {{ Form::close() }}
                                                        </div>
{{--                                                    @endpermission--}}
                                                </span>
                                        </td>
{{--                                    @endif--}}
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
