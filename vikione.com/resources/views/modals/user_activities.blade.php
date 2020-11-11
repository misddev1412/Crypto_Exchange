<div class="modal fade" id="user-activities" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-lg">
                <h3 class="popup-title">Login Activities <em class="ti ti-angle-right"></em> <small class="tnx-id">{{ set_id($user->id) }}</small></h3>
                <ul class="data-details-alt">
                    @forelse($activities as $activity)
                    @php
                    $browser = explode('/', $activity->browser);
                    $device = explode('/', $activity->device);
                    $ip = ($activity->ip == '::1' || $activity->ip == '127.0.0.1') ? 'localhost' : $activity->ip ;
                    @endphp
                    <li class="text-dark d-md-flex justify-content-between">
                        <div>Login with <span class="text-light">{{ $browser[0] }}</span> in <span class="text-light">{{ end($device) }}</span> via <span class="text-light">{{ $ip }}</span></div>
                        <div><span class="text-light">{{ _date($activity->created_at) }}</span></div>
                    </li>
                    @empty
                    <li> <div> No Activities Found!! </div> </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>