@extends('layouts.auth')
@section('page-title')
    {{__('Login')}}
@endsection
@section('language-bar')
<div class="lang-dropdown-only-desk">
    <li class="dropdown dash-h-item drp-language">
        <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="drp-text"> {{Str::upper($lang)}}
            </span>
        </a>
        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
            @foreach (languages() as $key => $language)
                <a href="{{ route('login',$key) }}"
                    class="dropdown-item @if ($lang == $key) text-primary  @endif">
                    <span>{{Str::ucfirst($language)}}</span>
                </a>
            @endforeach
        </div>
    </li>
</div>
@endsection
@php
    $admin_settings = getAdminAllSetting();
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-center" style="padding-bottom: 20px">
                <img style = "width: 200px" src="{{ asset('images/logo_Quyet-Thang.png') }}" alt="">
                <p class="mt-3 fw-bold">
                    @if (!empty(admin_setting('footer_text'))) {{admin_setting('footer_text')}} @else{{__('Copyright')}} &copy; {{ config('app.name', 'WorkGo') }}@endif{{ config(' ') }}
                </p>
                <h2 class="mb-3 f-w-600">{{ __('Đăng nhập') }}</h2>
            </div>
            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="" id="form_data">
                @csrf
                <div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" placeholder="{{ __('Địa chỉ Email') }}" required
                               autofocus>
                        @error('email')
                        <span class="error invalid-email text-danger" role="alert">
                            <small>{{ $message }}</small>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Mật khẩu') }}</label>
                        <input id="password" type="password"
                               class="form-control  @error('password') is-invalid @enderror" name="password"
                               placeholder="{{ __('Mật khẩu') }}" required>
                        @error('password')
                        <span class="error invalid-password text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                        @enderror
                        @if (Route::has('password.request'))
                            <div class="mt-2">
                                <a href="{{ route('password.request') }}"
                                   class="small text-primary text-underline--dashed border-primar">{{ __('Quên mật khẩu?') }}</a>
                            </div>
                        @endif
                    </div>
                    @if(module_is_active('GoogleCaptcha') && admin_setting('google_recaptcha_is_on') == 'on' )
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                    @endif
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block mt-2 login_button"
                                tabindex="4">{{ __('Đăng nhập') }}</button>
                    </div>
                    @if (empty( admin_setting('signup')) ||  admin_setting('signup') == "on")
                        <p class="my-4 text-center">{{ __("Don't have an account?") }}
                            <a href="{{route('register')}}" class="my-4 text-primary">{{__('Đăng kí')}}</a>
                        </p>
                    @endif
                    <div class="d-flex justify-content-between mt-3" style="padding-top: 30px">
                        <div>
                            <h4 style=" margin-bottom: 15px">{{ __('Hỗ trợ đăng nhập') }}</h4>
                            <p class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" data-slot="icon" class="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                                <a href="mailto:{{ admin_setting('email') }}">{{ 'info@quyetthang.vn' }}</a>
                            </p>
                            <p class="mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" data-slot="icon" class="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                                </svg>
                                <a href="tel:{{ admin_setting('phone') }}">{{ '0346999645' }}</a>
                            </p>
                        </div>
                        <div>
                            <h4 style=" margin-bottom: 15px">{{ __('Hỗ trợ nghiệp vụ') }}</h4>
                            <p class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" data-slot="icon" class="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13"/>
                                </svg>
                                <a href="https://drive.google.com/drive/folders/168Bz__NhYgh_y0MgY2R3mkgrJgQ4uOOv?fbclid=IwAR1WceAPgbHQgdhLBhr3DEfadWvtrwFF9hUcQe31EnH0MzisBAJSWHETo-U">{{ __('Tài liệu') }}</a>
                            </p>
                             <p class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" data-slot="icon" class="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13"/>
                                </svg>
                                <a href="https://drive.google.com/drive/folders/1nnRJFCeAXdx6JAyMkIpomRpEeUNwco1T?fbclid=IwAR1WceAPgbHQgdhLBhr3DEfadWvtrwFF9hUcQe31EnH0MzisBAJSWHETo-U">{{ __('Video HDSD') }}</a>
                            </p>
                            <!-- <p class="mb-0">
                                <iframe width="125px" height="75px"
                                        src="https://www.youtube.com/embed/qZ7auhJuPIM?si=k-VRUp-T1ECoWKai"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                </iframe> -->
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
<script>
    $(document).ready(function () {
      $("#form_data").submit(function (e) {
          $(".login_button").attr("disabled", true);
          return true;
      });
    });
    </script>
    @if(module_is_active('GoogleCaptcha') && (isset($admin_settings['google_recaptcha_is_on']) ? $admin_settings['google_recaptcha_is_on'] : 'off') == 'on' )
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush
