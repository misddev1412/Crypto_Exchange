@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            @if(! empty(site_logo('mail')))
            <img height="50" src="{{ site_logo('mail') }}" alt="{{ site_info('name') }}">
            @else
            {{ config('settings.site_name', config('app.name', 'TokenLite')) }}
            @endif
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Copyright &copy; {{ date('Y') }} {{ config('settings.site_name', config('app.name', 'TokenLite')) }}. {{ get_setting('site_copyright', 'All Rights Reserved.') }}
        @endcomponent
    @endslot
@endcomponent
