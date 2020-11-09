<div class="card-body py-2">
    <div class="table-responsive-sm">
        @if(count($recentWithdrawals) > 0)
            @component('components.table',['class'=> 'table-borderless'])
                @slot('thead')
                    <tr class="text-white">
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Date') }}</th>
                    </tr>
                @endslot

                @foreach($recentWithdrawals as $withdrawal)
                    <tr class="border-top lf-toggle-border-color">
                        <td>
                            <div class="d-flex">
                                <div class="mt-0 mr-2">
                                    <img src="{{ get_avatar($withdrawal->user->avatar) }}"
                                         alt="{{ $withdrawal->user->profile->full_name }}" class="lf-w-30px rounded-circle">
                                </div>
                                <div class="my-auto">
                                    <h6 class="my-0">{{ $withdrawal->user->profile->full_name  }}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $withdrawal->amount }}
                        </td>
                        <td>
                            {{ $withdrawal->created_at->format('jS \of F Y') }}
                        </td>
                    </tr>
                @endforeach
            @endcomponent
        @else
            <p class="text-center my-3">{{ __('Empty') }}</p>
        @endif
    </div>
</div>
@if(count($recentWithdrawals) > 0)
    <div class="card-footer py-0">
        <a href="{{ route('admin.history.withdrawals.index') }}" class="btn btn-block py-3">
            {{ __('View Withdrawal History') }}
        </a>
    </div>
@endif
