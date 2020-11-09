<div class="text-center mb-md-3 mb-2">
    <a href="{{route('home')}}"
       class="lf-logo{{is_light_mode(settings('company_logo_light')? '': ' lf-logo-inversed')}}">
        <img src="{{ company_logo() }}"
             class="img-fluid"
             alt="">
    </a>
</div>
@isset($pageTitle)
<h4 class="text-center lf-toggle-text-color mb-4">{{ $pageTitle }}</h4>
@endisset
<div class="lf-no-header-inner border lf-toggle-border-color lf-toggle-bg-card">
    <div class="m-lg-4">
        {{ $slot }}
    </div>
</div>
