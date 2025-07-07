@props([
    'mainColumnClass' => 'col-lg-8',
    'sidebarColumnClass' => 'col-lg-4',
])

<div class="row">
    <div class="{{ $mainColumnClass }}">
        {{ $main }}
    </div>
    <div class="{{ $sidebarColumnClass }}">
        {{ $sidebar }}
    </div>
</div>
