@php
$option =  $defaultDoc = $defaultImg = ''; $wallets = array();
$wallet = field_value_text('kyc_wallet_opt', 'wallet_opt');
if($wallet) {
    foreach ($wallet as $wal) { 
        $wallets[$wal] = $wal; 
    }
}

$custom = field_value_text('kyc_wallet_custom');
if($custom['cw_name'] != '' && $custom['cw_text'] != ''){
    $wallets[$custom['cw_name']] = $custom['cw_text'];
}
$wallet_count = count($wallets);
if($wallet_count > 0){
    foreach($wallets as $wallet_opt => $value){
        $option .= '<option value="'.strtolower($value).'">'.ucfirst($value).'</option>';
    }
}

$has_wallet = (field_value('kyc_wallet', 'show' ) && $wallet_count >= 1);
$has_docs = (field_value('kyc_document_passport') || field_value('kyc_document_nidcard') || field_value('kyc_document_driving'));
$support_docs = array(
    'passport' => field_value('kyc_document_passport'), 
    'nidcard' => field_value('kyc_document_nidcard'), 
    'driving' => field_value('kyc_document_driving')
);
$default_docs = array();
foreach ($support_docs as $doc => $type){
    if($type) {
        $default_docs = array('doc' => $doc, 'name' => $title[$doc], 'image' => $doc);
        break;
    }
}
if (!empty($default_docs)) {
    $defaultDoc = $default_docs['name']; 
    $defaultImg = $default_docs['image'];
}

$step_01 = ($has_wallet || $has_docs) ? '01' : '';
$step_02 = ($step_01 && $has_docs) ? '02' : '';
$step_03 = ($has_wallet && $has_docs) ? '03' : (($has_wallet && !$has_docs) ? '02' : '');

@endphp

