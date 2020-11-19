@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.environment.manual.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-code fa-fw" aria-hidden="true"></i> {{ trans('installer_messages.environment.manual.title') }}
@endsection

@section('container')

    <form method="post" action="{{ route('LaravelInstaller::environmentSaveManual') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <textarea class="textarea" name="envConfig">{{ (session()->has('envConfigData') && !empty(session('envConfigData'))) ? session('envConfigData') : old('envConfig', $envConfig) }}</textarea>
        @if ($checkConnection == false)
        <p class="alert"><strong>Copy the above code and replace the current code of ".env" file. Then refresh this page.</strong></p>
        @endif
    </form>

    @if( (! isset($environment['errors']) && session()->has('showInstallButton')) || $checkConnection == true)
        <div class="buttons-container">
            <a class="button float-right" href="{{ route('LaravelInstaller::database') }}">
                <i class="fa fa-check fa-fw" aria-hidden="true"></i>
                {!! trans('installer_messages.environment.classic.install') !!}
                <i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i>
            </a>
        </div>
    @endif

@endsection