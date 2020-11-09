<div class="card-body py-2">
    <div class="table-responsive-sm">
        @if(count($recentDeposits) > 0)
            @component('components.table',['class'=> 'table-borderless'])
                @slot('thead')
                    <tr class="text-white">
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Date') }}</th>
                    </tr>
                @endslot

                @foreach($recentDeposits as $deposit)
                    <tr class="border-top lf-toggle-border-color">
                        <td>
                            <div class="d-flex">
                                <div class="mt-0 mr-2">
                                    <img src="{{ get_avatar($deposit->user->avatar) }}"
                                         alt="{{ $deposit->user->profile->full_name }}" class="lf-w-30px rounded-circle">
                                </div>
                                <div class="my-auto">
                                    <h6 class="my-0">{{ $deposit->user->profile->full_name  }}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $deposit->amount }}
                        </td>
                        <td>
                            {{ $deposit->created_at->format('jS \of F Y') }}
                        </td>
                    </tr>
                @endforeach
            @endcomponent
        @else
            <p class="text-center my-3">{{ __('Empty') }}</p>
        @endif
    </div>
</div>
@if(count($recentDeposits) > 0)
    <div class="card-footer py-0">
        <a href="{{ route('admin.history.deposits.index') }}" class="btn btn-block py-3">
            {{ __('View Deposit History') }}
        </a>
    </div>
@endif
