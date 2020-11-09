<div>
    {{ Form::open(['route'=>['kyc-verifications.store'], 'class'=>'validator', 'enctype'=>'multipart/form-data']) }}
    <transition name="fade"
                mode="out-in">
        {{--step 1 --}}
        <section key="1"
                 v-if="step == 1">
            <div class="mb-3">
                <h3 class="margin-bottom font-weight-normal text-center">{{ __('Select ID Type') }}</h3>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="lf-kyc-card card text-center clickable lf-toggle-border-card lf-toggle-bg-card"
                         @click="nextStep('{{ KYC_TYPE_PASSPORT }}')">
                        <div class="card-body py-5 text-muted">
                            <i class="fa fa-address-book-o fa-5x fa-align-center"></i>
                        </div>
                        <p class="card-footer mb-0 py-3 font-size-14">
                            {{ __('PASSPORT') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="lf-kyc-card card text-center clickable lf-toggle-border-card lf-toggle-bg-card"
                         @click="nextStep('{{ KYC_TYPE_NID }}')">
                        <div class="card-body py-5 text-muted">
                            <i class="fa fa-id-card-o fa-5x fa-align-center"></i>
                        </div>
                        <p class="card-footer mb-0 py-3 font-size-14">
                            {{ __('NID CARD') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="lf-kyc-card card text-center clickable lf-toggle-border-card lf-toggle-bg-card"
                         @click="nextStep('{{ KYC_TYPE_DRIVING_LICENSE }}')">
                        <div class="card-body py-5 text-muted">
                            <i class="fa fa-truck fa-5x fa-align-center"></i>
                        </div>
                        <p class="card-footer mb-0 py-3 font-size-14">
                            {{ __("DRIVING LICENSE") }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{--step 2--}}
        <section key="2"
                 v-if="step == 2"
                 class="text-center">
            <input type="hidden"
                   name="id_type"
                   v-model="idType">

            <h3 class="font-weight-normal text-center margin-bottom">{{ __('Upload ID') }}</h3>
            <div class="row">
                <div class="col-sm-8 offset-md-2">
                    <div class="row">
                        <div :class="idType == '{{ KYC_TYPE_PASSPORT }}' ? 'col-sm-12' : 'col-sm-6' ">
                            <div class="form-group">
                                <div class="fileinput fileinput-new"
                                     data-provides="fileinput">
                                    <div class="fileinput-new img-thumbnail p-3 lf-toggle-bg-card lf-toggle-border-card">
                                        <div class="d-flex">
                                            <i class="fa fa-address-book-o fa-5x m-auto"></i>
                                        </div>
                                    </div>
                                    <div
                                        class="fileinput-preview fileinput-exists img-thumbnail lf-w-200px lf-h-200px lf-toggle-bg-card lf-toggle-border-card"></div>
                                    <div>
                                                <span class="btn btn-dark btn-file btn-block lf-cursor-pointer">
                                                <span class="fileinput-new">{{ __("Upload") }} <span
                                                        v-if="idType != '{{ KYC_TYPE_PASSPORT }}'">{{ __('Front') }}</span></span>
                                                <span class="fileinput-exists">{{ __('Change') }}</span>
                                                {{ Form::file('id_card_front', ['class' => form_validation($errors, 'id_card_front'),
                                                'id' => 'id_card_front']) }}
                                            </span>
                                        <a href="javascript:"
                                           class="btn btn-danger fileinput-exists btn-block"
                                           data-dismiss="fileinput">{{ __('Remove') }}</a>
                                    </div>
                                </div>
                                <p class="help-block text-muted">{{ __('Upload scan copy of ID card front less than or equal 2MB.') }}</p>
                                <span class="invalid-feedback">{{ $errors->first('id_card_front') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6"
                             v-if="idType != '{{ KYC_TYPE_PASSPORT }}'">
                            <div class="form-group">
                                <div class="fileinput fileinput-new"
                                     data-provides="fileinput">
                                    <div class="fileinput-new img-thumbnail p-3 lf-toggle-bg-card lf-toggle-border-card">
                                        <div class="d-flex">
                                            <i class="fa fa-address-book-o fa-5x m-auto"></i>
                                        </div>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists img-thumbnail lf-w-200px lf-h-200px"></div>
                                    <div>
                                                <span class="btn btn-dark btn-file btn-block lf-cursor-pointer">
                                                <span class="fileinput-new">{{ __("Upload") }} <span
                                                        v-if="idType != '{{ KYC_TYPE_PASSPORT }}'">{{ __('Back') }}</span></span>
                                                <span class="fileinput-exists">{{ __('Change') }}</span>
                                                {{ Form::file('id_card_back', ['class' => form_validation($errors, 'id_card_back'),
                                                'id' => 'id_card_back']) }}
                                            </span>
                                        <a href="javascript:"
                                           class="btn btn-danger fileinput-exists btn-block"
                                           data-dismiss="fileinput">{{ __('Remove') }}</a>
                                    </div>
                                </div>
                                <p class="help-block text-muted">{{ __('Upload scan copy of ID card back less than or equal 2MB.') }}</p>

                                <span class="invalid-feedback">{{ $errors->first('id_card_back') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit"
                    class="btn btn-sm-block btn-info">{{ __('Submit ID') }}</button>
            <a class="btn btn-sm-block btn-danger"
               @click.prevent="previousStep">{{ __('Back') }}</a>
        </section>
    </transition>
    {{ Form::close() }}
</div>
