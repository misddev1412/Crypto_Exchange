@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.final.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.final.title') }}
@endsection

@section('container')
	
	@if(session()->has('install_errors'))
    <div class="alert alert-danger">
        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>{{ session('install_errors') }}
    </div>
    @endif

	@if(session('message')['dbOutputLog'])
		<p><strong><small>{{ trans('installer_messages.final.migration') }}</small></strong></p>
		<pre><code>{{ session('message')['dbOutputLog'] }}</code></pre>
	@endif
	
	@if(!empty($finalMessages))
	<p><strong><small>{{ trans('installer_messages.final.console') }}</small></strong></p>
	<pre><code>{{ $finalMessages }}</code></pre>
	@endif
	
	@if(!empty($finalStatusMessage))
	<p><strong><small>{{ trans('installer_messages.final.log') }}</small></strong></p>
	<pre><code>{{ $finalStatusMessage }}</code></pre>
	@endif

	@if(file_exists(storage_path('installed')))
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>
    @else
	<div class="alert "><p>Please download the installed file and placed into your application storage folder, then <a href="{{ url('/') }}">click here</a>.</p></div>
    <div class="buttons">
        <a href="{{ route('LaravelInstaller::finalInstalled') }}" class="button">{{ trans('installer_messages.final.download') }}</a>
    </div>
	<p>&nbsp;</p>
	<div class="alert "><p><em>Caution: If you do not placed the 'installed' file then application will be redirect to installation page again.</em></p></div>
    @endif

@endsection
