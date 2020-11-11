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
{!! "\n\n----".auto_p($salutation) !!}
@else
@lang("\n\n----\nBest Regards"), <br>{{ site_info('name') }}
@endif


@endcomponent
@endcomponent
