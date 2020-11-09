<div class="card lf-toggle-border-card lf-toggle-bg-card lf-toggle-text-color lf-market-mt-lg-reverse overflow-hidden h-100">
    <div class="card-body px-3 py-2 h-lg-584px">
        <div class="line-height-medium d-flex justify-content-between align-items-center mt-2 mb-4">
            <span class="font-size-16 font-weight-bold">{{ __('MARKETS') }}</span>
            <div id="market-table-dropdown-wrapper" class="w-50 text-right"></div>
        </div>

        <table class="table table-borderless lf-toggle-text-color w-100 mt-2 mb-0" id="market-table">
            <thead class="lf-toggle-bg-card font-size-12">
                <tr>
                    <th class="font-weight-light p-1 pr-4 lf-toggle-text-color">{{ __('Coin') }}</th>
                    <th class="font-weight-light p-1 pr-4 lf-toggle-text-color text-center">{{ __('Price') }}</th>
                    <th class="font-weight-light p-1 pr-4 text-right">{{ __('Volume') }}</th>
                    <th class="font-weight-light p-1 pr-4 text-right">{{ __('Change') }}</th>
                </tr>
            </thead>
            <tbody class="font-size-10"></tbody>
        </table>
    </div>
</div>
