<tr>
    <td>
        <table class="footer" align="center" width="620" cellpadding="0" cellspacing="0">
            <tr>
                <td class="content-cell" align="center">
                    {{ Illuminate\Mail\Markdown::parse($slot) }}
                    @php
                    $social_links = get_setting('site_social_links', null);
                    @endphp
                    @if(! empty($social_links))
                        <ul class="social">
                        @foreach(json_decode( $social_links ) as $social => $link)
                        @if(! empty($link))
                            @if($social!='onsite' && $social!='onlogin')
                            <li><a href="{{ $link }}"><img src="{{asset('images/socials/'.$social.'.png')}}" alt="{{ ucfirst($social) }}"></a></li>
                            @endif
                        @endif
                        @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
        </table>
    </td>
</tr>
