@extends('layouts.admin')
@section('title', 'System Info')

@section('content')
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="main-content col-lg-12">
				@include('layouts.messages')
				@include('vendor.notice')
				<div class="content-area card">
					<div class="card-innr">
						<div class="card-head">
							<h4 class="card-title card-title-lg">System Information</h4>
							<p class="mt-2">Useful system information about token sales management application.</p>
						</div>
						<div class="gaps gaps-1x"></div>
						<div class="card-text">
							<table class="table table-bordered-plain table-lg table-plain-info fs-13">
								<tr>
									<th colspan="3"><h4 class="text-primary">Site Environment</h4></th>
								</tr>
								<tr>
									<td width="250">Site/App Name</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Application installed in this URL"></em>
									</td>
									<td>
										{{ site_info('name') }}
									</td>
								</tr>
								<tr>
									<td width="250">Site Main URL</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Application installed in this URL"></em>
									</td>
									<td>
										{{ site_info('url') }}
									</td>
								</tr>
								<tr>
									<td width="250">Site App URL</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The main URL of your application set in .env file."></em>
									</td>
									<td>
										{{ site_info('url_app') }} 
										{!! (site_info('url_app')!=site_info('url') ? '<em class="ml-1 fas fa-info-circle fs-12 text-danger" data-toggle="tooltip" data-placement="top" title="URL does not match. Site App URL should be match with site main URL."></em>' : '') !!}
									</td>
								</tr>
								<tr>
									<td width="250">Site API Key</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The API key use to access application data from external."></em>
									</td>
									<td>
										{{ site_info('apikey') }}
									</td>
								</tr>
								<tr>
									<td width="250">Site Language</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The language used in Application."></em>
									</td>
									<td>
										{{ is_lang_switch() ? 'Multiple ('.available_lang().')' : 'English Only' }}
									</td>
								</tr>
								<tr>
									<td width="250">Site App Mode</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Displays environment mode of Application."></em>
									</td>
									<td>
										{{ (env('APP_ENV')) ? ucfirst(env('APP_ENV')) : '-' }}
									</td>
								</tr>
								<tr>
									<td width="250">Debug Mode</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Displays whether or not Application is in Debug Mode."></em>
									</td>
									<td>
										{!! (env('APP_DEBUG')==true) ? '<span class="text-danger">Enable</span>' : 'Disable' !!}
									</td>
								</tr>
								<tr>
									<td width="250">HTTPS Connection</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Is the connection to your application is secure?"></em>
									</td>
									<td>
										{!! (Request::isSecure()) ? 'Yes' : '<span class="text-danger">Your site is not using HTTPS</span>' !!}
									</td>
								</tr>
								<tr>
									<td width="250">Force SSL (HTTPS)</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Force https or not, specify in .env file."></em>
									</td>
									<td>
										{{ (config('icoapp.force_https')==true) ? 'Yes' : 'No' }}
									</td>
								</tr>
								<tr>
									<td width="250">Default Upload Directory</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The system path of your KYC document upload."></em>
									</td>
									<td>
										<code class="text-light">{{ (is_demo_user() || is_demo_preview()) ? str_replace(storage_path(), '', storage_path('app/public')) : storage_path('app/public') }}</code>
									</td>
								</tr>
								<tr>
									<td width="250">KYC Upload Directory</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The system path of your KYC document upload."></em>
									</td>
									<td>
										<code class="text-light">{{ (is_demo_user() || is_demo_preview()) ? str_replace(storage_path(), '', storage_path('app/kyc-files')) : storage_path('app/kyc-files') }}</code>
									</td>
								</tr>
								<tr>
									<td width="250">Log Directory</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The system path of your KYC document upload."></em>
									</td>
									<td>
										<code class="text-light">{{ (is_demo_user() || is_demo_preview()) ? str_replace(storage_path(), '', storage_path('logs')) : storage_path('logs') }}</code>
									</td>
								</tr>
								<tr>
									<td width="250">Cache Directory</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The system path of your KYC document upload."></em>
									</td>
									<td>
										<code class="text-light">{{ (is_demo_user() || is_demo_preview()) ? str_replace(storage_path(), '', storage_path('framework')) : storage_path('framework') }}</code>
									</td>
								</tr>
							</table>

							<div class="gaps gaps-1-5x"></div>
							<table class="table table-bordered-plain table-lg table-plain-info fs-13">
								<tr>
									<th colspan="3"><h4 class="text-primary">Available Payment Module</h4></th>
								</tr>
								@php 
									$pay_modules = json_decode(gws('active_payment_modules'), true);
									$module_names = array_keys($pay_modules); $modules = array_map('strtolower', $module_names);
									$module_exist = is_payment_method_exist('array'); 
								@endphp
								@foreach($modules as $name)
								<tr>
									<td width="250">{{ ucfirst($name) }} Module</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Available payment module in the application."></em>
									</td>
									<td>
										{{ (isset($module_exist->$name->status) ? ucfirst($module_exist->$name->status) : '') }} 
										<code class="ml-2{{ ($pay_modules[ucfirst($name)]['type']=='core') ? ' text-primary' : '' }}">{{ ucfirst($pay_modules[ucfirst($name)]['type']).'/v'.$pay_modules[ucfirst($name)]['version'] }}</code>
									</td>
								</tr>
								@endforeach
							</table>

							<div class="gaps gaps-1-5x"></div>
							<table class="table table-bordered-plain table-lg table-plain-info fs-13">
								<tr>
									<th colspan="3"><h4 class="text-primary">Application Environment</h4></th>
								</tr>
								<tr>
									<td width="250">Application Identifier Key</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The identifier key of TokenLite application."></em>
									</td>
									<td>
										{{ (is_demo_user() || is_demo_preview()) ? '...'.substr(app_info('itemkey'), 1, 3).'...' : app_info('itemkey') }} <small></small>
									</td>
								</tr>
								<tr>
									<td width="250">Application Version</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The version of TokenLite installed on site."></em>
									</td>
									<td>
										{{ 'v'.app_info('version') }} <small>({{ app_info('update') }})</small>
									</td>
								</tr>
								<tr>
									<td width="250">Purchase Code</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Envato Purchase purchase code."></em>
									</td>
									<td>
										{!! (gws('env_pcode')) ? show_str(gws('env_pcode')).( nio_status() ? '' : ' <a href="'.route('admin.tokenlite').'"><span class="text-danger">Not valid</span> - Enter correct code</a>' ) : '<a href="https://www.templaterex.com"><span class="text-success">Licensed</span> - by Template Rex</a>' !!}
									</td>
								</tr>
								<tr>
									<td width="250">License Type</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="Envato license type based on your provided purchase code."></em>
									</td>
									<td>
										{!! (gws('env_ptype') && nio_status()) ? (starts_with(gws('env_ptype'), 1) ? '<span class="text-success">Regular License</span>' : '<span class="text-purple">Extended License</span>') : '<span class="text-success">Unlimited</span>' !!}
									</td>
								</tr>
								<tr>
									<td width="250">License Status</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="License status of this product."></em>
									</td>
									<td>
										{!! (gws('nio_lkey') && nio_status()) ? 'Active <a class="ml-2" href="'.route('admin.tokenlite', ['revoke' => 'license']).'">Revoke License</a>' : '<a href="https://www.templaterex.com"><span class="text-success">Active</span> - by Template Rex</a>' !!}
									</td>
								</tr>
								@if( !empty(gws('nio_lkey')) && nio_status() )
								<tr>
									<td width="250">License Valid For</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="License validity of domain name."></em>
									</td>
									<td>
										{{ nio_status(true) }}
									</td>
								</tr>
								@endif
							</table>
							
							<div class="gaps gaps-1-5x"></div>
							<table class="table table-bordered-plain table-lg table-plain-info fs-13">
								<tr>
									<th colspan="3"><h4 class="text-primary">Server Environment</h4></th>
								</tr>
								<tr>
									<td width="250">Server Info</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The main URL of your application set in .env file."></em>
									</td>
									<td>{{ request()->server('SERVER_SOFTWARE') }}</td>
								</tr>
								<tr>
									<td width="250">PHP Version</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The version of PHP installed on your hosting server."></em>
									</td>
									<td> 
										{!! phpversion() !!}
									</td>
								</tr>
								<tr>
									<td width="250">cURL version</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The version of cURL on your server."></em>
									</td>
									<td> 
										{!! (!empty(curl_version()) ? curl_version()['version'].', '.curl_version()['ssl_version'] : '-')  !!} 
									</td>
								</tr>
								<tr>
									<td width="250">MySQL Version</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The version of MySQL installed on your hosting server."></em>
									</td>
									<td> 
										@php
										$results = DB::select( DB::raw("select version()") );
    									$mysql_version = isset($results[0]->{'version()'}) ? $results[0]->{'version()'} : '*.*.*';
										@endphp
										{{ $mysql_version }}
									</td>
								</tr>
								<tr>
									<td width="250">PHP Post Max Size</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The largest file size that can be contained in one post."></em>
									</td>
									<td> 
										{{ ini_get('post_max_size').'B' }} {!! ((int)ini_get('post_max_size') < 32 ? '<em class="ml-1 fas fa-info-circle fs-11 text-light" data-toggle="tooltip" data-placement="top" title="Recommend is 32MB or above."></em>' : '') !!}
									</td>
								</tr>
								<tr>
									<td width="250">Max Upload Size</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The largest file size that can be contained in one post."></em>
									</td>
									<td> 
										{{ ini_get('upload_max_filesize').'B' }} {!! ((int)ini_get('upload_max_filesize') < 8 ? '<em class="ml-1 fas fa-info-circle fs-11 text-light" data-toggle="tooltip" data-placement="top" title="Recommend is 8MB or above."></em>' : '') !!}
									</td>
								</tr>
								<tr>
									<td width="250">PHP Memory Limit</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The maximum amount of memory (RAM) that your site can use at one time."></em>
									</td>
									<td> 
										{{ ini_get('memory_limit').'B' }} {!! ((int)ini_get('memory_limit') < 256 ? '<em class="ml-1 fas fa-info-circle fs-11 text-light" data-toggle="tooltip" data-placement="top" title="Recommend is 256MB or above."></em>' : '') !!}
									</td>
								</tr>
								<tr>
									<td width="250">PHP Time Limit</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)"></em>
									</td>
									<td> 
										{{ ini_get('max_execution_time') }} {!! ((int)ini_get('max_execution_time') < 300 ? '<em class="ml-1 fas fa-info-circle fs-11 text-light" data-toggle="tooltip" data-placement="top" title="Recommend is 300 or above."></em>' : '') !!}
									</td>
								</tr>
								<tr>
									<td width="250">PHP Max Input Vars</td>
									<td width="24" class="text-center">
										<em class="ti ti-help-alt fs-11" data-toggle="tooltip" data-placement="top" title="The maximum number of variables your server can use for a single function to avoid overloads."></em>
									</td>
									<td> 
										{{ ini_get('max_input_vars') }} {!! ((int)ini_get('max_input_vars') < 1500 ? '<em class="ml-1 fas fa-info-circle fs-11 text-light" data-toggle="tooltip" data-placement="top" title="Recommend is 1500 or above."></em>' : '') !!}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>{{-- .card --}}
			</div>{{-- .col --}}
		</div>{{-- .container --}}
	</div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection