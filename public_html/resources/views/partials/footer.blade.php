<footer style="background-color :#0CAF60;" class="dash-footer">
    <div class="footer-wrapper justify-content-center">
        <div class="py-1">
            <span style="color: #ffffff !important;" class="text-muted text-white">
                {{-- @if (isset($company_settings['footer_text']))
                {{ $company_settings['footer_text'] }}
                @elseif(isset($admin_settings['footer_text']))
                {{ $admin_settings['footer_text'] }}
                @else
                {{ __('Copyright') }} &copy; {{ config('app.name', 'WorkDo') }}
                @endif
                {{ date('Y') }} --}}
                © 2024 Nền tảng chuyển đổi số doanh nghiệp CRM - QT
            </span>
        </div>
    </div>
</footer>



@if (Route::currentRouteName() !== 'chatify')
<div id="commonModal" class="modal" tabindex="-1" aria-labelledby="exampleModalLongTitle" aria-modal="true"
    role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="commonModalOver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
        </div>
    </div>
</div>
@endif
<div class="loader-wrapper d-none">
    <span class="site-loader"> </span>
</div>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"> </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<!-- Required Js -->


<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '.read__notification', function () {
        const notification_id = $(this).data('notification-id');
        const user_id = $(this).data('user-id');
        const token = $(this).data('token');
        const url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                notification_id: notification_id,
                user_id: user_id,
                _token: "{{ csrf_token() }}",
            },
            success: function (data) {
                if (data.status == 1) {
                    $('.custom_notification_counter').html(data.count);
                    $('.read__notification').removeClass('read__notification');
                }else{
                   swal("{{ __('Something went wrong!') }}", {
                        icon: "error",
                    });
                }
            }
        });
    });
});
</script>


<script src="{{ asset('js/custom.js') }}"></script>
@if ($message = Session::get('success'))
<script>
    toastrs('Success', '{!! $message !!}', 'success');
</script>
@endif
@if ($message = Session::get('error'))
<script>
    toastrs('Error', '{!! $message !!}', 'error');
</script>
@endif
@stack('scripts')
@include('Chatify::layouts.footerLinks')
@if (isset($admin_settings['enable_cookie']) && $admin_settings['enable_cookie'] == 'on')
@include('layouts.cookie_consent')
@endif
</body>

</html>