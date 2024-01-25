@extends('layouts.main')
@section('page-title')
    {{ __('Lịch sử thay đổi') }}
@endsection
@section('page-breadcrumb')
    {{ __('Lịch sử') }}
@endsection

@section('page-action')
<div>
    @permission('employee create')
        <a href="{{ route('proposal.index') }}" data-title="{{ __('Lịch sử chỉnh sửa') }}" data-bs-toggle="tooltip"
            title="" class="btn btn-sm btn-primary">
            Trở lại
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
                                    <th>ID</th>
                                    <th>Hành động</th>
                                    <th>Người thay đổi</th>
                                    <th>Nội dung</th>
                                </tr>
                                   
                            </thead>
                            <tbody>
                                @foreach ($proposals as $log)
                                    
                                       <tr>
                                            <td>{{$log->id}}</td>
                                            <td>{{$log->log_type}}</td>
                                            @php
                                            $changes = json_decode($log->remark, true);
                                            @endphp
                                            @if($log->action_type == 'store')
                                                <td>
                                                    @foreach($changes as $field => $change)
                                                    <strong>{{$change['changed_by']}}</strong>
                                                    @break
                                                    @endforeach

                                                </td>

                                            @elseif($log->action_type == 'delete')
                                                <td>
                                                    @foreach($changes as $field => $change)
                                                    <strong>{{$change['changed_by']}}</strong>
                                                    @break
                                                    @endforeach

                                                </td>
                                            @else
                                                <td>
                                                    @foreach($changes['changes'] as $field => $change)
                                                    <strong>{{ $change['changed_by'] }}</strong>
                                                    @break
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if($log->action_type == 'store')
                                                <td>
                                                    @foreach($changes as $field => $change)
                                                    Đã tạo văn bản trình ký cho
                                                    <strong>{{$change['customer_id']}}</strong>
                                                    @endforeach
                                                </td>
                                            @elseif($log->action_type == 'delete')
                                                <td>
                                                    @foreach($changes as $field => $change)
                                                    Đã xóa điều chuyển
                                                    cho nhân viên 
                                                    <strong>{{$change['name']}}</strong>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td>
                                                    @foreach($changes['changes'] as $field => $change)
                                                    Đã thay đổi
                                                    <strong> {{ $change['old'] }}</strong>
                                                    thành 
                                                    <strong> {{ $change['new'] }}</strong>
                                                    vào<strong> {{ $change['changed_at'] }}</strong><br>
                                                    @endforeach
                                                    
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
