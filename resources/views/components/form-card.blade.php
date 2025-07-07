@props(['title', 'icon' => 'fas fa-edit', 'class' => ''])

<div class="card {{ $class }}">
    <div class="card-header">
        <h3 class="card-title">
            <i class="{{ $icon }}"></i>
            {{ $title }}
        </h3>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
