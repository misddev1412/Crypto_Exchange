@extends('layouts.admin')
@section('title', $page->title)

@section('content')
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="main-content col-lg-12">
				<div class="content-area card">
					<div class="card-innr">
						@include('layouts.messages')
						<div class="card-head">
							<h4 class="card-title card-title-lg">{{ replace_shortcode($page->title) }}</h4>
							@if($page->meta_description!=null)
							<p class="large">{{ replace_shortcode($page->meta_description) }}</p>
							@endif
						</div>
						@if(!empty($page->description))
						<div class="card-text">
							{!! replace_shortcode(auto_p($page->description)) !!}
						</div>
						@endif
					</div>
				</div>{{-- .card --}}
			</div>{{-- .col --}}
		</div>{{-- .container --}}
	</div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection