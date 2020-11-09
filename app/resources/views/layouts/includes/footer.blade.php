<footer class="footer lf-toggle-bg-footer main-footer">
    <div class="container">
        <div class="row">
            <!-- logo and about -->
            <div class="col-sm-6 col-lg-3">
                <div class="footer-widget">
                    <div class="footer-widget-logo mb-2 lf-logo{{is_light_mode(settings('company_logo_light')? '': ' lf-logo-inversed')}}">
                        <img src="{{ company_logo() }}"
                             alt="logo" class="img-fluid">
                    </div>

                    @if(!empty(settings('footer_about')))
                    <div class="footer-widget-content">
                        <p>
                            {{ settings('footer_about') }}
                        </p>
                    </div>
                    @endif
                    @if(!empty(settings('footer_email')) || !empty(settings('footer_phone_number')) || !empty(settings('footer_address')))
                    <ul class="footer-widget-contact-info">
                        @if(!empty(settings('footer_email')))
                        <li>
                            <i class="fa fa-envelope-open"></i><a href="mailto:{{ settings('footer_email') }}">{{ settings('footer_email') }}</a>
                        </li>
                        @endif

                        @if(!empty(settings('footer_address')))
                        <li>
                            <i class="fa fa-map-marker"></i>{{ settings('footer_address') }}
                        </li>
                        @endif
                        @if(!empty(settings('footer_phone_number')))
                        <li>
                            <i class="fa fa-phone"></i>{{ settings('footer_phone_number') }}
                        </li>
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
            <!-- menu -->
            @for($i = 1; $i <= 3; $i++)
                @if(!empty(settings('footer_menu_title_'.$i)) || !empty(settings('footer_widget_two_nav')))
                    <div class="col-sm-6 col-lg-3">
                        <div class="footer-widget">
                        @if(!empty(settings('footer_menu_title_'.$i)))
                            <h3 class="footer-widget-title">
                                {{ settings('footer_menu_title_'.$i) }}
                            </h3>
                        @endif
                        @if(!empty(settings('footer_menu_'.$i)))
                                {{ get_nav(settings('footer_menu_'.$i), 'footer_nav') }}
                            @endif
                        </div>
                    </div>
                @endif
            @endfor
        </div>
    </div>
</footer>
<footer class="footer lf-toggle-bg-copyright-footer py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="font-size-12 text-center text-sm-left text-muted">
                    @if(!empty(settings('footer_copyright_text')))
                    {{ view_html(settings('footer_copyright_text')) }}
                    @else
                    &copy; 2019-{{ date('Y') }} - <a href="{{ route('home') }}">{{ company_name() }}</a> - All Right Reserved.
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <ul class="footer-social-media-links text-center text-sm-right">
                        <li>
                            <a class="link-color-fb" target="_blank" href="javascript:;">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a class="link-color-twitter" target="_blank" href="javascript:;">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a class="link-color-linkedin" target="_blank" href="javascript:;">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
</footer>
