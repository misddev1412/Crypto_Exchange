@php
    $attributes = '';

    $class = isset($class) ? 'table table-borderless '.$class : 'table table-borderless';
    $class = isset($type) ? $class. ' table-'. $type : $class;

    $attributes .= 'class="'.$class.'"';

    if(isset($id)){
        $attributes.= ' id="'.$id.'"';
    }
@endphp

<table {{ view_html($attributes) }}>
    @isset($thead)
        <thead>
        {{ $thead }}
        </thead>
    @endisset
    <tbody>
    {{ $slot }}
    </tbody>

    @isset($tfoot)
        <tfoot>
        {{ $tfoot }}
        </tfoot>
    @endisset
</table>
