@extends('layouts.main')
@section('page-title')
Quản lý ca làm việc
@endsection
@section('page-breadcrumb')
Ca làm việc
@endsection
@section('page-action')
<div>
    <a href="{{ route('workshift.index') }}" class="btn btn-primary btn-sm">Danh sách ca làm việc</a>
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
                                <th>Tên nhân viên</th>
                                <th>Ca làm việc</th>
                                <th>Ngày</th>
                                <th>Trạng thái</th>
                                <th>Lí do</th>
                                <th>Ngày tạo</th>
                                <th>Ngày cập nhật</th>
                                <th>Người phê duyệt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workshiftApprovals as $workshift)
                            <tr>
                                <td>{{ $workshift->user_name }}</td>
                                <td>@if($workshift->shift == 'morning') {{ 'Ca sáng' }} @elseif($workshift->shift ==
                                    'afternoon') {{ 'Ca chiều' }} @elseif($workshift->shift == 'full') {{ 'Cả ngày' }}
                                    @else {{ 'Nghỉ' }} @endif</td>
                                <td>{{ date('d-m-Y', strtotime($workshift->date)) }}</td>
                                <td>
                                    @if($workshift->status == 0)
                                    <span class="btn btn-sm btn-warning">Chờ phê duyệt</span>
                                    @elseif($workshift->status == 1)
                                    <span class="btn btn-sm btn-success">Đã phê duyệt</span>
                                    @else
                                    <span class="btn btn-sm btn-danger">Đã từ chối</span>
                                    @endif
                                </td>
                                <td>{{ $workshift->reason }}</td>
                                <td>{{ date('H:i d-m-Y', strtotime($workshift->created_at)) }}</td>
                                <td>{{ date('H:i d-m-Y', strtotime($workshift->updated_at)) }}</td>
                                <td>@if($workshift->approved_by != null) {{ getUserById($workshift->approved_by)->name
                                    }} @else
                                    {{ 'Chưa phê duyệt' }} @endif</td>
                                </td>
                                <td class="d-flex gap-5">
                                    @if($workshift->status != 1 && $workshift->status != 2)
                                    <div class="action-btn bg-success">
                                        <form method="POST"
                                            action="{{route('workshift.approval', ['id' => $workshift->id])}}"
                                            accept-charset="UTF-8" class="m-0">
                                            @csrf
                                            <input type="hidden" name="workshift_id"
                                                value="{{ $workshift->workshift_id }}">
                                            <button type="submit" class="btn btn-sm btn-success"
                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Approve"
                                                aria-label="Approve">
                                                Phê duyệt
                                            </button>
                                        </form>
                                    </div>

                                    <div class="action-btn bg-danger ms-2">
                                        <form method="POST"
                                            action="{{route('workshift.reject', ['id' => $workshift->id])}}"
                                            accept-charset="UTF-8" class="m-0">
                                            @csrf
                                            <input type="hidden" name="workshift_id"
                                                value="{{ $workshift->workshift_id }}">
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                                title="" data-bs-original-title="Reject" aria-label="Reject">
                                                Từ chối
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                    <div class="action-btn bg-danger ms-2">
                                        <form method="POST"
                                            action="{{ route('workshift.destroy.approval',['id' => $workshift->id]) }}"
                                            accept-charset="UTF-8" class="m-0">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"> <a
                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                aria-label="Delete" data-confirm="Bạn có chắc chắn?"
                                                data-text="Hành động này không thể được hoàn tác. Bạn có muốn tiếp tục?"
                                                data-confirm-yes="delete-form-30">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection