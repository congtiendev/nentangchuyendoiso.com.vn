@extends('layouts.main')
@section('page-title')
Quy định
@endsection
@section('page-breadcrumb')
Quy định
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
                                <th>Thời gian checkin</th>
                                <th>Thời gian checkout</th>
                                <th>Xử lý vi phạm checkin/checkout/quên chấm công</th>
                                <th>Cách tính công</th>
                                <th>Đăng ký/đổi ca</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rules as $rule)
                            <tr>
                                <td>{{ $rule->checkin_time ?? 'Chưa có thông tin' }}</td>
                                <td>{{ $rule->checkout_time ?? 'Chưa có thông tin' }}</td>
                                <td>{{ $rule->violation_handling ?? 'Chưa có thông tin' }}</td>
                                <td>{{ $rule->attendance_calculation	 ?? 'Chưa có thông tin' }}</td>
                                <td>{{ $rule->shift_registration ?? 'Chưa có thông tin' }}</td>
                                <td>
                                    <button id="showUpdateRulesModal" class="btn btn-info btn-sm">
                                        <i class="ti ti-pencil"></i>
                                    </button>
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

@foreach ($rules as $rule)
<div class="modal fade" id="updateRulesModal" tabindex="-1" role="dialog"
    aria-labelledby="updateRulesModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('rules.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="updateRulesModalLabel">Thêm loại làm việc</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Thời gian checkin</label>
                            <textarea class="form-control" name="checkin_time" id="checkin_time" placeholder="Thời gian checkin...">{{ $rule->checkin_time ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Thời gian checkout</label>
                            <textarea class="form-control" name="checkout_time" id="checkout_time" placeholder="Thời gian checkout...">{{ $rule->checkout_time ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Xử lý vi phạm checkin/checkout/quên chấm công</label>
                            <textarea class="form-control" name="violation_handling" id="violation_handling" placeholder="Xử lý vi phạm checkin/checkout/quên chấm công...">{{ $rule->violation_handling ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-form-label">Cách tính công</label>
                        <textarea class="form-control" name="attendance_calculation" id="attendance_calculation" placeholder="Cách tính công...">{{ $rule->attendance_calculation ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-form-label">Đăng ký/đổi ca</label>
                        <textarea class="form-control" name="shift_registration" id="shift_registration" placeholder="Đăng ký/đổi ca...">{{ $rule->shift_registration ?? '' }}</textarea>
                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
        </form>
    </div>
</div>
@endforeach


<script>
    $(document).ready(function () {
        $(document).on('click', '#showUpdateRulesModal', function () {
        $('#updateRulesModal').modal('show');
    });
    $('.btn-close-modal').click(function () {
        $('#updateRulesModal').modal('hide');
     });
    });
</script>
@endsection