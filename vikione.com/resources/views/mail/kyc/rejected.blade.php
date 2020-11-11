@component('mail::message')

@component('mail::panel', ['status' => $status])

{{-- Content  --}}
<center>
	<h1 class="center">{!! replace_shortcode(replace_with($greeting, '[[user_name]]', $template->name)) !!}</h1>
	<img style="width:88px; margin-bottom:10px;" src="{{asset('assets/images/icons/kyc-reject.png')}}" alt="img">
	<h2 class="center" style="font-size: 18px; color: #ff3649; font-weight: 400; margin-bottom: 8px;">{{ replace_shortcode(replace_with($template->subject, '[[user_name]]', $template->name)) }}</h2>
	<p class="center">{!! str_replace("\n", "<br>",replace_shortcode($template->message)) !!}</p>
</center>
@endcomponent


@endcomponent