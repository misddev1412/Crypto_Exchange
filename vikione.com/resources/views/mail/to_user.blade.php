@component('mail::message')
@component('mail::panel')
{{-- Greeting --}}
@if (! empty($greeting))
    # {!! replace_shortcode(replace_with($greeting, '[[user_name]]', $user->name)) !!}
@else
    # {!! replace_shortcode(replace_with($template->greeting, '[[user_name]]', $user->name)) !!}
@endif

{{-- Message  --}}
@if(! empty($message))
{!! auto_p(replace_shortcode(replace_with($message->text, '[[user_name]]', $user->name))) !!}
@endif

{{-- Salutation --}}
@if (! empty($salutation))
{!! "\n\n----".auto_p($salutation) !!}
@else
@lang("\n\n----\nBest Regards"), <br>{{ site_info('name') }}
@endif


@endcomponent
@endcomponent
