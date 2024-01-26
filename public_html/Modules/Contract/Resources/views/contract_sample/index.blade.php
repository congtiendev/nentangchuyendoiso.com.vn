@extends('layouts.main')
@section('page-title')
Quản lý hợp đồng mẫu
@endsection

@section('page-breadcrumb')
Quản lý hợp đồng mẫu
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
    @permission('contract create')
    <button style="background:green;" data-url="{{ route('contract.samples.store') }}" id="showAddContractSampleModal" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </button>
    @endpermission
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
                                <th>Tên hợp đồng</th>
                                <th>Đối tượng</th>
                                <th>Loại hợp đồng</th>
                                <th>Người có thẩm quyền</th>
                                <th>Nội dung hợp đồng</th>
                                <th>Mô tả</th>
                                <th> Người tạo</th>
                                <th> Ngày tạo</th>

                                @if (Laratrust::hasPermission('contract create') || Laratrust::hasPermission('contract
                                show') || Laratrust::hasPermission('contract edit') ||
                                Laratrust::hasPermission('contract delete'))
                                <th>{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contracts as $contract)
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
                                    {{ getUserById($contract->contract_object)->name }}
                                </td>
                                <td>
                                    {{ getContractTypeById($contract->contract_type)->name }}
                                </td>
                                <td>
                                    {{ getUserById($contract->competent_person)->name }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ url($contract->content)}}" target="_blank">
                                        <i class="ti ti-file" style="font-size: 25px;"></i>
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
                                
                                        @permission('contract show')
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('contract.samples.show', $contract->id) }}"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('View') }}"><i class="ti ti-eye text-white"></i></a>
                                        </div>
                                        @endpermission
                                        @permission('contract edit')
                                        <div class="action-btn bg-info ms-2">
                                            <button
                                                data-url="{{route('contract.samples.update', $contract->id)}}"
                                                data-name="{{$contract->name}}" data-contract_type="{{$contract->contract_type}}"
                                                data-competent_person="{{$contract->competent_person}}"
                                                data-contract_object="{{$contract->contract_object}}"
                                                data-description="{{$contract->description}}"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center btn-update-contract-sample"
                                                title="{{ __('Edit') }}"><i class="ti ti-pencil text-white"></i></button>
                                        </div>
                                        @endpermission
                                        @permission('contract delete')
                                        <div class="action-btn bg-danger ms-2">
                                            <form method="POST"
                                                action="{{ route('contract.samples.destroy',['id' => $contract->id]) }}"
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


<div class="modal fade" id="createContractSampleModal" tabindex="-2" role="dialog"
    aria-labelledby="employeeCreateWorkShiftModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="updateContractSampleForm" class="modal-content" method="POST" enctype="multipart/form-data"
            action="{{ route('contract.samples.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Thêm hợp đồng mẫu</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Tên hợp đồng </label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Loại hợp đồng</label>
                            <select class="form-control" name="contract_type" id="contract_type" required>
                                <option value=""> Chọn loại hợp đồng </option>
                                @foreach(getContractType() as $key => $value)
                                <option value="{{ $key }}"> {{ $value }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Người có thẩm quyền </label>
                            <select class="form-control" name="competent_person" id="competent_person" required>
                                @foreach(getUserWorkSpace() as $key => $value)
                                <option value="{{ $key }}"> {{ $value }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Đối tượng </label>
                            <select class="form-control" name="contract_object" id="contract_object" required>
                                @foreach(getUserWorkSpace() as $key => $value)
                                <option value="{{ $key }}"> {{ $value }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label"> Nội dung </label>
                            <input type="file" class="form-control" name="content" id="content" >
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
        $('#showAddContractSampleModal').click(function () {
            $('#createContractSampleModal').modal('show');
            $('#createContractSampleModal').find('#updateContractSampleForm').attr('action', $(this).data('url'));
            $('#createContractSampleModal').find('#name').val('');
            $('#createContractSampleModal').find('#contract_type').val('');
            $('#createContractSampleModal').find('#competent_person').val('');
            $('#createContractSampleModal').find('#contract_object').val('');
            $('#createContractSampleModal').find('#description').val('');
         });
         $(document).on('click', '.btn-update-contract-sample', function () {
            $('#createContractSampleModal').modal('show');
            $('#createContractSampleModal').find('#updateContractSampleForm').attr('action', $(this).data('url'));
            $('#createContractSampleModal').find('#name').val($(this).data('name'));
            $('#createContractSampleModal').find('#contract_type').val($(this).data('contract_type'));
            $('#createContractSampleModal').find('#competent_person').val($(this).data('competent_person'));
            $('#createContractSampleModal').find('#contract_object').val($(this).data('contract_object'));
            $('#createContractSampleModal').find('#description').val($(this).data('description'));
         });
         $('.btn-close-modal').click(function () {
            $('#createContractSampleModal').modal('hide');
         });
    });
</script>
@endpush