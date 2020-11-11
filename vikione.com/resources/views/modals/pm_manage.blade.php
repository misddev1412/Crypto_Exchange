<div class="modal fade" id="manage" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="close"><em class="ti ti-close"></em></a>
            <div class="popup-body-full pdt-1x pdt-sm-3x">
                <div class="popup-body-innr pdt-2-5x pdb-1x">
                    <h3 class="popup-title ucap">Manage currencies</h3>
                    <p>You can manage currency what you want to use in payment system. you can use one or multiple currency from below option.</p>
                </div>

                <form action="{{ route('admin.ajax.payments.update') }}" method="post" id="pm_manage_rate_form">
                    @csrf
                    <input type="hidden" name="req_type" value="currency_manage">
                    <div class="popup-body-innr pdt-0-5x pdb-1x">
                        <div class="row guttar-20px align-items-center">
                            <div class="col-sm">
                                <div class="row align-items-center">
                                    <div class="col-sm w-sm-38">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label cap">Based Currency</label>
                                            <select class="select select-block select-bordered base-currency" name="base_currency">
                                                @foreach($gateway as $gt=>$val)
                                                @if(get_setting('pmc_active_'.$gt) == 1)
                                                <option {{ get_setting('site_base_currency') == strtoupper($gt) ? 'selected' : '' }} value="{{ strtoupper($gt) }}">{{ $val .' ('.strtoupper($gt).')'}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <span class="input-note">Calculate based on this Currency</span>
                                            <span class="input-note input-note-danger">Important: If change the based currency after any transaction made, it may occurred in calculation. must update exchange rate accordingly after currency change.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row guttar-20px">
                            <div class="col-sm">
                                <div class="input-item input-with-label w-sm-76">
                                    <label class="input-item-label cap">Currency Exchange</label>
                                    <select class="select select-block select-bordered" id="exchange_method" name="exchange_method">
                                        <option {{ get_setting('pm_exchange_method') == 'manual' ? 'selected' : '' }} value="manual">Manual / Own price</option>
                                        <option {{ get_setting('pm_exchange_method') == 'automatic' ? 'selected' : '' }} value="automatic">Automatic via CryptoCompare</option>
                                    </select>
                                    <span class="input-note">Set how exchange rate calculate</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sap"></div>

                    <div class="popup-body-innr pdt-2-5x">
                        <div class="{{ get_setting('pm_exchange_method') == 'manual' ? 'd-block' : 'd-none' }}" id="manual_rate" >
                            <label class="input-item-label cap pdb-0-5x">Set Manual Exchange Rate</label>
                            <div class="row">
                                @foreach($gateway as $g=>$v)
                                @php 
                                $current = (strtolower(get_setting('site_base_currency')) == $g) ? ' readonly' : '';

                                $current_value = (strtolower(get_setting('site_base_currency')) == $g) ? 1 : get_setting('pmc_rate_'.$g);
                                @endphp
                                @if(get_setting('pmc_active_'.$g) == 1)
                                <div class="col-md-3 col-6 pdb-2x">
                                    <div class="relative">
                                        <input class="input-bordered currency-rate currency-{{ $g }}" type="text" name="pmc_rate_{{ $g }}" value="{{ $current_value }}" required{{ $current }}>
                                        <span class="input-hint">{{ strtoupper($g) }}</span>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="input-item input-with-label {{ get_setting('pm_exchange_method') == 'automatic' ? 'd-block' : 'd-none' }}" id="automatic_rate">
                            <label class="input-item-label cap pdb-0-5x">Automatic Exchange Rate (read only)</label>
                            <div class="row">
                                @foreach($gateway as $g=>$v)
                                @if(get_setting('pmc_active_'.$g) == 1)
                                <div class="col-md-3 col-6 pdb-2x">
                                    <div class="relative">
                                        <input class="input-bordered" value="{{ get_setting('pmc_auto_rate_'.$g) }}" readonly="readonly">
                                        <span class="input-hint">{{ strtoupper($g) }}</span>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                <div class="col-12">
                                    <div class="input-wrap">
                                    <label for="rate_time" class="input-item-label cap">Select automatic update rate time</label>
                                    <select name="automatic_rate_time" id="rate_time" class="select select-bordered select-block">
                                        <option {{ get_setting('pm_automatic_rate_time') == 15 ? 'selected' : '' }} value="15">15 minute</option>
                                        <option {{ get_setting('pm_automatic_rate_time') == 30 ? 'selected' : '' }} value="30">30 minute</option>
                                        <option {{ get_setting('pm_automatic_rate_time') == 45 ? 'selected' : '' }} value="45">45 minute</option>
                                        <option {{ get_setting('pm_automatic_rate_time') == 60 ? 'selected' : '' }} value="60">1 hour</option>
                                        <option {{ get_setting('pm_automatic_rate_time') == 120 ? 'selected' : '' }} value="120">2 hour</option>
                                    </select>
                                    </div>
                                    <span class="input-note">Last check : {{ _date(get_setting('pm_exchange_auto_lastcheck')) }}</strong>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="sap"></div>

                    <div class="popup-footer">
                        <button class="btn btn-md btn-primary save-disabled" type="submit" disabled>Update</button>
                    </div>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>

<script type="text/javascript">
    (function($) {
        var $exchange_method = $('#exchange_method');
        if ($exchange_method.length > 0) {
            $(document).on('change', '#exchange_method', function(){
                $this = $(this);
                if($this.val() == 'automatic'){
                    $('#automatic_rate').removeClass('d-none').addClass('d-block');
                    $('#manual_rate').removeClass('d-block').addClass('d-none');
                }else{
                    $('#automatic_rate').removeClass('d-block').addClass('d-none');
                    $('#manual_rate').removeClass('d-none').addClass('d-block');
                }
            });
        }


        var $pm_manage = $('form#pm_manage_rate_form'),
        $submit_btn = $pm_manage.find('button[type=submit]'),
        is_changed = false,
        input_changes_ = '.input-switch, .select, .input-checkbox, .input-bordered';

        if ($pm_manage.length > 0) {
            ajax_form_submit($pm_manage, true);
        }
        var $currency_input = $('.currency-rate'), $currency_base = $('.base-currency');
        $currency_base.on('change', function(){
            var _val = $(this).val();
            $currency_input.val('').removeAttr('readonly');
            $('.currency-'+_val.toLowerCase()).val(1).attr('readonly', 'readonly');
        });
        $pm_manage.find(input_changes_).on('keyup change',
            function() {
                is_changed = true;
                btn_actived($submit_btn);
            });
        $submit_btn.on('click', function() {
            is_changed = false;
        });
        $('.modal-close').on('click', function(e) {
            e.preventDefault();
            if (is_changed === true) {
                if (confirm('you made some changes, \ndo you realy close without save?')) {
                    bs_modal_hide($(this));
                    is_changed = false;
                }
            } else {
                bs_modal_hide($(this));
            }
        });
    })(jQuery);
</script>
