<div class="section-title {{ isset($align)? 'text-'.$align: '' }} mb-5">
    <h2 class="title font-size-44">
        {{ $slot }}
    </h2>
    @isset($subtite)
    <p>
        {{ $subtite }}
    </p>
    @endisset
</div>
