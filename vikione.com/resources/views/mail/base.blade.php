@component('mail::message')
@component('mail::panel')
{{-- Greeting --}}
@if (! empty($greeting))
    # {!! $greeting !!}
@else
    @if ($level === 'error')
    # @lang('Whoops!')
    @else
    # @lang('Hello!')
    @endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}
@endforeach

@isset($__message)
{!! $__message !!}
@endisset

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{!! $line !!}
@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{!! auto_p($salutation, true) !!}
@else
@lang("\n\n\nBest Regards"), <br>{{ site_info('name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endcomponent
@endisset
@endcomponent
@endcomponent
