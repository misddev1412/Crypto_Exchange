@extends('layouts.admin')
@section('title', 'Email Setup')
@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h3 class="card-title">Email Templates<br></h3>
                            <div class="card-opt">
                                <ul class="btn-grp btn-grp-block guttar-20px">
                                    <li>
                                        <a href="#mailSetting" class="btn btn-auto btn-primary btn-sm" data-toggle="modal">
                                            <i class="fas fa-envelope"></i><span><span class="d-none d-sm-inline-block">Email</span> Settings</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-text">
                            <ul class="list list-s1 list-col2x">
                                @foreach($templates as $item)
                                <li class="item">
                                    <div class="list-content justify-content-between">
                                        <span>{{ $item->name }}</span>
                                        <div class="d-flex guttar-10px">
                                            <div class="action-btn">
                                                <a class="btn btn-xs btn-icon btn-circle btn-light-alt et-item" href="javascript:void(0)" data-slug="{{ $item->slug }}" data-id="{{ $item->id }}" ><em class="far fa-edit"></em></a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modals')
<div class="modal fade" id="mailSetting" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <form action="{{ route('admin.ajax.settings.email.update') }}" autocomplete = "false" method="POST" id="email_settings">
                    @csrf
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="card-title">Mailing Setting</h3>
                        </div>
                        <div class="gaps-2x"></div>
                        <h5 class="text-primary">Choose Email Driver </h5>
                        <div class="row">
                            <div class="col-4">
                                <div class="input-item input-with-label">
                                    <input id="smtp_uchk" class="mail-chk input-checkbox" type="radio" {{ email_setting('driver', env('MAIL_DRIVER', 'sendmail')) == 'sendmail' ? 'checked' : '' }} name="site_mail_driver" value="sendmail">
                                    <label for="smtp_uchk">Send Mail</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-item input-with-label">
                                    <input id="smtp_chk" class="mail-chk input-checkbox" type="radio" {{ email_setting('driver', env('MAIL_DRIVER', 'smtp')) == 'smtp' ? 'checked' : '' }} name="site_mail_driver" value="smtp">
                                    <label for="smtp_chk">SMTP</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-item input-with-label">
                                    <input id="mail" class="mail-chk input-checkbox" type="radio" {{ email_setting('driver', env('MAIL_DRIVER', 'mail')) == 'mail' ? 'checked' : '' }} name="site_mail_driver" value="mail">
                                    <label for="mail">Mail </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="smtp-box">
                            <div class="col-12 col-md-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">SMTP HOST</label>
                                    <input class="input-bordered" type="text" name="site_mail_host" placeholder="" value="{{ email_setting('host', env('MAIL_HOST')) }}">
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">SMTP Port</label>
                                    <input class="input-bordered" type="number" name="site_mail_port" value="{{ email_setting('port', env('MAIL_PORT')) }}" placeholder="587" >
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">SMTP Secure</label>
                                    <input class="input-bordered" type="text" name="site_mail_encryption" value="{{ email_setting('encryption', env('MAIL_ENCRYPTION', 'tls')) }}" placeholder="tls" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">SMTP UserName</label>
                                    <input class="input-bordered" type="text" name="site_mail_username" placeholder="" value="{{ (Auth::user()->type == 'demo')? "hide@ouremail.address" : email_setting('user_name', env('MAIL_USERNAME')) }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">SMTP Password</label>
                                    <input class="input-bordered" type="password" autocomplete="new-password" name="site_mail_password" value="{{ (Auth::user()->type == 'demo')? "" : email_setting('password', env('MAIL_PASSWORD')) }}" placeholder="********">
                                </div>
                            </div>
                        </div>
                        <div class="gaps-1x"></div>
                        <div class="sap"></div>
                        <div class="gaps-2x"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Email From Address</label>
                                    <input class="input-bordered" type="email" name="site_mail_from_address" value="{{ email_setting('from_address') }}" placeholder="info@sitename.com" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Email From Name</label>
                                    <input class="input-bordered" type="text" name="site_mail_from_name" value="{{ email_setting('from_name') }}" placeholder="Site Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Email Global Footer</label>
                                    <textarea class="input-bordered" name="site_mail_footer" id="gblfootr" cols="30" rows="4">{{ get_setting('site_mail_footer') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Email Send to Admin</label>
                                    <div class="input-wrap">
                                        <select name="send_notification_to" id="ntf" class="select select-bordered">
                                            <option {{ get_setting('send_notification_to') == 'all' ? 'selected' : '' }} value="all">All Admin</option>
                                            @foreach($admins as $admin)
                                            <option {{ get_setting('send_notification_to') == $admin->id ? 'selected' : '' }} value="{{ $admin->id }}">{{ $admin->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-item input-with-label">
                                    <label for="emails" class="input-item-label">Enter External Emails</label>
                                    <div class="input-wrap">
                                        <input name="send_notification_mails" id="emails" type="text" class="input-bordered" value="{{ get_setting('send_notification_mails') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="ti ti-reload"></i><span>Update</span></button>
                    </div>
                    </div>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
{{-- Modal End --}}
@endsection

@push('footer')
@if (isset($_GET['setup']))
<script>
    $(function() {
        $('#mailSetting').modal('show');
    });
</script>
@endif
@endpush
