

<div class="card">
    <div class="card-body text-center">
        <div class="qr-main-image p-5 position-relative ">
            <img src="{{ asset('Modules/CMMS/Resources/assets/custom/img/left-top.svg') }}" alt="left-top" class="img-fluid absolutr-left-top-border">
            <img src="{{ asset('Modules/CMMS/Resources/assets/custom/img/right-top.svg') }}" alt="right-top" class="img-fluid absolutr-right-top-border">
            <img src="{{ asset('Modules/CMMS/Resources/assets/custom/img/left-bottom.svg') }}" alt="left-bottom" class="img-fluid absolutr-left-bottom-border">
            <img src="{{ asset('Modules/CMMS/Resources/assets/custom/img/right-bottom.svg') }}" alt="right-bottom" class="img-fluid absolutr-right-bottom-border">
            <div class="qrcode"></div>
        </div>
        <div class="text">
            <p>{{__('Point your camera at the QR code, or visit')}}</p>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.qrcode').qrcode("{{route('work_request.portal',[\Illuminate\Support\Facades\Crypt::encrypt($id) , 'en'])}}");
</script>
