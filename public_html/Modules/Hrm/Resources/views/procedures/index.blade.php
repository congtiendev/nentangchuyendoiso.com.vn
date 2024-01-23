@extends('layouts.main')
@section('page-title')
    {{ __('Quản lí quy trình') }}
@endsection
@section('page-breadcrumb')
    {{ __('Quản lý quy trình') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('employee create')
            <a  class="mx-2 btn btn-sm btn-primary align-items-center text-white"
                data-url="{{ route('procedures.create') }}"
                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                data-title="{{ __('Tạo mới quy trình') }}"
                data-bs-original-title="{{ __('Thêm mới') }}">
                <i class="ti ti-plus"></i> Tạo mới
            </a>
        @endpermission
        @permission('employee create')
        <a  class="mx-2 btn btn-sm btn-primary align-items-center text-white"
        data-url="{{ route('procedures_type.create') }}"
        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
        data-title="{{ __('Thêm loại quy trình') }}"
        data-bs-original-title="{{ __('Thêm mới') }}">
        <i class="ti ti-plus"></i> Loại quy trình
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
                                    <th class="text-center">#ID</th>
                                    <th>{{ __('Tên quy trình') }}</th>
                                    <th>{{ __('Loại quy trình') }}</th>
                                    <th>{{ __('Mô tả') }}</th>
                                    @if (Laratrust::hasPermission('employee edit') || Laratrust::hasPermission('employee delete'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($procedures as $procedure)    
                                        <tr>
                                            @if (!empty($procedure->id))
                                                <td class="text-center">
                                                    @permission('employee show')
                                                        {{-- <a class="btn btn-outline-primary"
                                                            href="{{ route('procedures.show', ['id'=>$procedure->id]) }}">#{{ $procedure->id }}</a>
                                                    @else --}}
                                                        <a
                                                            class="btn btn-outline-primary">#{{ $procedure->id }}</a>
                                                    @endpermission
                                                </td>
                                            @else
                                                <td>--</td>
                                            @endif
                                            <td>{{ $procedure->name }}</td>
                                            <td>{{ $procedure->procedureType->name }}</td>
                                            <td>
                                                {{ $procedure->description }}
                                            </td>
                                            @if (Laratrust::hasPermission('employee edit') || Laratrust::hasPermission('employee delete'))
                                                <td class="Action">
                                                        <span>
                                                            @permission('employee edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                                    data-url="{{ route('procedures.edit', $procedure->id) }}"
                                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Chỉnh sửa quy trình') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                            @if (!empty($procedure->id))
                                                                {{-- @permission('employee show')
                                                                    <div class="action-btn bg-warning ms-2">
                                                                        <a href="#"
                                                                            class="mx-3 btn btn-sm  align-items-center"
                                                                            data-bs-toggle="tooltip" title=""
                                                                            data-bs-original-title="{{ __('Show') }}">
                                                                            <i class="ti ti-eye text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endpermission --}}
                                                                @permission('employee delete')
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        {{ Form::open(['route' => ['procedures.destroy', $procedure->id], 'class' => 'm-0']) }}
                                                                        @method('DELETE')
                                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                            data-bs-toggle="tooltip" title=""
                                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                            data-confirm-yes="delete-form-{{ $procedure->id }}"><i
                                                                                class="ti ti-trash text-white text-white"></i></a>
                                                                        {{ Form::close() }}
                                                                    </div>
                                                                @endpermission
                                                            @endif
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
