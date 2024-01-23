@extends('layouts.main')
@section('page-title')
Quản lý trình ký mẫu
@endsection

@section('page-breadcrumb')
Quản lý trình ký mẫu
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
<style>
    .comp-card {
        min-height: 140px;
    }
</style>
@endpush

@section('page-action')
<div>
    @stack('addButtonHook')
    <button data-action="{{ route('signature-sample.store') }}" id="showAddsignatureSampleModal" class="btn btn-sm"
        style="background-color: #0CAF60;">
        <i class="ti ti-plus"></i>
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>Tên trình ký</th>
                                <th>Đối tượng</th>
                                <th>Loại trình ký</th>
                                <th>Người có thẩm quyền</th>
                                <th>Nội dung trình ký</th>
                                <th>Mô tả</th>
                                <th> Người tạo</th>
                                <th> Ngày tạo</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($signatureSamples as $contract)
                            <tr>
                                <td class="Id">
                                    @permission('contract show')
                                    <a href="{{ route('contract.show', $contract->id) }}"
                                        class="btn btn-outline-primary">
                                        {{ Modules\Contract\Entities\Contract::contractNumberFormat($contract->id) }}
                                    </a>
                                    @else
                                    <a class="btn btn-outline-primary">{{
                                        Modules\Contract\Entities\Contract::contractNumberFormat($contract->id) }}</a>
                                    @endif
                                </td>
                                <td>
                                    {{ getUserById($contract->signature_object)->name }}
                                </td>
                                <td>
                                    {{ getSignatureTypeById($contract->signature_type)->name}}
                                </td>
                                <td>
                                    {{ getUserById($contract->approver)->name }}
                                </td>
                                <td >
                                    <a href="{{ url($contract->content)}}" class="btn btn-outline-primary" download>
                                        <i class="fa fa-download" style="font-size: 20px;"></i>
                                    </a>
                                    <a class="btn btn-outline-primary" href="{{ url($contract->content)}}" target="_blank">
                                        <i class="fa fa-file" style="font-size: 20px;"></i>
                                    </a>
                                </td>
                                <td>
                                    {{ $contract->description }}
                                </td>
                                <td>
                                    {{ getUserById($contract->created_by)->name }}
                                </td>
                                <td>
                                    {{date('d-m-Y', strtotime($contract->created_at))}}
                                </td>

                                @if (Laratrust::hasPermission('contract create') || Laratrust::hasPermission('contract
                                show') || Laratrust::hasPermission('contract edit') ||
                                Laratrust::hasPermission('contract delete'))
                                <td class="Action">
                                    <span>
                                
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('signature-sample.show', $contract->id) }}"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('View') }}"><i class="ti ti-eye text-white"></i></a>
                                        </div>
                                        <div class="action-btn bg-info ms-2">
                                            <button
                                                data-action="{{route('signature-sample.update', $contract->id)}}"
                                                data-name="{{ $contract->name }}"
                                                data-signature_type="{{ $contract->signature_type }}"
                                                data-approver="{{ $contract->approver }}"
                                                data-signature_object="{{ $contract->signature_object }}"
                                                data-description="{{ $contract->description }}"
                                                data-content="{{ $contract->content }}"
                                                class="mx-3 btn btn-sm  btn-update-signature-sample"
                                                title="{{ __('Edit') }}"><i class="ti ti-pencil text-white"></i></button>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <form method="POST"
                                                action="{{ route('signature-sample.destroy',['id' => $contract->id]) }}"
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


<div class="modal fade" id="createsignatureSampleModal" tabindex="-2" role="dialog"
    aria-labelledby="employeeCreateWorkShiftModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" enctype="multipart/form-data" action="">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Thêm trình ký mẫu</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Tên trình ký </label>
                            <input type="text" class="form-control" name="name" id="name" required placeholder="Tên trình ký">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Loại trình ký</label>
                            <select class="form-control" name="signature_type" id="signature_type" required>
                                <option value=""> Chọn loại trình ký </option>
                                @foreach($signatureTypes as $key => $value)
                                <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Người có thẩm quyền </label>
                            <select class="form-control" name="approver" id="approver" required>
                                <option value=""> Chọn người có thẩm quyền </option>
                                @foreach(getUserWorkSpace() as $key => $value)
                                <option value="{{ $key }}"> {{ $value }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Đối tượng </label>
                            <select class="form-control" name="signature_object" id="signature_object" required>
                                <option value=""> Chọn đối tượng </option>
                                @foreach(getUserWorkSpace() as $key => $value)
                                <option value="{{ $key }}"> {{ $value }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group" id="content__group">
                            <label class="col-form-label"> Nội dung </label>
                            <input type="file" class="form-control" name="content" id="content" required>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label">
                                Mô tả
                            </label>
                            <textarea class="form-control" name="description" id="description" cols="20"
                                rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#showAddsignatureSampleModal').click(function () {
            $('#createsignatureSampleModal').modal('show');
            $('#createsignatureSampleModal').find('#updatesignatureSampleForm').attr('action', $(this).data('action'));
            $('#createsignatureSampleModal').find('#name').val('');
            $('#createsignatureSampleModal').find('#signature_type').val('');
            $('#createsignatureSampleModal').find('#approver').val('');
            $('#createsignatureSampleModal').find('#signature_object').val('');
            $('#createsignatureSampleModal').find('#description').val('');
         });
         $(document).on('click', '.btn-update-signature-sample', function () {
            $('#createsignatureSampleModal').modal('show');
            $('#createsignatureSampleModal').find('#updatesignatureSampleForm').attr('action', $(this).data('action'));
            $('#createsignatureSampleModal').find('#name').val($(this).data('name'));
            $('#createsignatureSampleModal').find('#signature_type').val($(this).data('signature_type'));
            $('#createsignatureSampleModal').find('#approver').val($(this).data('approver'));
            $('#createsignatureSampleModal').find('#signature_object').val($(this).data('signature_object'));
            $('#createsignatureSampleModal').find('#description').val($(this).data('description'));
            });
         $('.btn-close-modal').click(function () {
            $('#createsignatureSampleModal').modal('hide');
         });
    });
</script>
@endpush