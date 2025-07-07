@props(['active' => false])

@php
    $classes = 'nav-link';
    if ($active) {
        $classes .= ' active';
    }
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
