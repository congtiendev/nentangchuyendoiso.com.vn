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
                                <th>Ca làm việc</th>
                                <th>Ngày</th>
                                <th>Trạng thái</th>
                                <th>Lí do</th>
                                <th>Ngày tạo</th>
                                <th>Ngày cập nhật</th>
                                <th>Người phê duyệt</th>
              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workshiftApprovals as $workshift)
                            <tr>
                                <td>
                                    {{ getWorkshiftTypeName($workshift->shift) }} 
                                </td>
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
                       
                            </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection