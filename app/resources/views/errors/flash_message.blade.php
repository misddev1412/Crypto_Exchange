<?php
$class = '';
$message = '';
if(session()->has('success')){
$class = ' flash-success';
$message = session('success');
}
elseif(session()->has('error')){
$class = ' flash-error';
$message = session('error');
}
elseif(session()->has('warning')){
$class = ' flash-warning';
$message = session('warning');
}elseif(isset($errors) && $errors->any()){
$class = ' flash-error';
$message = __('Invalid data in field(s)');
}
?>
<div class="flash-message{{ $message ? ' flash-message-active flash-message-window' : ''}}">
    <div class="centralize-wrapper">
        <div class="centralize-inner">
            <div class="centralize-content{{$class}}">
                <div class="flash-removable">
                    <button type="button" class="close flash-close" aria-hidden="true">×</button>
                    <div class="flash-icon"></div>
                    <p>
                        {{ $message }}
                    </p>
                    <a class="flash-confirm hidden-flash-item btn btn-sm btn-info btn-flat" href="javascript:;">{{ __('Confirm') }}</a>
                    <a class="flash-close hidden-flash-item btn btn-sm btn-warning btn-flat" href="javascript:;">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
