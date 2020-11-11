@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
@php 
$base_cur = base_currency();
@endphp
<div class="page-content">
	<div class="container">
        @include('vendor.notice')
        @include('layouts.messages')
		<div class="row">
			<div class="col-lg-4 col-md-6">
                <div class="card height-auto">
                    <div class="card-innr">
                        <div class="tile-header">
                            <h6 class="tile-title">Token Sale - {{ $stage->stage->name }}</h6>
                        </div>
                        <div class="tile-data">
                            <span class="tile-data-number">{{ to_num($stage->stage->total_tokens, 0, ',', false) }}</span>
                            <span class="tile-data-status tile-data-active" title="Sales %" data-toggle="tooltip" data-placement="right">{{ $stage->trnxs->percent }}%</span>
                        </div>
                        <div class="tile-footer">
                            <div class="tile-recent">
                                <span class="tile-recent-number">{{ to_num($stage->trnxs->last_week, 0, ',', false) }}</span>
                                <span class="tile-recent-text">since last week</span>
                            </div>
                            <div class="tile-link">
                                <a href="{{ route('admin.stages') }}" class="link link-thin link-ucap link-dim">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card height-auto">
                    <div class="card-innr">
                        <ul class="tile-nav nav">
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view-kycs">KYC</a></li>
                        	<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view-users">User</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="view-users">
                                <div class="tile-header">
                                    <h6 class="tile-title">Total Users</h6>
                                </div>
                                <div class="tile-data">
                                    <span class="tile-data-number">{{ to_num($users->all, 0, ',', false) }}</span>
                                    <span class="tile-data-status tile-data-active" title="Verified" data-toggle="tooltip" data-placement="right">{{ $users->verified }}%</span>
                                </div>
                                <div class="tile-footer">
                                    <div class="tile-recent">
                                        <span class="tile-recent-number">{{ to_num($users->last_week, 0, ',', false) }}</span>
                                        <span class="tile-recent-text">since last week</span>
                                    </div>
                                    <div class="tile-link">
                                        <a href="{{ route('admin.users') }}" class="link link-thin link-ucap link-dim">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="view-kycs">
                                <div class="tile-header">
                                    <h6 class="tile-title">Total KYC</h6>
                                </div>
                                <div class="tile-data">
                                    <span class="tile-data-number">{{ to_num($users->kyc_submit, 0, ',', false) }}</span>
                                    <span class="tile-data-status tile-data-active" title="Approved" data-toggle="tooltip" data-placement="right">{{ $users->kyc_approved }}%</span>
                                </div>
                                <div class="tile-footer">
                                    <div class="tile-recent">
                                        <span class="tile-recent-number">{{ to_num($users->kyc_last_week, 0, ',', false) }}</span>
                                        <span class="tile-recent-text">since last week</span>
                                    </div>
                                    <div class="tile-link">
                                        <a href="{{ route('admin.kycs') }}" class="link link-thin link-ucap link-dim">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="token-statistics card card-token height-auto">
                    <div class="card-innr">
                        <ul class="tile-nav nav">
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view-fiat">Fiat</a></li>
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view-crypto">Crypto</a></li>
                        </ul>
                        <div class="token-balance token-balance-s3">
                            <div class="token-balance-text">
                                <h6 class="card-sub-title">AMOUNT COLLECTED</h6>
                                <span class="lead">{{ to_num($trnxs->currency->base, 'auto', ',') }} 
                                <span>{{ strtoupper($base_cur) }} 
                                <em class="fas fa-info-circle fs-11" data-toggle="tooltip" data-placement="right" title="Combined calculation of all transactions in base currency."></em></span> 
                            </span>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="view-crypto">
                                <div class="token-balance token-balance-s2">
                                    <ul class="token-balance-list">
                                        <li class="token-balance-sub">
                                            <span class="lead">{{ to_num($trnxs->currency->eth, 'auto') }}</span>
                                            <span class="sub">ETH</span>
                                        </li>
                                        <li class="token-balance-sub">
                                            <span class="lead">{{ to_num($trnxs->currency->btc, 'auto') }}</span>
                                            <span class="sub">BTC</span>
                                        </li>
                                        <li class="token-balance-sub">
                                            <span class="lead">{{ to_num($trnxs->currency->ltc, 'auto') }}</span>
                                            <span class="sub">LTC</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="view-fiat">
                                <div class="token-balance token-balance-s2">
                                    <ul class="token-balance-list">
                                        <li class="token-balance-sub">
                                            <span class="lead">{{ to_num($trnxs->currency->usd, 'auto') }}</span>
                                            <span class="sub">USD</span>
                                        </li>
                                        <li class="token-balance-sub">
                                            <span class="lead">{{ to_num($trnxs->currency->eur, 'auto') }}</span>
                                            <span class="sub">EUR</span>
                                        </li>
                                        <li class="token-balance-sub">
                                            <span class="lead">{{ to_num($trnxs->currency->gbp, 'auto') }}</span>
                                            <span class="sub">GBP</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="token-transaction card card-full-height">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm">Recent Transaction</h4>
                            <div class="card-opt">
                                <a href="{{ route('admin.transactions') }}" class="link ucap">View ALL <em class="fas fa-angle-right ml-2"></em></a>
                            </div>
                        </div>
                        <table class="table tnx-table">
                            <tbody>
                            	@forelse($trnxs->all as $tnx)
                                <tr>
                                    <td>
                                        <h5 class="lead mb-1">{{ $tnx->tnx_id}}</h5>
                                        <span class="sub">{{ _date($tnx->tnx_time) }}</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <h5 class="lead mb-1{{ ($tnx->tnx_type=='refund') ? ' text-danger' : '' }}">
                                            {{ (starts_with($tnx->total_tokens, '-') ? '' : '+').to_round($tnx->total_tokens, 'min') }}
                                        </h5>
                                        <span class="sub ucap">{{ to_num($tnx->amount, 'max').' '.$tnx->currency }}</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="data-state data-state-{{ __status($tnx->status, 'icon') }}"></div>
                                    </td>
                                </tr>
                                @empty
								<tr class="data-item text-center">
									<td class="data-col" colspan="4">No available transaction!</td>
								</tr>
								@endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="token-sale-graph card card-full-height">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm">Token Sale Graph</h4>
                            <div class="card-opt">
                                <a href="{{ url()->current() }}" class="link ucap link-light toggle-tigger toggle-caret">{{ isset($_GET['chart']) ? $_GET['chart'] : 15 }} Days</a>
								<div class="toggle-class dropdown-content">
									<ul class="dropdown-list">
										<li><a href="{{ url()->current() }}?chart=7">7 Days</a></li>
										<li><a href="{{ url()->current() }}?chart=15">15 Days</a></li>
										<li><a href="{{ url()->current() }}?chart=30">30 Days</a></li>
									</ul>
								</div>
                            </div>
                        </div>
                        <div class="chart-tokensale chart-tokensale-long">
                            <canvas id="tknSale"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="reg-statistic-graph card card-full-height">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm">Registration Statistics</h4>
                            <div class="card-opt">
                                <a href="{{ url()->current() }}" class="link ucap link-light toggle-tigger toggle-caret">{{ isset($_GET['user']) ? $_GET['user'] : 15 }} Days</a>
                                <div class="toggle-class dropdown-content">
                                    <ul class="dropdown-list">
                                        <li><a href="{{ url()->current() }}?user=7">7 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=15">15 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=30">30 Days</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="chart-statistics mb-0">
                            <canvas id="regStatistics"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card card-full-height">
                    <div class="card-innr">
                        <div class="phase-block guttar-20px">
                            <div class="fake-class">
                                <div class="card-head has-aside">
                                    <h4 class="card-title card-title-sm">Stage - {{ $stage->stage->name }}</h4>
                                </div>
                                <ul class="phase-status">
                                    <li>
                                        <div class="phase-status-title">Total</div>
                                        <div class="phase-status-subtitle">{{ to_num_token($stage->stage->total_tokens) }}</div>
                                    </li>
                                    <li>
                                        <div class="phase-status-title">Sold</div>
                                        <div class="phase-status-subtitle">{{ to_num_token($stage->stage->soldout) }} <span>*</span></div>
                                    </li>
                                    <li>
                                        <div class="phase-status-title">Sale %</div>
                                        <div class="phase-status-subtitle">{{ $stage->trnxs->percent }}% Sold</div>
                                    </li>
                                    <li>
                                        <div class="phase-status-title">Unsold</div>
                                        <div class="phase-status-subtitle">{{ to_num_token(($stage->stage->total_tokens - $stage->stage->soldout)) }}</div>
                                    </li>
                                </ul>
                                <div class="notes">* Not included pending <strong>{{ to_num_token($stage->trnxs->pending) }}</strong> token sales.</div>
                            </div>
                            <div class="fake-class">
                                <div class="chart-phase">
                                    <div class="phase-status-total">
                                        <span class="lead">{{ to_num($stage->stage->total_tokens, 0, ',', false) }}</span>
                                        <span class="sub">{{ token_symbol() }}</span>
                                    </div>
                                    <div class="chart-tokensale-s2">
                                        <canvas id="phaseStatus"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- .card --}}
            </div>
		</div>{{-- .row --}}
	</div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection

@push('footer')
<script type="text/javascript">
	var tnx_labels = [<?=$trnxs->chart->days?>], tnx_data = [<?=$trnxs->chart->data?>], 
    user_labels = [<?=$users->chart->days?>], user_data = [<?=$users->chart->data?>],
    theme_color = {base:"<?=theme_color('base')?>", text: "<?=theme_color('text')?>", heading: "<?=theme_color('heading')?>"},
	phase_data = [{{ round($stage->stage->soldout, 2) }}, {{ (($stage->stage->total_tokens - $stage->stage->soldout) > 0 ? round(($stage->stage->total_tokens - $stage->stage->soldout), 2) : 0) }}];
</script>

<script src="{{ asset('assets/js/admin.chart.js') }}"></script>
@endpush