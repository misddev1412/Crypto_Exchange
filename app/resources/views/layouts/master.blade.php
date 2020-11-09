@include('layouts.includes.header')

@if(isset($headerLess) && $headerLess)
    <div class="centralize-wrapper">
        <div class="centralize-inner">
            <div class="centralize-content lf-no-header-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
@else
    @include('layouts.includes.top_header')

    @includeWhen((!isset($hideBreadcrumb) || !$hideBreadcrumb), 'layouts.includes.breadcrumb')
    <div class="lf-toggle-bg-content py-3">
        @yield('content')
    </div>

    @yield('extended-content')

    @include('layouts.includes.footer')

@endif
@include('layouts.includes.script')
