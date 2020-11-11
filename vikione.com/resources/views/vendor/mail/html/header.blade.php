<tr>
    <td class="header">
        <a href="{{ $url }}">
            {!! $slot !!}
        </a>
        @if(get_setting('site_description') != NULL)
        <p>{{ get_setting('site_description') }}</p>
        @endif
    </td>
</tr>
