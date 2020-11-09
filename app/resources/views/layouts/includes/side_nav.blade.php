<?php
if (settings('side_nav') == 1) {
    $sideLogoClass = is_light_mode(' lf-white-transparent-side-nav', ' lf-dark-transparent-side-nav');
}
else {
    $sideLogoClass = is_light_mode(' lf-white-side-nav');
}
?>
<div class="lf-side-nav{{$sideLogoClass}}{{((!isset($activeSideNav) && in_array(settings('navigation_type'), [1,2])) || (isset($activeSideNav) && $activeSideNav)) ? (isset($fixedSideNav) ? ($fixedSideNav ? ' lf-side-nav-open' : '') : (settings('navigation_type') && settings('side_nav_fixed') ? ' lf-side-nav-open' : '')) : ''}}">
    <div class="lf-side-nav-handler lf-side-nav-controller"><i class="fa fa-arrow-circle-left"></i></div>
    <div class="text-center lf-side-nav-logo d-table w-100">
        <a href="{{route('home')}}"
           class="align-middle p-2 d-table-cell lf-logo{{is_light_mode(settings('company_logo_light')? '': ' lf-logo-inversed')}}">
            <!-- lf-logo-inversed -->
            <img src="{{ company_logo() }}"
                 class="img-fluid"
                 alt="">
        </a>
    </div>

    <div class="lf-side-nav-wrapper">
        <nav id="lf-side-nav">
            {{ get_nav('side-nav', 'side_nav') }}
        </nav>`
    </div>

</div>
