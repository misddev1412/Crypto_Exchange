<div class="card-body py-2">
    <div class="table-responsive-sm" >
        @if(count($recentTrades) > 0)
            @component('components.table',['class'=> 'table-borderless'])
                @slot('thead')
                    <tr class="text-white">
                        <th>{{ __('Coin Pair') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Total') }}</th>
                    </tr>
                @endslot

                @foreach($recentTrades as $exchange)
                    <tr class="border-top lf-toggle-border-color">
                        <td>
                            {{ $exchange->trade_pair }}
                        </td>
                        <td>
                            {{ $exchange->price }}
                        </td>
                        <td>
                            {{ $exchange->amount }}
                        </td>
                        <td>
                            {{ $exchange->total }}
                        </td>
                    </tr>
                @endforeach
            @endcomponent
        @else
            <p class="text-center my-3">{{ __('Empty') }}</p>
        @endif
    </div>
</div>
@if(count($recentTrades) > 0)
    <div class="card-footer py-0">
        <a href="#" class="btn btn-block py-3">
            {{ __('View Trade History') }}
        </a>
    </div>
@endif
