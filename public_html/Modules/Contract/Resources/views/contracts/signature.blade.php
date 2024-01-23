<form id='form_pad' method="post" enctype="multipart/form-data">
    @method('POST')
    <div class="modal-body" id="">
        <div class="row">
            <input type="hidden" name="contract_id" value="{{ $contract->id }}">
            <div class="form-control">
                <canvas id="signature-pad" class="signature-pad" height="300px"></canvas>
                <input type="hidden"
                    @if (Auth::user()->type == 'company') name="owner_signature" @else name="client_signature" @endif
                    id="SignupImage1">
            </div>
            <div class="mt-1 d-flex justify-content-between">
                <button type="button" class="btn btn-danger" id="clearSig">{{ __('Clear') }}</button>
                <button type="button" class="btn btn-primary" id="ca__cloud">CA Clould</button>
                <button type="button" class="btn btn-primary" id="usb_token">USB Token</button>
                <input style="width:110px;" type="file" id="addImgSig" value="{{ __('Tải lên') }}"
                    class="btn btn-primary btn-sm float-left waves-effect waves-light ">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="button" id="addSig" value="{{ __('Sign') }}" class="btn btn-primary ms-2">
    </div>
</form>

<script src="{{ asset('Modules/Contract/Resources/assets/js/signature_pad/signature_pad.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $('#ca__cloud').click(function() {
       swal("{{ __('Warning') }}", "Vui lòng tích hợp dịch vụ ", "warning");
    });
    $('#usb_token').click(function() {
       swal("{{ __('Warning') }}", "Vui lòng tích hợp dịch vụ ", "warning");
    });
    var signature = {
        canvas: null,
        clearButton: null,
        uploadSignature: null,

        init: function init() {

            this.canvas = document.querySelector(".signature-pad");
            this.clearButton = document.getElementById('clearSig');
            this.saveButton = document.getElementById('addSig');
            this.uploadSignature = document.getElementById('addImgSig');
            signaturePad = new SignaturePad(this.canvas);
            // Bắt sự kiện upload file từ máy tính
            this.uploadSignature.addEventListener('change', function(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = new Image();
                    img.onload = function() {
                        var canvas = document.getElementById('signature-pad');
                        var ctx = canvas.getContext('2d');
                        var maxWidth = 450; // Đặt kích thước tối đa mong muốn
                        var maxHeight = 300;
                        var width = img.width;
                        var height = img.height;
                        // Tính toán tỷ lệ thu phóng để giữ nguyên tỷ lệ khung hình
                        var scale = Math.min(maxWidth / width, maxHeight / height);
                        // Đặt kích thước mới
                        canvas.width = width * scale;
                        canvas.height = height * scale;
                        // Vẽ ảnh lên canvas
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                // disable canvas
                signaturePad.off();
            });
            this.clearButton.addEventListener('click', function(event) {
                signaturePad.clear();
                signaturePad.on();
            });
            this.saveButton.addEventListener('click', function(event) {
                var data = signaturePad.toDataURL('image/png');
                $('#SignupImage1').val(data);
                console.log(data);
                $.ajax({
                    url: '{{ route('signaturestore') }}',
                    type: 'POST',
                    data: $("form").serialize(),
                    success: function(data) {
                        toastrs('{{ __('Success') }}', 'Contract Signed Successfully',
                            'success');
                        $('#commonModal').modal('hide');
                    },
                    error: function(data) {}
                });
            });
        }
    };

    signature.init();
</script>
