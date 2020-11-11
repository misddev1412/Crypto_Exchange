@extends('layouts.admin')
@section('title', 'Languages Management')

@section('content')
<div class="page-content">
    <div class="container">
        @include('vendor.notice')
        <div class="row">
            <div class="main-content col-lg-9">
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">Site Languages</h4>
                            <p class="pt-3">Enable or disable the languages of application. You can add your language and translate that on your way.</p>
                        </div>
                        <div class="gaps-2x"></div>
                        <div class="card-text">
                            <table class="data-table languages-list table-lg">
                                <thead>
                                    <tr class="data-item data-head">
                                        <td class="data-col">Language</td>
                                        <td class="data-col">Label</td>
                                        <td class="data-col">Short</td>
                                        <td class="data-col">Status</td>
                                        <td class="data-col">Last Generate</td>
                                        <th class="data-col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($languages as $lang)
                                    <tr class="data-item">
                                        <td class="data-col">
                                            <span>{{ $lang->name }} <small>[{{ strtoupper($lang->code) }}]</small></span>
                                        </td>
                                        <td class="data-col">
                                            <span>{{ $lang->label }}</span>
                                        </td>
                                        <td class="data-col">
                                            <span>{{ $lang->short }}</span>
                                        </td>
                                        <td class="data-col">
                                            @if($lang->status)
                                                <span class="dt-status-md badge badge-dim badge-success badge-md d-none d-sm-inline-block">Actived</span>
                                                <span class="dt-status-sm badge badge-sq badge-dim badge-success badge-md d-sm-none">A</span>
                                            @else
                                                <span class="dt-status-md badge badge-dim badge-danger badge-md d-none d-sm-inline-block">Disable</span>
                                                <span class="dt-status-sm badge badge-sq badge-dim badge-danger badge-md d-sm-none">D</span>
                                            @endif
                                        </td>                                        
                                        <td class="data-col">
                                            <span class="sub">
                                                {{ gmdate('M d, Y h:iA', gws('lang_last_generate_'.$lang->code)) }}
                                                @if(gws('lang_last_generate_'.$lang->code) <= gws('lang_last_update_'.$lang->code))
                                                 <em class="fas fa-info-circle fs-11 text-warning" data-toggle="tooltip" data-placement="right" title="Seems translation has been updated, Please generate your language again."></em>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="data-col text-right">
                                            <div class="relative d-inline-block lang-action-list">
                                                <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                                <div class="toggle-class dropdown-content dropdown-content-top-left">
                                                    <ul class="dropdown-list">
                                                        <li><a href="{{ route('admin.lang.translate', $lang->code) }}" >Translation</a> </li>
                                                        <li><a href="javascript:void(0)" class="lang-action" data-lang="{{ $lang->code }}" data-confirm="yes" data-actions="generate">Generate</a> </li>
                                                        <li><a href="javascript:void(0)" class="lang-action" data-lang="{{ $lang->code }}" data-modal="edit" data-actions="update">Update</a></li>
                                                        @if($lang->code!='en')
                                                            @if($lang->status)
                                                            <li><a href="javascript:void(0)" class="lang-action" data-lang="{{ $lang->code }}" data-confirm="yes" data-actions="disable">Disable</a> </li>
                                                            @else
                                                            <li><a href="javascript:void(0)" class="lang-action" data-lang="{{ $lang->code }}" data-confirm="yes" data-actions="enable">Enable</a> </li>
                                                            @endif
                                                            <li><a href="javascript:void(0)" class="lang-action" data-lang="{{ $lang->code }}" data-confirm="yes" data-actions="delete">Delete</a> </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
            </div>{{-- .col --}}
            <div class="col-lg-3 aside sidebar-right">
                <div class="card card-navs">
                    <div class="card-innr">
                        <div class="card-head d-none d-lg-block">
                            <h6 class="card-title card-title-md">Language</h6>
                        </div>
                        <ul class="sidebar-nav">
                            <li><a href="#" data-toggle="modal" data-target="#lang-add"><em class="ikon ikon-docs"></em> Add New</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#lang-settings"><em class="ikon ikon-settings"></em> Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>{{-- .row --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

