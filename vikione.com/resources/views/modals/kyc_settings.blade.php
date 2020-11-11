@php
$wallet_opt = field_value_text('kyc_wallet_opt' , 'wallet_opt');
is_array($wallet_opt) ? true : $wallet_opt = array();
$custom = field_value_text('kyc_wallet_custom');
is_array($custom) ? true : $custom = array();
@endphp

<div class="modal fade" id="kyc-settings" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" ><em class="ti ti-close"></em></a>
            <div id="base-body" class="popup-body-full">
                {{-- Content Start --}}
                <div class="popup-header">
                    <h3 class="popup-title">KYC Form Settings</h3>
                    <p>You can manage currency what you want to use in payment system. You can use one or multiple currency from below option.</p>
                </div>
                <form action="{{ route('admin.ajax.kyc.update') }}" method="POST" id="kyc_settings">
                    @csrf
                    <input type="hidden" name="req_type" value="update_kyc_settings">
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">General Settings</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Application hide from Userend</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-opt-hide" name="kyc_opt_hide" value="1" {{ field_value('kyc_opt_hide') == 1 ? ' checked' : '' }} type="checkbox">
                                        <label for="kyc-opt-hide">Enable</label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Accessible without Sign up</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-public" name="kyc_public" {{ field_value('kyc_public') == 1 ? ' checked' : '' }} type="checkbox">
                                        <label for="kyc-public">Enable</label>
                                    </div>
                                    <div class="col-12">
                                        <div class="gaps-1x"></div>
                                        <p class="small">Public KYC Application URL : <a href="{{ route('public.kyc') }}">{{ route('public.kyc') }}</a></p>
                                    </div>
                                </div>
                            </div>

                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Email Verified before Submit</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-before-email" name="kyc_before_email" value="1" {{ field_value('kyc_before_email') == 1 ? ' checked' : '' }} type="checkbox">
                                        <label for="kyc-before-email">Enable</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">KYC Before Token Purchase</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-before-toekn" name="token_before_kyc" value="1" {{ token('before_kyc') == 1 ? 'checked' : '' }} type="checkbox">
                                        <label for="kyc-before-toekn">Enable</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>{{-- .kyc-option --}}
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">Personal Form Options</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">First Name</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="fname-show" value="show"name="kyc_firstname[]"{{ field_value('kyc_firstname', 'show' ) ? ' checked' : '' }} type="checkbox" disabled>
                                        <label for="fname-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="fname-req" value="req" name="kyc_firstname[]"{{ field_value('kyc_firstname', 'req' ) ? ' checked' : '' }} type="checkbox" disabled>
                                        <label for="fname-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Last Name</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="lname-show" value="show" name="kyc_lastname[]"{{ field_value('kyc_lastname', 'show' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="lname-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="lname-req" value="req" name="kyc_lastname[]"{{ field_value('kyc_lastname', 'req' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="lname-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Email Address</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-email-show" value="show" name="kyc_email[]"{{ field_value('kyc_email', 'show' ) ? ' checked' : '' }} type="checkbox" disabled="disabled">
                                        <label for="kyc-email-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="kyc-email-req" value="req" name="kyc_email[]"{{ field_value('kyc_email', 'req' ) ? ' checked' : '' }} type="checkbox" disabled="disabled">
                                        <label for="kyc-email-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Phone Number</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="phone-show" value="show" name="kyc_phone[]"{{ field_value('kyc_phone', 'show' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="phone-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="phone-req" value="req" name="kyc_phone[]"{{ field_value('kyc_phone', 'req' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="phone-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Date of Birth</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="dob-show" value="show" name="kyc_dob[]" {{ field_value('kyc_dob', 'show' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="dob-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="dob-req" value="req" name="kyc_dob[]" {{ field_value('kyc_dob', 'req' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="dob-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Gender</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="gender-show" value="show" name="kyc_gender[]" {{ field_value('kyc_gender', 'show' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="gender-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="gender-req" value="req" name="kyc_gender[]" {{ field_value('kyc_gender', 'req' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="gender-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Country</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="country-show" value="show" name="kyc_country[]" {{ field_value('kyc_country', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="country-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="country-req" value="req" name="kyc_country[]" {{ field_value('kyc_country', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="country-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Address Line 1</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="address-l1-show" value="show" name="kyc_address1[]"{{ field_value('kyc_address1', 'show') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l1-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="address-l1-req" value="req" name="kyc_address1[]"{{ field_value('kyc_address1', 'req') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l1-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Address Line 2</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="address-l2-show" value="show" name="kyc_address2[]"{{ field_value('kyc_address2', 'show') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l2-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="address-l2-req" value="req" name="kyc_address2[]"{{ field_value('kyc_address2', 'req') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l2-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">City</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="city-show" value="show" name="kyc_city[]" {{ field_value('kyc_city', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="city-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="city-req" value="req" name="kyc_city[]" {{ field_value('kyc_city', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="city-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">State</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="state-show" value="show" name="kyc_state[]" {{ field_value('kyc_state', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="state-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="state-req" value="req" name="kyc_state[]" {{ field_value('kyc_state', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="state-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Zip Code</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="zip-code-show" value="show" name="kyc_zip[]" {{ field_value('kyc_zip', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="zip-code-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="zip-code-req" value="req" name="kyc_zip[]" {{ field_value('kyc_zip', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="zip-code-req">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Telegram</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="telegram-show" value="show" name="kyc_telegram[]" {{ field_value('kyc_telegram', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="telegram-show">Show</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="telegram-req" value="req" name="kyc_telegram[]" {{ field_value('kyc_telegram', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="telegram-req">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>{{-- .kyc-option --}}
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">Document Verification Options</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Verify by Passport</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="passport-enable"  name="kyc_document_passport" {{ get_setting('kyc_document_passport') ? 'checked' : '' }} type="checkbox">
                                        <label for="passport-enable">Enable</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Verify by National Card</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="nid-enable" name="kyc_document_nidcard" {{ get_setting('kyc_document_nidcard') ? 'checked' : '' }} type="checkbox">
                                        <label for="nid-enable">Enable</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Verify by Driving License</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="dlicense-enable" name="kyc_document_driving" {{ get_setting('kyc_document_driving') ? 'checked' : '' }} type="checkbox">
                                        <label for="dlicense-enable">Enable</label>
                                    </div>
                                </div>
                            </div>
                            <span class="input-note">
                                * Only one way will be required IF multiple option enable.
                            </span>
                            <div class="gaps-1x"></div>
                        </div>
                    </div>{{-- .kyc-option --}}
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">Paying Wallet Option</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">Wallet Address</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="input-wrap">
                                            <input class="input-switch input-switch-sm" id="wallet-show" value="show" name="kyc_wallet[]" {{ field_value('kyc_wallet', 'show') ? 'checked' : '' }} type="checkbox">
                                            <label for="wallet-show">Show</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="input-wrap">
                                            <input class="input-checkbox input-checkbox-sm" id="wallet-req" value="req" name="kyc_wallet[]" {{ field_value('kyc_wallet', 'req') ? 'checked' : '' }} type="checkbox">
                                            <label for="wallet-req">Required</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item input-with-label">
                                        <h6 class="kyc-option-subtitle">Supported Wallet</h6>
                                        <div class="input-wrap">
                                            <select  name="kyc_wallet_opt[]" class="select select-block select-bordered" value="" data-placeholder="Select Options" multiple="multiple">
                                                @foreach($available_wallets as $name => $wallet)
                                                <option {{in_array($name, $wallet_opt )? 'selected' : ''}} value="{{ $name }}">{{ $wallet }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-item input-with-label">
                                        <h6 class="kyc-option-subtitle">Note for Wallet</h6>
                                        <div class="input-wrap">
                                            <input class="input-bordered" type="text" name="kyc_wallet_note" value="{{ get_setting('kyc_wallet_note')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row guttar-15px align-items-center">
                                <div class="col-sm-6">
                                    <div class="input-item input-with-label">
                                        <h6 class="kyc-option-subtitle">Own Custom Wallet</h6>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-6">
                                   <div class="input-item input-with-label">
                                        <div class="input-wrap">
                                            <input class="input-bordered" placeholder="wallet-name" type="text" name = "kyc_wallet_custom[]" value="{{ (!empty($custom['cw_name']) ? $custom['cw_name'] : '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <div class="input-item input-with-label">
                                        <div class="input-wrap">
                                            <input class="input-bordered" placeholder="Wallet Label" type="text" name="kyc_wallet_custom[]" value="{{ (!empty($custom['cw_text']) ? $custom['cw_text'] : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <button class="btn btn-primary save-disabled" type="submit" disabled="disabled">Update</button>
                    </div>
                </form>
                {{-- Content End --}}
            </div>
        </div>
    </div>
</div>
