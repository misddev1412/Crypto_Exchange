@extends('layouts.admin')
@section('title', 'Website Settings')
@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @include('layouts.messages')
                @include('vendor.notice')
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">Website Settings</h4>
                        </div>
                        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#siteinfo">Site Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#general">General Settings</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#social_links">Social Links</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#social_api">API Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#custom_code">Advanced</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#transaction">Transactions</a>
                            </li>
                        </ul>{{-- .nav-tabs-line --}}

                        <div class="tab-content" id="website-setting">
                            <div class="tab-pane fade show active " id="siteinfo">
                                <form action="{{ route('admin.ajax.settings.update') }}" class="validate-modern" method="POST" id="update_settings">
                                    @csrf
                                    <input type="hidden" name="type" value="site_info">
                                    <div class="d-flex align-items-center justify-content-between pdb-1x">
                                        <h5 class="card-title-md text-primary">Website Information</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Site Name</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text" data-validation="required" name="site_name" value="{{ get_setting('site_name') }}">
                                                </div>
                                                <span class="input-note">Enter name of website name.</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Site Email</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text" data-validation="required" name="site_email" value="{{ get_setting('site_email') }}">
                                                </div>
                                                <span class="input-note">Using for contact and sending email.</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Site Copyright</label>
                                                <input class="input-bordered" type="text" name="site_copyright" value="{{  get_setting('site_copyright')  }}">
                                                <span class="input-note">Copyright text for site.</span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Contact Address</label>
                                                <input class="input-bordered" type="text" data-validation="required" name="site_support_address" value="{{ get_setting('site_support_address') }}">
                                                <span class="input-note">Enter the support address.</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Contact Phone</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" minlength="10" type="text" data-validation="required" name="site_support_phone" value="{{ get_setting('site_support_phone') }}">
                                                    <span class="input-note">Using for contact and support.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Support Email</label>
                                                <input class="input-bordered" type="text" name="site_support_email" value="{{  get_setting('site_support_email')  }}">
                                                <span class="input-note">Contact and Support Email.</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Main Site URL</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" name="main_website_url" value="{{  get_setting('main_website_url')  }}">
                                                </div>
                                                <span class="input-note">Set your main website url.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gaps-1x"></div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary save-disabled" disabled><i class="ti ti-reload mr-2"></i>Update</button>
                                    </div>
                                    <div class="gaps-0-5x"></div>
                                </form>
                            </div>
                            <div class="tab-pane fade " id="general">
                                <form action="{{ route('admin.ajax.settings.update') }}" class="validate-modern" method="post" id="update_general_settings">
                                    @csrf
                                    <input type="hidden" name="type" value="general">
                                    <div class="d-flex align-items-center justify-content-between pdb-1x">
                                        <h5 class="card-title-md text-primary">Application Settings</h5>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-lg-3 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label for="site-timezone" class="input-item-label">Time Zone</label>
                                                <select name="site_timezone" id="site-timezone" class="select select-block select-bordered">
                                                    @foreach($timezones as $timezone => $hrf)
                                                    <option value="{{ $timezone }}" {{ ($timezone == get_setting('site_timezone', 'UTC') ? 'selected' : '') }}>{{ $hrf }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-note">Set application timezone.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-3 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Date Format</label>
                                                <select name="site_date_format" id="site_date_format" class="select select-block select-bordered">
                                                    <option {{ (get_setting('site_date_format') == 'd M, Y' ? 'selected' : '') }} value="d M, Y">{{ date('d M, Y') }}</option>
                                                    <option {{ (get_setting('site_date_format') == 'M d, Y' ? 'selected' : '') }} value="M d, Y">{{ date('M d, Y') }}</option>
                                                    <option {{ (get_setting('site_date_format') == 'd M, y' ? 'selected' : '') }} value="d M, y">{{ date('d M, y') }}</option>
                                                    <option {{ (get_setting('site_date_format') == 'm-d-Y' ? 'selected' : '') }} value="m-d-Y">{{ date('m-d-Y') }}</option>
                                                    <option {{ (get_setting('site_date_format') == 'd-m-Y' ? 'selected' : '') }} value="d-m-Y">{{ date('d-m-Y') }}</option>
                                                    <option {{ (get_setting('site_date_format') == 'Y-m-d' ? 'selected' : '') }} value="Y-m-d">{{ date('Y-m-d') }}</option>
                                                    <option {{ (get_setting('site_date_format') == 'm-d-y' ? 'selected' : '') }} value="m-d-y">{{ date('m-d-y') }}</option>
                                                    <option {{ (get_setting('site_date_format') == 'y-m-d' ? 'selected' : '') }} value="y-m-d">{{ date('y-m-d') }}</option>
                                                </select>
                                                <span class="input-note">Application date format</span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-lg-3 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Time Format</label>
                                                <div class="input-wrap input-wrap-switch">
                                                    <select name="site_time_format" id="site_time_format" class="select select-block select-bordered">
                                                        <option {{ (get_setting('site_time_format') == 'h:i A' ? 'selected' : '') }} value="h:i A">11:12 AM</option>
                                                        <option {{ (get_setting('site_time_format') == 'H:i' ? 'selected' : '') }} value="H:i">15:30 (24 hr)</option>
                                                        <option {{ (get_setting('site_time_format') == 'H:i:s' ? 'selected' : '') }} value="H:i:s">15:30:25 (24 hr)</option>
                                                    </select>
                                                </div>
                                                <span class="input-note">Application time format</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gaps-1x"></div><div class="sap"></div><div class="gaps-3x"></div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-3 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Admin Theme</label>
                                                <div class="input-wrap">
                                                    <select name="theme_admin" id="theme_admin" class="select select-block select-bordered">
                                                        @foreach (config('icoapp.themes') as $theme =>$tm_name)
                                                        <option {{(get_setting('theme_admin', 'style') == $theme)?'selected ':''}}value="{{ $theme }}">{{$tm_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="input-note">Style scheme for admin area.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-3 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Auth Page Layout</label>
                                                <div class="input-wrap">
                                                    <select name="theme_auth_layout" id="theme_auth_layout" class="select select-block select-bordered">
                                                        <option {{ (get_setting('theme_auth_layout') == 'default' ? 'selected' : '') }} value="default">Default</option>
                                                        <option {{ (get_setting('theme_auth_layout') == 'alter' ? 'selected' : '') }} value="alter">Alter Side</option>
                                                        <option {{ (get_setting('theme_auth_layout') == 'center-light' ? 'selected' : '') }} value="center-light">Center Light</option>
                                                        <option {{ (get_setting('theme_auth_layout') == 'center-dark' ? 'selected' : '') }} value="center-dark">Center Dark</option>
                                                    </select>
                                                </div>
                                                <span class="input-note">Login/Registration page design layout.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-6 col-sm-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">User Panel Theme</label>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="input-wrap">
                                                            <select name="theme_user" id="theme_user" class="select select-block select-bordered">
                                                                @foreach (config('icoapp.themes') as $theme =>$tm_name)
                                                                <option {{(get_setting('theme_user', 'style') == $theme)?'selected ':''}}value="{{ $theme }}">{{$tm_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="input-wrap input-wrap-checkbox mgt-1x">
                                                            <input class="input-checkbox input-checkbox-sm" type="checkbox" name="theme_custom" id="theme_custom" {{ get_setting('theme_custom') == 1 ? 'checked' : '' }}>
                                                            <label for="theme_custom">Enable Custom Stylesheet</label>
                                                        </div>
                                                    </div>   
                                                </div>
                                                <span class="input-note">Style scheme for user area.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Maintenance Mode</label>
                                                <div class="input-wrap input-wrap-switch">
                                                    <input class="input-switch switch-toggle" data-switch="switch-to-maintenance" type="checkbox" name="site_maintenance" id="site_maintenance" {{ get_setting('site_maintenance') == 1 ? 'checked' : '' }}>
                                                    <label for="site_maintenance">Enable</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="switch-content switch-to-maintenance">
                                                <div class="input-item input-with-label">
                                                    <label class="input-item-label">Maintenance Text</label>
                                                    <div class="input-wrap">
                                                        <textarea class="input-bordered" name="site_maintenance_text" id="site_maintenance_text" cols="30" rows="2">{{ get_setting('site_maintenance_text') }}</textarea>
                                                    </div>
                                                    <div class="input-note">Admin Login on maintenance mode: <strong class="text-primary">{{ route('admin.login') }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gaps-1x"></div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary save-disabled" disabled><i class="ti ti-reload mr-2"></i>Update</button>
                                    </div>
                                    <div class="gaps-0-5x"></div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="social_links">
                                <form action="{{ route('admin.ajax.settings.update') }}" class="validate-modern" method="post" id="update_social_settings">
                                    @php
                                    $links = json_decode( get_setting('site_social_links') );
                                    @endphp
                                    @csrf
                                    <input type="hidden" name="type" value="social_links">
                                    <div class="d-flex align-items-center justify-content-between pdb-1x">
                                        <h5 class="card-title-md text-primary">Social Profile Links</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4 col-sm-12">
                                            <div class="input-item input-with-label pb-0">
                                                <div class="input-wrap input-wrap-switch">
                                                    <input class="input-switch input-switch-sm" type="checkbox" name="social[onsite]" id="social-on-site" {{ (isset($links->onsite) && $links->onsite) ? 'checked' : '' }}>
                                                    <label for="social-on-site">Show on User/Client Area</label>
                                                </div>
                                            </div>
                                        </div>
										<div class="col-md-6 col-lg-4 col-sm-12">
                                            <div class="input-item input-with-label">
                                                <div class="input-wrap input-wrap-switch">
                                                    <input class="input-switch input-switch-sm" type="checkbox" name="social[onlogin]" id="social-on-login-reg" {{ (isset($links->onlogin) && $links->onlogin) ? 'checked' : '' }}>
                                                    <label for="social-on-login-reg">Show on Login/Register Page</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sap"></div><div class="gaps-2x"></div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4 col-sm-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Facebook</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" placeholder="https://www.facebook.com/user-name" data-validation="required" name="social[facebook]" value="{{ isset($links->facebook) ? $links->facebook : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-sm-12">

                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Twitter</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" placeholder="https://twitter.com/user-name" data-validation="required" name="social[twitter]" value="{{ isset($links->twitter) ? $links->twitter : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-sm-12">

                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Linked In</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" placeholder="https://www.linkedin.com/user-name" data-validation="required" name="social[linkedin]" value="{{ isset($links->linkedin) ? $links->linkedin : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-sm-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Github</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" placeholder="https://www.github.com/user-name" data-validation="required" name="social[github]" value="{{ isset($links->github) ? $links->github : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-sm-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Medium</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" placeholder="https://www.medium.com/@user-name" data-validation="required" name="social[medium]" value="{{ isset($links->medium) ? $links->medium : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-sm-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Youtube</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" placeholder="https://www.youtube.com/user-name" data-validation="required" name="social[youtube]" value="{{ isset($links->youtube) ? $links->youtube : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-sm-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Telegram</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="url" placeholder="https://telegram.org/@user-name" data-validation="required" name="social[telegram]" value="{{ isset($links->telegram) ? $links->telegram : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gaps-1x"></div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary save-disabled" disabled><i class="ti ti-reload mr-2"></i>Update</button>
                                    </div>
                                    <div class="gaps-0-5x"></div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="social_api">
                                <form action="{{ route('admin.ajax.settings.update') }}" method="post" class="validate-modern" id="update_api_settings" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="type" value="api_credetial">
                                    <div class="d-flex align-items-center justify-content-between pdb-1x">
                                        <h5 class="card-title-md text-primary">Google reCaptcha v3</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-sm-12 col-md-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Site Key</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="text" name="recaptcha_site_key" autocomplete="new-code-recap-site" value="{{ get_setting('recaptcha_site_key') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 col-md-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Secret Key</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="text" name="recaptcha_secret_key" autocomplete="new-code-recap-secret" value="{{ get_setting('recaptcha_secret_key') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-note pt-0">Get the API Key <strong class="text-primary"><a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a></strong></div>
                                            <div class="gaps-2-5x"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pdb-1x">
                                        <h5 class="card-title-md text-primary">Social Login API Credentials</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-sm-12 col-md-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Facebook Client ID</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="text" name="api_fb_id" autocomplete="off" value="{{ get_setting('site_api_fb_id') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 col-md-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Facebook Client Secret</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="password" name="api_fb_secret" autocomplete="new-password" value="{{ get_setting('site_api_fb_secret') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-note pt-0">In Facebook App set redirect URL: <strong class="text-primary">{{ config('services.facebook.redirect') }}</strong></div>
                                            <div class="gaps-2-5x"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-sm-12 col-md-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Google Client ID</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="text" name="api_google_id" autocomplete="new-code-api-gid" value="{{ get_setting('site_api_google_id') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 col-md-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Google Client Secret</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="password" name="api_google_secret" autocomplete="new-code-api-gsecret" value="{{ get_setting('site_api_google_secret') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-note pt-0">In Google App set redirect URL: <strong class="text-primary">{{ config('services.google.redirect') }}</strong></div>
                                            <div class="gaps-2-5x"></div>
                                        </div>
                                    </div>
                                    <div class="gaps-1x"></div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary save-disabled" disabled><i class="ti ti-reload mr-2"></i>Update</button>
                                    </div>
                                    <div class="gaps-0-5x"></div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="custom_code">
                                <form action="{{ route('admin.ajax.settings.update') }}" method="post" class="validate-modern" id="update_code_settings" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="type" value="custom_code">
                                    <div class="d-flex align-items-center justify-content-between pdb-1x">
                                        <h5 class="card-title-md text-primary">Header & Footer Code</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Header Code</label>
                                                <div class="input-wrap">
                                                    <textarea name="site_header_code" id="header-code" cols="30" rows="5" class="input-bordered input-textarea">{{ get_setting('site_header_code') }}</textarea>
                                                </div>
                                                <div class="input-note">You can use this for analytics code. Please enter full code including &lt;script&gt; tag.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Footer Code</label>
                                                <div class="input-wrap">
                                                    <textarea name="site_footer_code" id="footer-code" cols="30" rows="5" class="input-bordered input-textarea">{{ get_setting('site_footer_code') }}</textarea>
                                                </div>
                                                <div class="input-note">You can use this for chat or third-party tracker codes. Please enter full code including &lt;script&gt; tag.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gaps-1x"></div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary save-disabled" disabled><i class="ti ti-reload mr-2"></i>Update</button>
                                    </div>
                                    <div class="gaps-0-5x"></div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="transaction">
                                <form action="{{ route('admin.ajax.settings.update') }}" method="post" class="validate-modern" id="update_code_settings" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="type" value="transaction">
                                    <div class="row">
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">A limit token of 1 transaction</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text" data-validation="required" name="limt_a_token" value="{{ get_setting('limt_a_token') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Limit token for transactions</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="" data-validation="required" name="limit_tokens" value="{{ get_setting('limit_tokens') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gaps-1x"></div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary save-disabled" disabled><i class="ti ti-reload mr-2"></i>Update</button>
                                    </div>
                                    <div class="gaps-0-5x"></div>
                                </form>
                            </div>
                        </div>
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection