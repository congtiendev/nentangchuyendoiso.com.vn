    {{-- email setting --}}
    <div class="card" id="email-sidenav">
        <div class="email-setting-wrap ">
            {{ Form::open(['route' => ['email.setting.store'], 'id' => 'mail-form']) }}
            @method('post')
            <div class="card-header">
                <h3 class="h5">{{ __('Email Settings') }}</h3>
            </div>
            <div class="card-body pb-0">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="mail_driver" class="form-label">{{ __('Mail Driver') }}</label>
                        {{ Form::text('mail_driver', isset($settings['mail_driver']) ? $settings['mail_driver'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver'), 'id' => 'mail_driver']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
                        {{ Form::text('mail_host', isset($settings['mail_host']) ? $settings['mail_host'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Host'), 'id' => 'mail_host']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail_port" class="form-label">{{ __('Mail Port') }}</label>
                        {{ Form::text('mail_port', isset($settings['mail_port']) ? $settings['mail_port'] : null, ['class' => 'form-control', 'placeholder' => __('Enter mail port'), 'required' => 'required', 'id' => 'mail_port']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail_username" class="form-label">{{ __('Mail Username') }}</label>
                        {{ Form::text('mail_username', isset($settings['mail_username']) ? $settings['mail_username'] : null, ['class' => 'form-control', 'placeholder' => __('Enter mail username'), 'required' => 'required', 'id' => 'mail_username']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail_password" class="form-label">{{ __('Mail Password') }}</label>
                        {{ Form::text('mail_password', isset($settings['mail_password']) ? $settings['mail_password'] : null, ['class' => 'form-control', 'placeholder' => __('Enter mail password'), 'required' => 'required', 'id' => 'mail_password']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail_encryption" class="form-label">{{ __('Mail Encryption') }}</label>
                        {{ Form::text('mail_encryption', isset($settings['mail_encryption']) ? $settings['mail_encryption'] : null, ['class' => 'form-control', 'placeholder' => __('Enter mail encryption'), 'required' => 'required', 'id' => 'mail_encryption']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail_from_address" class="form-label">{{ __('Mail From Address') }}</label>
                        {{ Form::text('mail_from_address', isset($settings['mail_from_address']) ? $settings['mail_from_address'] : null, ['class' => 'form-control ', 'placeholder' => __('Enter mail from address'), 'required' => 'required', 'id' => 'mail_from_address']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail_from_name" class="form-label">{{ __('Mail From Name') }}</label>
                        {{ Form::text('mail_from_name', isset($settings['mail_from_name']) ? $settings['mail_from_name'] : null, ['class' => 'form-control', 'placeholder' => __('Enter mail from name'), 'required' => 'required', 'id' => 'mail_from_name']) }}
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between flex-wrap "style="gap:10px">

                <button type="button" data-url="{{ route('test.mail') }}"
                    data-title="{{ __('Send Test Mail') }}"
                    class="btn btn-print-invoice  btn-primary m-r-10 test-mail">{{ __('Send Test Mail') }}</button>

                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                    value="{{ __('Save Changes') }}">
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <!--Email Notification Settings-->
    <div class="card" id="email-notification-sidenav">
        <div class="email-setting-wrap ">
            {{ Form::open(['route' => ['email.notification.setting.store'], 'id' => 'mail-notification-form']) }}
            @method('post')
            <div class="card-header">
                <h3 class="h5">{{ __('Email Notification Settings') }}</h3>
            </div>
            <div class="card-body pb-0">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    @php
                        $active = 'active';
                    @endphp
                    @foreach ($email_notification_modules as $e_module)
                        @if (Laratrust::hasPermission($e_module . ' manage') ||
                                Laratrust::hasPermission(strtolower($e_module) . ' manage') ||
                                $e_module == 'general')
                            <li class="nav-item">
                                <a class="nav-link text-capitalize {{ $active }}"
                                    id="pills-{{ strtolower($e_module) }}-tab-email" data-bs-toggle="pill"
                                    href="#pills-{{ strtolower($e_module) }}-email" role="tab"
                                    aria-controls="pills-{{ strtolower($e_module) }}-email"
                                    aria-selected="true">{{ Module_Alias_Name($e_module) }}</a>
                            </li>
                            @php
                                $active = '';
                            @endphp
                        @endif
                    @endforeach
                </ul>
                <div class="tab-content mb-3" id="pills-tabContent">
                    @foreach ($email_notification_modules as $e_module)
                        <div class="tab-pane fade {{ $loop->index == 0 ? 'active' : '' }} show"
                            id="pills-{{ strtolower($e_module) }}-email" role="tabpanel"
                            aria-labelledby="pills-{{ strtolower($e_module) }}-tab-email">
                            <div class="row">
                                @foreach ($email_notify as $e_action)
                                    @if ($e_action->permissions == null || Laratrust::hasPermission($e_action->permissions) )
                                        @if ($e_action->module == $e_module)
                                            <div class="col-lg-4 col-md-6 col-12">
                                                <div
                                                    class="d-flex align-items-center justify-content-between list_colume_notifi pb-2 mb-3">
                                                    <div class="mb-3 mb-sm-0">
                                                        <h6>
                                                            <label for="{{ $e_action->action }}"
                                                                class="form-label">{{ $e_action->action }}</label>
                                                        </h6>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="form-check form-switch d-inline-block">
                                                            <input type="hidden"
                                                                name="mail_noti[{{ $e_action->action }}]"
                                                                value="0" />
                                                            <input class="form-check-input"
                                                                {{ isset($settings[$e_action->action]) && $settings[$e_action->action] == true ? 'checked' : '' }}
                                                                id="mail_notificaation"
                                                                name="mail_noti[{{ $e_action->action }}]"
                                                                type="checkbox" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                    value="{{ __('Save Changes') }}">
            </div>
            {{ Form::close() }}
        </div>
    </div>
        <script>
            /* Open Test Mail Modal */
            $(document).on('click', '.test-mail', function(e) {
                e.preventDefault();
                var title = $(this).attr('data-title');
                var size = 'md';
                var url = $(this).attr('data-url');
                if (typeof url != 'undefined') {
                    $("#commonModal .modal-title").html(title);
                    $("#commonModal .modal-dialog").addClass('modal-' + size);
                    $("#commonModal").modal('show');

                    $.post(url, {
                        mail_driver: $("#mail_driver").val(),
                        mail_host: $("#mail_host").val(),
                        mail_port: $("#mail_port").val(),
                        mail_username: $("#mail_username").val(),
                        mail_password: $("#mail_password").val(),
                        mail_from_address: $("#mail_from_address").val(),
                        mail_encryption: $("#mail_encryption").val(),
                        _token: "{{ csrf_token() }}",
                    }, function(data) {
                        $('#commonModal .body').html(data);
                    });
                }
            })
            /* End Test Mail Modal */

            /* Test Mail Send
              ----------------------------------------*/

              $(document).on('click', '#test-send-mail', function() {
                $('#test-mail-form').ajaxForm({
                    beforeSend: function () {
                        $(".loader-wrapper").removeClass('d-none');
                    },
                    success: function (res) {
                        $(".loader-wrapper").addClass('d-none');
                        if (res.flag == 1) {
                            toastrs('Success', res.msg, 'success');
                            $('#commonModal').modal('hide');
                        } else {
                            toastrs('Error', res.msg, 'error');
                        }
                    },
                    error: function(xhr){
                        $(".loader-wrapper").addClass('d-none');
                        toastrs('Error', xhr.responseJSON.error, 'error');
                    }
                }).submit();
            });

        </script>
