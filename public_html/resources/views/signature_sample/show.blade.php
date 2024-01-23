@extends('layouts.main')

@section('page-title')
Trình ký mẫu
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
<link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
<style>
    .nav-tabs .nav-link-tabs.active {
        background: none;
    }
</style>
@endpush


@section('page-breadcrumb')
Trình ký mẫu,
{{ __('Show') }}
@endsection


@section('content')
<div class="row">
    <div class="col-sm-12 ">
        <div class="row">
            <div class="col-md-12">
                <div id="general">
                    <div class="row">
                        <div class="col-6 row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Mô tả</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-m">
                                            {{ $signatureSample->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Attachments') }}</h5>
                                    </div>
                                    <div class="card-body">
                                      <div class="d-flex flex-column gap-2">
                                        <a href="{{ url($signatureSample->content)}}" class="btn btn-outline-primary" download>
                                            <i class="fa fa-download" style="font-size: 20px;"></i>
                                        </a>
                                        <a class="btn btn-outline-primary" href="{{ url($signatureSample->content)}}" target="_blank">
                                            <i class="fa fa-file" style="font-size: 20px;"></i>
                                        </a>
                                      </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card report_card total_amount_card">
                                <div class="card-body pt-0" style="min-height: 190px;">

                                    <div class="row mt-2 mb-0 align-items-center">
                                        <div class="col-sm-4 h6 text-m">Tên trình ký</div>
                                        <div class="col-sm-8 text-m">{{ $signatureSample->name }}
                                        </div>

                                        <div class="col-sm-4 h6 text-m">Đối tượng</div>
                                        <div class="col-sm-8 text-m">
                                            {{ getUserById($signatureSample->signature_object)->name }}
                                        </div>

                                        <div class="col-sm-4 h6 text-m">Loại trình ký</div>
                                        <div class="col-sm-8 text-m">
                                            {{ getSignatureTypeById($signatureSample->signature_type)->name }}
                                        </div>

                                        <div class="col-sm-4 h6 text-m">Người có thẩm quyền</div>
                                        <div class="col-sm-8 text-m">
                                            {{ getUserById($signatureSample->approver)->name }}
                                        </div>

                                        <div class="col-sm-4 h6 text-m">Người tạo</div>
                                        <div class="col-sm-8 text-m">
                                            {{ getUserById($signatureSample->created_by)->name }}
                                        </div>

                                        <div class="col-sm-4 h6 text-m">Ngày tạo</div>
                                        <div class="col-sm-8 text-m">
                                            {{ date('d-m-Y', strtotime($signatureSample->created_at)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection