@extends('layouts.master',['activeSideNav' => active_side_nav()])

@section('title', $title)

@section('content')
    <div class="container my-5 lf-language-palet">
        <div class="row">
            <div class="col-md-12">
                <div class="bg-primary py-3 px-3">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle"
                                data-toggle="dropdown"
                                aria-expanded="false">
                            @{{ selectedLanguage | uppercase }}
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-left p-0 border-radius-0" role="menu">
                            <a :class="selectedLanguage == language ? 'dropdown-item active px-3 py-2' : 'dropdown-item px-3 py-2' "
                               v-for="language in languages" href="javascript:"
                               @click="changeLanguage(language)">
                                @{{ language | uppercase }}
                            </a>
                        </div>
                    </div>

                    <div class="float-right">
                        <button @click="add"
                                class="btn btn-success">{{ __('Add New') }}
                        </button>
                        <button @click="sync"
                                class="btn btn-danger">{{ __('Sync') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card lf-toggle-border-color lf-toggle-bg-card">
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend lf-toggle-bg-input lf-toggle-border-color">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control lf-toggle-bg-input lf-toggle-border-color" v-model="searchPhrase"
                                   @keyup="searchTranslations" placeholder="Search">
                        </div>

                        <div id="setting-scroll" class="cm-mt-15">
                            <div class="list-group list-group-flush" v-if="Object.keys(filteredTranslations).length">
                                <div v-for="(value, key) in filteredTranslations"
                                     @click="selectedKey = key;addNewKey = false"
                                     :class="['list-group-item','lf-toggle-bg-card','lf-toggle-text-color','lf-toggle-border-color', 'list-group-item-action','lf-cursor-pointer','px-0', 'py-3', {'list-group-item-danger': !value}]">
                                    <div class="d-flex w-100 justify-content-between">
                                        <strong class="mb-1" v-html="highlight(key)"></strong>
                                    </div>
                                    <small class="text-muted" v-html="highlight(value)"></small>
                                </div>
                            </div>
                            <div class="text-center" v-else>
                                <span>{{ __("No translation match with your search key.") }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div v-if="addNewKey">
                    <input type="text" class="form-control lf-toggle-border-color lf-toggle-bg-input" placeholder="{{ __('New Key...') }}"
                           v-model="newKey">
                    <span class="help-block text-red">@{{ newKeyErrorMsg }}</span>

                    <textarea name="" rows="10" class="form-control my-3 lf-toggle-border-color lf-toggle-bg-input"
                              v-model="newKeyValue"
                              placeholder="Translate..."></textarea>
                    <span class="help-block text-red">@{{ newKeyValueErrorMsg }}</span>

                    <div class="cm-mt-15">
                        <button class="btn btn-info"
                                @click="saveNewKey">{{ __('Save') }}
                        </button>
                    </div>
                </div>
                <div v-else>
                    <div v-if="selectedKey">
                        <div class="card lf-toggle-border-color lf-toggle-bg-card">
                            <div class="card-body">
                                <p class="mb-4" v-html="highlight(selectedKey)"></p>
                                <textarea name="" rows="10" class="form-control mb-4 lf-toggle-bg-input lf-toggle-border-color"
                                          v-model="translations[selectedLanguage][selectedKey]"
                                          @keyup="changeTranslations(selectedKey, $event)"
                                          placeholder="Translate..."></textarea>

                                <div class="cm-mt-15">
                                    <button class="btn btn-info btn-sm"
                                            @click="save">{{ __('Save') }}
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                            @click="remove">{{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-muted text-center mt-5" v-else>
                        {{ __('Select a key from the list to the left') }}
                    </h6>
                </div>
            </div>
        </div>

        <confirmation-dialog
            :messages="{
                title: '{{ __("Are you sure you want to delete this key?") }}',
                cancelButtonText: '{{ __("Cancel") }}',
                confirmButtonText: '{{ __("Confirm") }}'
                }"

            v-if='confirmDialog' @confirm='confirmDelete'
            @cancel="cancelDelete"></confirmation-dialog>
    </div>

@endsection
@section('style')
    <style>

        .mCSB_container_wrapper > .mCSB_container {
            padding-right: 0 !important;
        }

        .mCSB_container_wrapper {
            margin-right: 20px !important;
        }

    </style>
@endsection
@section('script')
    <script>
        "use strict";

        const data = {
            languages: @json(language_short_code_list()),
            selectedLanguage: '{{ app()->getLocale() }}'.toLowerCase(),
            newKeyError: "{{ __('This new key field is required') }}",
            newKeyValueError: "{{ __('This new key value field is required') }}",
            routes: {
                getTranslations: '{{ route("languages.translations") }}',
                update: '{{ route("languages.update.settings") }}',
                sync: '{{ route("languages.sync") }}'
            }
        };
        $(window).on("load", function () {
            $("#setting-scroll").mCustomScrollbar({
                setHeight: "400px",
                axis: "yx",
                theme: "dark",
                scrollInertia: 200
            });
        });
    </script>
    <script src="{{ asset('js/language.js') }}?t={{ time() }}"></script>
@endsection
