@extends('layouts.user')
@section('title', ucfirst($page->title))
@php
    ($has_sidebar = true)
@endphp

@section('content')
<div class="content-area content-area-mh card user-account-pages page-{{ $page->slug }}" style="position: relative;">
    <div class="card-innr" id="card-wrapper">
        <div class="card-head">
            <h2 class="card-title card-title-lg">{{ __(replace_shortcode($page->title)) }}</h2>
            @if($page->meta_description!=null)
                <p class="large">{{ replace_shortcode($page->meta_description) }}</p>
            @endif
        </div>
        @if(!empty($page->description))
            <div class="card-text">
                {!! replace_shortcode(auto_p($page->description)) !!}
            </div>
        @endif

        <div class="gaps-1x"></div>
        <div class="referral-form">
            <h4 class="card-title card-title-sm">{{ __('Referral URL') }}</h4>
            <div class="copy-wrap mgb-1-5x mgt-1-5x">
                <span class="copy-feedback"></span>
                <em class="copy-icon fas fa-link"></em>
                <input type="text" class="copy-address"
                    value="{{ route('public.referral').'?ref='.set_id(auth()->id()) }}"
                    disabled>
                <button class="copy-trigger copy-clipboard"
                    data-clipboard-text="{{ route('public.referral').'?ref='.set_id(auth()->id()) }}"><em
                        class="ti ti-files"></em></button>
            </div>
            <p class="text-light mgmt-1x">
                <em><small>{{ __('Use above link to refer your friend and get referral bonus.') }}</small></em>
            </p>
        </div>
        <div class="sap sap-gap"></div>
        <div class="card-head">
            <h4 class="card-title card-title-sm">{{ __('Referral Lists') }}</h4>
        </div>
        {{-- <table class="data-table dt-init refferal-table" data-items="10">
            <thead>
                <tr class="data-item data-head">
                    <th class="data-col refferal-name"><span>{{ __('User Name') }}</span></th>
                    <th class="data-col refferal-tokens"><span>{{ __('Earn Token') }}</span></th>
                    <th class="data-col refferal-date"><span>{{ __('Register Date') }}</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reffered as $refer)
                    <tr class="data-item">
                        <td class="data-col refferal-name">{{ $refer->name }}</td>
                        <td class="data-col refferal-tokens">
                            {{ (referral_bonus($refer->id)) ? referral_bonus($refer->id).' '.token_symbol() : __('~') }}
                        </td>
                        <td class="data-col refferal-date">{{ _date($refer->created_at) }}</td>
                    </tr>
                @empty
                    <tr class="data-item">
                        <td class="data-col">{{ __('No one join yet!') }}</td>
                        <td class="data-col"></td>
                        <td class="data-col"></td>
                    </tr>
                @endforelse
            </tbody>
        </table> --}}
        {{-- <button id="zoom_out">zoom_out</button> --}}
        {{-- <canvas id="canvas"></canvas> --}}
        <div id="jstree">
            <!-- in this example the tree is populated from inline HTML -->
            {!! $treeRef !!}
          </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    $('#jstree').jstree();
</script>
@endsection
