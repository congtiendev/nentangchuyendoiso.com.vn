@extends('layouts.main')
@section('page-title')
Thông báo
@endsection
@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5>Tất cả thông báo</h5>
            </div>
            <div class="card-body card-635 d-flex flex-column gap-3">
                @foreach ($notifications as $notification)
                <div data-user-id="{{ Auth::user()->id }}" data-notification-id="{{ $notification->id }}"
                    data-url="{{route('read.notification')}}" data-token="{{ csrf_token() }}"
                    class="position-relative py-3 border-top d-flex gap-3  bd-highlight @if(isReadNotification($notification->id)) read__notification @endif">
                    @if(isReadNotification($notification->id))
                    <span style="width:10px;height:11px;"
                        class="position-absolute top-3  start-100 translate-middle badge rounded-pill bg-danger">
                        .
                    </span>
                    @endif
                    <div class="bd-highlight">
                        <img src="{{ asset($notification->from_avatar) }}" alt="" class="rounded-circle"
                            width="40" height="40">
                    </div>
                    <a href="{{ $notification->link }}" class="d-flex flex-column bd-highlight text-black">
                        <h2 class="fs-6 text-primary">{{$notification->title}}</h2>
                        <p class="fs-6 muted">
                            <span class="f-w-600">{{$notification->from_name}}</span>
                            {{$notification->content}}
                        </p>
                        <span class="fs-6 muted d-flex gap-1 align-items-center">
                            <i class="ti ti-clock chatify-icon"></i>{{ timeAgo($notification->created_at)
                            }}</span>
                    </a>
                </div>
                @endforeach
                <nav aria-label="Điều hướng trang">
                    <ul class="pagination justify-content-center">
                        @if ($notifications->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Trước</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->previousPageUrl() }}"
                                   tabindex="-1">Trước</a>
                            </li>
                        @endif

                        @for ($i = 1; $i <= $notifications->lastPage(); $i++)
                            <li class="page-item {{ ($notifications->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $notifications->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($notifications->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->nextPageUrl() }}">Tiếp theo</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Tiếp theo</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection