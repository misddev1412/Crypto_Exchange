</div>
@if(!isset($headerLess) || !$headerLess)
    @if((!isset($activeSideNav) && in_array(settings('navigation_type'), [1,2])) || (isset($activeSideNav) && $activeSideNav))
        @include('layouts.includes.side_nav')
    @endif
@endif
<!-- Flash Message -->
@include('errors.flash_message')

@include('layouts.includes.notice')

<!-- REQUIRED SCRIPTS -->
@yield('script-top')
<!-- jQuery -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
@if(!isset($headerLess) || !$headerLess)
    <script src="{{ asset('plugins/jQueryUI/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('plugins/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('plugins/slicknav/slicknav.min.js') }}"></script>
@endif
@yield('extra-script')
<script src="{{ asset('plugins/flash_message/flash.message.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

@yield('script')

</body>
</html>
