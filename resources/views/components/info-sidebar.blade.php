@props([
    'title' => 'Informações',
    'icon' => 'fas fa-info-circle',
    'type' => 'info', // info, tips, warning
])

<div class="card">
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