@endsection

@section('modals')
{{-- Settings Modal --}}
<div class="modal fade" id="lang-settings" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="popup-body">
                <h3 class="popup-title">{{ __('Language Settings') }}</h3>
                <form class="validate-modern lang-form-submit" action="{{ route('admin.ajax.lang.action') }}" method="POST" id="lang-settings-update">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Language Name</label>
                                <div class="input-wrap">
                                    <select class="select select-block select-bordered" name="languages_show_as">
                                        <option {{ gws('languages_show_as') == 'code' ? 'selected ' : '' }}value="code">Use Short Name</option>
                                        <option {{ gws('languages_show_as') == 'label' ? 'selected ' : '' }}value="label">Use Label Name</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Show Switcher</label>
                                <div class="input-wrap input-wrap-switch">
                                    <input class="input-switch" name="languages_switcher" type="checkbox"{{ gws('languages_switcher') == 1 ? ' checked' : '' }} id="lang-switcher-enable">
                                    <label for="lang-switcher-enable">Enable</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">    
                            @csrf
                            <input type="hidden" name="actions" value="settings">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>

{{-- Add Lang Modal --}}
<div class="modal fade" id="lang-add" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <div class="popup-body">
                <h3 class="popup-title">{{ __('Add New Language') }}</h3>
                <form class="validate-modern lang-form-submit _reload" action="{{ route('admin.ajax.lang.action') }}" method="POST" id="lang-add-new">
                    <div class="row">
                        <div class="col-sm-6">  
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Language Name</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="name" placeholder="Eg. English" required>
                                </div>
                                <span class="input-note">Name of language to indentify.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Code Name</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="code" required>
                                </div>
                                <span class="input-note">Eg. 'en' and code name should be unique.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Language Label</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="label" required>
                                </div>
                                <span class="input-note">Eg. 'English' and label must be unique.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Short Name</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="short" required>
                                </div>
                                <span class="input-note">Eg. 'EN' or 'ENG' and name must uppercase.</span>
                            </div>
                        </div>
                        <div class="col-12">    
                            @csrf
                            <input type="hidden" name="actions" value="language">
                            <button type="submit" class="btn btn-primary">{{ __('Add Language') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
@endsection
@push('footer')
<script type="text/javascript">
    (function($){
        var $languages = $(".lang-form-submit");
        if ($languages.length > 0) {
            var clear = ($(this).find('input[name=actions]').val() == 'language') ? true : false;
            ajax_form_submit($languages, clear);
        }

        var lang_action = ".lang-action", lang_action_url = "{{ route('admin.ajax.lang.action') }}", $ajmod = $('#ajax-modal');
        $(document).on('click', lang_action, function(e){
            e.preventDefault();
            var $this = $(this), $lnap = $this.parents('.lang-action-list'), is_confirm = $this.data('confirm');
            $lnap.find('.toggle-tigger').add('.toggle-class').removeClass('active');
            if(is_confirm==='yes') {
                var action = $this.data('actions'), swal_btx = (action=='delete') ? 'Delete' : ((action=='generate') ? 'Regenerate' : 'Yes'), 
                swal_bcn = (action=='delete') ? 'danger' : '', swal_text = "Please confirm if you want to "+ action +" the language?";
                swal({
                    title: "Are you sure?",
                    icon: (action=='delete'||action=='generate') ? 'warning' : 'info',
                    text: (action=='delete') ? swal_text+" You can not undo once you deleted it." : ((action=='generate') ? swal_text+" Once you regenerate the language file, it will overwrite your old language file." : swal_text),
                    buttons: {
                        cancel:{text:'No', visible:true}, 
                        confirm:{text:swal_btx, className:swal_bcn}
                    },
                    dangerMode: (action=='delete') ? true : false
                })
                .then(function(data) {
                    if(data) { post_submit(lang_action_url, $this.data(), $ajmod); }
                });
            } else {
                if($this.data()) { post_submit(lang_action_url, $this.data(), $ajmod); }
            }
        });
    })(jQuery);
</script>
@endpush