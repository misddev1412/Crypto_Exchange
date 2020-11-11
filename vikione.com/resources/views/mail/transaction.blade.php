@component('mail::message')
@component('mail::panel', ['status' => str_replace(['pending', 'approved', 'onhold', 'rejected', 'canceled'], ['warning', 'success', 'info', 'danger','danger'], $transaction->status)])
{{-- Greeting --}}
@if (! empty($greeting))
# {!! $greeting !!}
@else
# {!! replace_shortcode(replace_with($template->greeting, '[[user_name]]', $user->name)) !!}
@endif

{{-- Message  --}}
<div class="text-left">
	@if(! empty($template->message))
	{!! str_replace('<br><br>', '<br>', $template->message) !!}
	@endif
</div>
{{-- Salutation --}}
@if (! empty($salutation))
{!! "\n----\n".auto_p($salutation) !!}
@else
{{-- @lang("\n----\nBest Regards"), <br>{{ site_info('name') }} --}}
@endif

@endcomponent
@endcomponent