<div class="form-step form-step1">
    <div class="form-step-head card-innr">
        <div class="step-head">
            <div class="step-number">{{ $step_01 }}</div>
            <div class="step-head-text">
                <h4>{{__('Personal Details')}}</h4>
                <p>{{__('Your basic personal information is required for identification purposes.')}}</p>
            </div>
        </div>
    </div>{{-- .step-head --}}
    <div class="form-step-fields card-innr">
        <div class="note note-plane note-light-alt note-md pdb-1x">
            <em class="fas fa-info-circle"></em>
            <p>{{__('Please type carefully and fill out the form with your personal details. You are not allowed to edit the details once you have submitted the application.')}}</p>
        </div>
        <div class="row">
            @if(field_value('kyc_firstname', 'show'))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="first-name" class="input-item-label">{{__('First Name')}}  {!! required_mark('kyc_firstname') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_firstname', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" value = "{{ isset($user_kyc) ? $user_kyc->firstName : ''}}" id="first-name" name="first_name">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_lastname', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="last-name" class="input-item-label">{{__('Last Name')}} {!! required_mark('kyc_lastname') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_lastname', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" value = "{{ isset($user_kyc) ? $user_kyc->lastName : ''}}" type="text" id="last-name" name="last_name">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_email', 'show' ) && isset($input_email) && $input_email == true)
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="email" class="input-item-label">{{__('Email Address')}} {!! required_mark('kyc_email') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_email', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" value = "{{ isset($user_kyc) ? $user_kyc->email : ''}}" type="email" id="email" name="email">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif

            @if(!isset($user_kyc))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="password" class="input-item-label">{{__('Password')}} 
                        <span class="text-require text-danger">*</span>
                    </label>
                    <div class="input-wrap">
                        <input required class="input-bordered" placeholder="*******" type="password" minlength="6" id="password" name="password">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif

            @if(field_value('kyc_phone', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="phone-number" class="input-item-label">{{__('Phone Number ')}}{!! required_mark('kyc_phone') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_phone', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" value = "{{ isset($user_kyc) ? $user_kyc->phone : ''}}" id="phone-number" name="phone">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_dob', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="date-of-birth" class="input-item-label">{{__('Date of Birth')}} {!! required_mark('kyc_dob') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_dob', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered date-picker-dob" type="text" value = "{{ isset($user_kyc) ? $user_kyc->dob : ''}}" id="date-of-birth" name="dob">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_gender', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="gender" class="input-item-label">{{__('Gender')}} {!! required_mark('kyc_gender') !!}</label>
                    <div class="input-wrap">
                        <select {{ field_value('kyc_gender', 'req' ) == '1' ? 'required ' : '' }}class="select-bordered select-block" name="gender" id="gender">
                            <option value="">{{__('Select Gender')}}</option>
                            <option {{( (isset($user_kyc) ? $user_kyc->gender : '') == 'male')?"selected":"" }} value="male">{{__('Male')}}</option>
                            <option {{( (isset($user_kyc) ? $user_kyc->gender : '') == 'female')?"selected":"" }} value="female">{{__('Female')}}</option>
                            <option {{( (isset($user_kyc) ? $user_kyc->gender : '') == 'other')?"selected":"" }} value="other">{{__('Other')}}</option>
                        </select>
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_telegram', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="telegram" class="input-item-label">{{__('Telegram Username')}}  {!! required_mark('kyc_telegram') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_telegram', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" value = "{{ isset($user_kyc) ? $user_kyc->telegram : ''}}" id="telegram" name="telegram">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
        </div>{{-- .row --}}
        <h4 class="text-secondary mgt-0-5x">{{__('Your Address')}}</h4>
        <div class="row">
            @if(field_value('kyc_country', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="country" class="input-item-label">{{__('Country')}} {!! required_mark('kyc_country') !!}</label>
                    <div class="input-wrap">
                        <select {{ field_value('kyc_country', 'req' ) == '1' ? 'required ' : '' }}class="select-bordered select-block" name="country" id="country" data-dd-class="search-on">
                            <option value="">{{__('Select Country')}}</option>
                            @foreach($countries as $country)
                            <option {{ (isset($user_kyc) ? $user_kyc->country : '') == $country ? 'selected' : '' }} value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_state', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="state" class="input-item-label">{{__('State')}} {!! required_mark('kyc_state') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_state', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" value = "{{ isset($user_kyc) ? $user_kyc->state : ''}}" id="state" name="state">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_city', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="city" class="input-item-label">{{__('City')}} {!! required_mark('kyc_city') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_city', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" value = "{{ isset($user_kyc) ? $user_kyc->city : ''}}" id="city" name="city">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_zip', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="zip" class="input-item-label">{{__('Zip / Postal Code')}} {!! required_mark('kyc_zip') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_zip', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" value = "{{ isset($user_kyc) ? $user_kyc->zip : ''}}" id="zip" name="zip">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_address1', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="address_1" class="input-item-label">{{__('Address Line 1')}} {!! required_mark('kyc_address1') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_address1', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" value = "{{ isset($user_kyc) ? $user_kyc->address1 : ''}}" id="address_1" name="address_1">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
            @if(field_value('kyc_address2', 'show' ))
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="address_2" class="input-item-label">{{__('Address Line 2')}} {!! required_mark('kyc_address2') !!}</label>
                    <div class="input-wrap">
                        <input {{ field_value('kyc_address2', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text"  value = "{{ isset($user_kyc) ? $user_kyc->address2 : ''}}" id="address_2" name="address_2">
                    </div>
                </div>{{-- .input-item --}}
            </div>{{-- .col --}}
            @endif
        </div>{{-- .row --}}
    </div>{{-- .step-fields --}}
</div>
@if($has_docs)
<div class="form-step form-step2">
    <div class="form-step-head card-innr">
        <div class="step-head">
            <div class="step-number">{{ $step_02 }}</div>
            <div class="step-head-text">
                <h4>{{__('Document Upload')}}</h4>
                <p>{{__('To verify your identity, we ask you to upload high-quality scans or photos of your official identification documents issued by the government.')}}</p>
            </div>
        </div>
    </div>{{-- .step-head --}}
    <div class="form-step-fields card-innr">
        <div class="note note-plane note-light-alt note-md pdb-0-5x">
            <em class="fas fa-info-circle"></em>
            <p>{{__('In order to complete, please upload any of the following personal documents.')}}</p>
        </div>
        <div class="gaps-2x"></div>
        @if (!empty($support_docs))
        <ul class="document-list guttar-vr-10px">
            @foreach ($support_docs as $doc_item => $opt)
            @if ($opt)
            <li class="document-item">
                <div class="input-wrap">
                    @if ($doc_item=='passport' && ($opt))
                    <input class="document-type" type="radio" name="documentType" value="{{ $doc_item }}" id="docType-{{ $doc_item }}" data-title="{{ $title[$doc_item] }}" data-img="{{ asset('assets/images/vector-'.$doc_item.'.png') }}"{{ (isset($default_docs['doc']) && $default_docs['doc'] == $doc_item) ? ' checked' : '' }}>
                    <label for="docType-{{ $doc_item }}">
                        <div class="document-type-icon">
                            <img src="{{ asset('assets/images/icon-passport.png') }}" alt="">
                            <img src="{{ asset('assets/images/icon-passport-color.png') }}" alt="">
                        </div>
                        <span>{{ $title[$doc_item] }}</span>
                    </label>
                    @endif
                    @if ($doc_item=='nidcard' && ($opt))
                    <input class="document-type" type="radio" name="documentType" data-change=".doc-upload-d2" value="{{ $doc_item }}" id="docType-{{ $doc_item }}" data-title="{{ $title[$doc_item] }}" data-img="{{ asset('assets/images/vector-'.$doc_item.'.png') }}"{{ (isset($default_docs['doc']) && $default_docs['doc'] == $doc_item) ? ' checked' : '' }}>
                    <label for="docType-{{ $doc_item }}">
                        <div class="document-type-icon">
                            <img src="{{ asset('assets/images/icon-national-id.png') }}" alt="">
                            <img src="{{ asset('assets/images/icon-national-id-color.png') }}" alt="">
                        </div>
                        <span>{{ $title[$doc_item] }}</span>
                    </label>
                    @endif
                    @if ($doc_item=='driving' && ($opt))
                    <input class="document-type" type="radio" name="documentType"  value="{{ $doc_item }}" id="docType-{{ $doc_item }}" data-title="{{ $title[$doc_item] }}" data-img="{{ asset('assets/images/vector-'.$doc_item.'.png') }}"{{ (isset($default_docs['doc']) && $default_docs['doc'] == $doc_item) ? ' checked' : '' }}>
                    <label for="docType-{{ $doc_item }}">
                        <div class="document-type-icon">
                            <img src="{{ asset('assets/images/icon-license.png') }}" alt="">
                            <img src="{{ asset('assets/images/icon-license-color.png') }}" alt="">
                        </div>
                        <span>{{ $title[$doc_item] }}</span>
                    </label>
                    @endif
                </div>
            </li>
            @endif
            @endforeach
        </ul>
        @endif
        <div class="doc-upload-area">
            <p class="text-secondary font-bold">{{__('To avoid delays with verification process, please double-check to ensure the below requirements are fully met:')}}</p>
            <ul class="list-check">
                <li>{{__('Chosen credential must not be expired.')}}</li>
                <li>{{__('Document should be in good condition and clearly visible.')}}</li>
                <li>{{__('There is no light glare or reflections on the card.')}}</li>
                <li>{{__('File is at least 1 MB in size and has at least 300 dpi resolution.')}}</li>
            </ul>
            <div class="gaps-2x"></div>
            <div class="doc-upload doc-upload-d1">
                <h6 class="font-mid doc-type-title">{!! __('Upload Here Your :doctype Copy', ['doctype' => '<storng class="doc-type-name">'.$defaultDoc.'</storng>']) !!}</h6>
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <div class="upload-box">
                            <div class="upload-zone document_one">
                                <div class="dz-message" data-dz-message>
                                    <span class="dz-message-text">{{__('Drag and drop file')}}</span>
                                    <span class="dz-message-or">{{__('or')}}</span>
                                    <button type="button" class="btn btn-primary">{{__('Select')}}</button>
                                </div>
                            </div>
                            <input type="hidden" name="document_one" />
                        </div>
                    </div>
                    <div class="col-sm-4 d-none d-sm-block">
                        <div class="mx-md-4">
                            <img width="160" class="_image" src="{{ asset('assets/images/vector-'.$defaultImg.'.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="doc-upload doc-upload-d2{{ (isset($default_docs['doc']) && $default_docs['doc'] == 'nidcard') ? '' : ' hide' }}">
                <h6 class="font-mid">{{ __('Upload Here Your National ID Back Side') }}</h6>
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <div class="upload-box">
                            <div class="upload-zone document_two">
                                <div class="dz-message" data-dz-message>
                                    <span class="dz-message-text">{{__('Drag and drop file')}}</span>
                                    <span class="dz-message-or">{{__('or')}}</span>
                                    <button type="button" class="btn btn-primary">{{__('Select')}}</button>
                                </div>
                            </div>
                            <input type="hidden" name="document_two" />
                        </div>
                    </div>
                    <div class="col-sm-4 d-none d-sm-block">
                        <div class="mx-md-4">
                            <img width="160" src="{{  asset('assets/images/vector-id-back.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="sap sap-gap"></div>
            <div class="doc-upload doc-upload-d3">
                <h6 class="font-mid">{{__('Upload a selfie as a Photo Proof while holding document in your hand')}}</h6>
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <div class="upload-box">
                            <div class="upload-zone document_upload_hand">
                                <div class="dz-message" data-dz-message>
                                    <span class="dz-message-text">{{__('Drag and drop file')}}</span>
                                    <span class="dz-message-or">{{__('or')}}</span>
                                    <button type="button" class="btn btn-primary">{{__('Select')}}</button>
                                </div>
                            </div>
                            <input type="hidden" name="document_image_hand" />
                        </div>
                    </div>
                    <div class="col-sm-4 d-none d-sm-block">
                        <div class="mx-md-4">
                            <img width="160" src="{{ asset('assets/images/vector-hand.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>            
        </div>{{-- .doc-upload-area --}}
    </div>
</div>
@endif

@if($has_wallet)
<div class="form-step form-step3">
    <div class="form-step-head card-innr">
        <div class="step-head">
            <div class="step-number">{{ $step_03 }}</div>
            <div class="step-head-text">
                <h4>{{__('Your Paying Wallet')}}</h4>
                <p>{{__('Submit your wallet address that you are going to send funds')}}</p>
            </div>
        </div>
    </div>{{-- .step-head --}}
    <div class="form-step-fields card-innr">
        <div class="note note-plane note-light-alt note-md pdb-1x">
            <em class="fas fa-info-circle"></em>
            <p>{{__('DO NOT USE your exchange wallet address such as Kraken, Bitfinex, Bithumb, Binance etc.')}}</p>
        </div>
        @if($wallet_count > 1)
        <div class="row">
            <div class="col-md-6">
                <div class="input-item input-with-label">
                    <label for="swalllet" class="input-item-label">{{__('Select Wallet')}} {!! required_mark('kyc_wallet') !!}</label>
                    <div class="input-wrap">
                        <select {{ field_value('kyc_wallet', 'req' ) == '1' ? 'required ' : '' }}class="select-bordered select-bordered select-block" name="wallet_name" id="swalllet">
                            {!! $option !!}
                        </select>
                    </div>
                </div>
            </div>
        </div>{{-- .row --}}
        @else
        <input type="hidden" name="wallet_name" value="{{array_keys($wallets)[0]}}">
        @endif
        <div class="input-item input-with-label">
            <label for="token-address" class="input-item-label">{{ ($wallet_count ==1) ? __('Enter your :Name wallet address', ['name' => array_values($wallets)[0]]) : __('Enter your wallet address') }}{!! required_mark('kyc_wallet') !!}</label>
            <div class="input-wrap">
                <input {{ field_value('kyc_wallet', 'req' ) == '1' ? 'required ' : '' }}class="input-bordered" type="text" id="token-address" name="wallet_address" placeholder="{{__('Your personal wallet address')}}">
            </div>
            <span class="input-note">{{__('Note:')}} {{ get_setting('kyc_wallet_note') }}</span>
        </div>{{-- .input-item --}}
    </div>{{-- .step-fields --}}
</div>
@endif
<div class="form-step form-step-final">
    <div class="form-step-fields card-innr">
        @if(get_page('privacy', 'status') == 'active' || get_page('terms', 'status') == 'active')
        <div class="input-item">
            <input class="input-checkbox input-checkbox-md" id="term-condition" name="condition" type="checkbox" required="required" data-msg-required="{{ __("You should read our terms and policy.") }}">
            <label for="term-condition">{{__('I have read the')}} {!! get_page_link('terms', ['target'=>'_blank']) !!} {{ (get_page_link('terms') && get_page_link('policy') ? __('and') : '') }} {!! get_page_link('policy', ['target'=>'_blank']) !!}.</label>
        </div>
        @endif
        <div class="input-item">
            <input class="input-checkbox input-checkbox-md" id="info-currect" name="currect" type="checkbox" required="required" data-msg-required="{{ __("Confirm that all information is correct.") }}">
            <label for="info-currect">{{__('All the personal information I have entered is correct.')}}</label>
        </div>
        <div class="input-item">
            <input class="input-checkbox input-checkbox-md" id="certification" name="certification" type="checkbox" required="required" data-msg-required="{{ __("Certify that you are individual.") }}">
            <label for="certification">{{__("I certify that, I am registering to participate in the token distribution event(s) in the capacity of an individual (and beneficial owner) and not as an agent or representative of a third party corporate entity.")}}</label>
        </div>
        @if($has_wallet)
        <div class="input-item">
            <input class="input-checkbox input-checkbox-md" id="tokenKnow" name="tokenKnow" type="checkbox" required="required" data-msg-required="{{ __("Confirm that you understand.") }}">
            <label for="tokenKnow">{{__("I understand that, I can participate in the token distribution event(s) only with the wallet address that was entered in the application form.")}}</label>
        </div>
        @endif
        <div class="gaps-1x"></div>
        <button class="btn btn-primary" type="submit">{{__('Proceed to Verify')}}</button>
    </div>{{-- .step-fields --}}
</div>
<div class="hiddenFiles"></div>
