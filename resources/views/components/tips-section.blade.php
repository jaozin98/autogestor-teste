@props(['tips' => []])

<div class="tips">
    @foreach ($tips as $tip)
        <div class="tip-item">
            <div class="tip-icon">
                <i class="{{ $tip['icon'] ?? 'fas fa-lightbulb' }}"></i>
            </div>
            <div class="tip-content">
                <h4>{{ $tip['title'] }}</h4>
                <p>{{ $tip['description'] }}</p>
            </div>
        </div>
    @endforeach
</div>
