@php
    $attributes = '';

    $class = isset($class) ? 'card card-outline '.$class : 'card card-outline';
    $class = isset($type) ? $class. ' card-'. $type : $class;
    $headerClass = isset($headerClass) ? 'card-header '. $headerClass  : 'card-header';
    $bodyClass = isset($bodyClass) ? 'card-body '. $bodyClass  : 'card-body';
    $footerClass = isset($footerClass) ? 'card-footer '. $footerClass  : 'card-footer';
    $attributes .= 'class="'.$class.'"';

    if(isset($id)){
        $attributes.= ' id="'.$id.'"';
    }
@endphp

<div {{ view_html($attributes) }}>
    @isset($header)
        <div class="{{ $headerClass }}">
            {{ $header }}
        </div>
    @endisset

    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="{{ $footerClass }}">
            {{ $footer }}
        </div>
    @endisset
</div>
