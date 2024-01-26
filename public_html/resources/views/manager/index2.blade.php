@extends('layouts.main')
@section('page-title')
    {{ __('Hồ sơ duyệt mượn DC') }}
@endsection
@section('page-breadcrumb')
    {{ __('Hồ sơ duyệt mượn') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('location create')
        <a href="{{ route('manager-file.index') }}" data-title="{{ __('Danh sách mượn LD') }}" data-bs-toggle="tooltip"
            title="" class="btn btn-sm btn-primary">
           Danh sách mượn LD
        </a>
        @endpermission
        @permission('location create')
        <a href="{{ route('manager-file.index2') }}" data-title="{{ __('Danh sách mượn DC') }}" data-bs-toggle="tooltip"
        title="" class="btn btn-sm btn-primary">
        Danh sách mượn DC
         </a>
        @endpermission
            {{-- @permission('data create') --}}
                {{-- <a href="{{ route('borrow-asset-records.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                    data-bs-original-title="{{ __('Create') }}">
                    <i class="ti ti-plus"></i>
                </a> --}}
            {{-- @endpermission --}}
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
                                    <th> {{ __('ID') }}</th>
                                    <th> {{ __('Tên người nhân viên') }}</th>
                                    <th> {{ __('Tên thiết bị') }}</th>
                                    <th> {{ __('Số ngày mượn') }}</th>
                                    <th> {{ __('Ngày bắt đầu') }}</th>
                                    <th> {{ __('Ngày kết thúc') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    {{-- <th > {{ __('Action') }}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                <tr class="font-style">
                                        <td class="Id">
                                            {{$data->id}}
                                        </td>
                                        <td> {{ $data->user->name }} </td>
                                        <td> {{ $data->asset->name }} </td>
                                        <td>{{ ($data->borrowed_day) }}</td>
                                        <td>{{ company_date_formate($data->borrowed_date) }}</td>
                                        <td>{{ company_date_formate($data->give_back_day) }}</td> 
                                        <td>
                                            @if ($data->status == 'Chờ phê duyệt')
                                                <span
                                                    class="badge fix_badge bg-primary p-2 px-3 rounded">{{ (\App\Models\BorrowAssetRecord::$statues[0]) }}</span>
                                            @elseif($data->status == 'Phê duyệt')
                                                <span
                                                    class="badge fix_badge bg-info p-2 px-3 rounded">{{(\App\Models\BorrowAssetRecord::$statues[1]) }}</span>
                                            @elseif($data->status == 'Từ chối')
                                                <span
                                                    class="badge fix_badge bg-secondary p-2 px-3 rounded">{{ (\App\Models\BorrowAssetRecord::$statues[2]) }}</span>
                                            @elseif($data->status == 'Đã trả')
                                                <span
                                                    class="badge fix_badge bg-warning p-2 px-3 rounded">{{ (\App\Models\BorrowAssetRecord::$statues[3]) }}</span>
                                            @elseif($data->status == 'Thu hồi')
                                                <span
                                                    class="badge fix_badge bg-danger p-2 px-3 rounded">{{ (\App\Models\BorrowAssetRecord::$statues[4]) }}</span>
                                            @endif
                                        </td>
                                            <td class="Action">
                                                <span>
                                                    {{-- @permission('data show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ route('data.show', \Crypt::encrypt($data->id)) }}"
                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                data-bs-toggle="tooltip" title="{{ __('Show') }}"
                                                                data-original-title="{{ __('Detail') }}">
                                                                <i class="ti ti-eye text-white text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission --}}

                                                    {{-- @permission('proposal edit') --}}
                                                        {{-- <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('borrow-asset-records.edit', $data->id) }}"
                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div> --}}
                                                    {{-- @endpermission --}}
                                                   
                                                    {{-- @permission('data delete') --}}
                                                        {{-- <div class="action-btn bg-danger ms-2">
                                                            {{ Form::open(['route' => ['borrow-asset-records.destroy', $data->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $data->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {{ Form::close() }}
                                                        </div> --}}
                                                    {{-- @endpermission --}}
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
@endsection
@push('scripts')
    <script>
        $(document).on("click",".cp_link",function() {
            var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
    </script>
@endpush
