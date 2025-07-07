@props(['model', 'infoItems' => []])

<div class="model-info">
    @foreach ($infoItems as $item)
        <div class="info-item">
            <div class="info-label">
                <i class="{{ $item['icon'] ?? 'fas fa-info' }}"></i>
                {{ $item['label'] }}
            </div>
            <div class="info-value">
                @if (isset($item['badge']))
                    <span class="badge bg-{{ $item['badge']['type'] ?? 'info' }}">
                        @if (isset($item['badge']['icon']))
                            <i class="{{ $item['badge']['icon'] }}"></i>
                        @endif
                        {{ $item['value'] }}
                    </span>
                @else
                    {{ $item['value'] }}
                @endif
            </div>
        </div>
    @endforeach
</div>
