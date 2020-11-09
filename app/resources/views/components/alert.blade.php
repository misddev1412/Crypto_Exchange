@php
    $attributes = '';

    $class = isset($class) ? 'alert '.$class : 'alert';
    $class = isset($type) ? $class. ' alert-'. $type : $class;
    $class = isset($closeButton) ? $class. ' alert-dismissible fade show' : $class;
    $attributes .= 'class="'.$class.'"';

    if(isset($id)){
        $attributes.= ' id="'.$id.'"';
    }
@endphp

<div {{ view_html($attributes) }} role="alert">
    {{ $slot }}
    @if(isset($closeButton) && $closeButton)
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
</div>
