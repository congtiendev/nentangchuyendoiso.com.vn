<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div id="process_area" class="overflow-auto import-data-table">
            </div>
        </div>
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <a href="#" onclick="location.reload();" class="btn btn-secondary btn-light"
                data-bs-dismiss="modal">{{ __('Cancel') }}</a>
            <button type="submit" name="import" id="import" class="btn btn-primary ms-2"
                disabled>{{ __('Import') }}</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var total_selection = 0;

        var first_name = 0;

        var last_name = 0;

        var email = 0;

        var column_data = [];

        $(document).on('change', '.set_column_data', function() {
            var column_name = $(this).val();

            var column_number = $(this).data('column_number');
            // Bắt sự kiện thay đổi thì xóa dữ liệu cũ trong column_data

            if (column_name in column_data) {

                toastrs('Error', 'Đã tồn tại cột ' + column_name, 'error');

                $(this).val('');
                return false;
            }
            if (column_name != '') {
                column_data[column_name] = column_number;
            } else {
                const entries = Object.entries(column_data);
                for (const [key, value] of entries) {
                    if (value == column_number) {
                        delete column_data[key];
                    }
                }
            }

            total_selection = Object.keys(column_data).length;
            if (total_selection == 3) {
                $("#import").removeAttr("disabled");
                name = column_data.name;
                email = column_data.email;
                mobile_no = column_data.mobile_no;
            } else {
                $('#import').attr('disabled', 'disabled');
            }

        });

        $(document).on('click', '#import', function(event) {

            event.preventDefault();
            var role = [];
            $(".role-name-value").each(function() {
                var value = $(this).val();
                role.push(value);
            })
            $.ajax({
                url: "{{ route('users.import.data') }}",
                method: "POST",
                data: {
                    name: name,
                    email: email,
                    mobile_no: mobile_no,
                    role: role,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('#import').attr('disabled', 'disabled');
                    $('#import').text('Importing...');
                },
                success: function(data) {
                    $('#import').attr('disabled', false);
                    $('#import').text('Import');
                    $('#upload_form')[0].reset();

                    if (data.html == true) {
                        $('#process_area').html(data.response);
                        $("button").hide();
                        toastrs('Error', 'These data are not inserted', 'error');

                    } else {
                        $('#message').html(data.response);
                        $('#commonModalOver').modal('hide')
                        toastrs('Success', data.response, 'success');
                        location.reload();
                    }

                }
            })

        });
    });
</script>
