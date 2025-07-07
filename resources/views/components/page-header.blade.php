@props([
    'title',
    'description' => null,
    'subtitle' => null,
    'icon' => 'fas fa-edit',
    'showViewButton' => false,
    'viewRoute' => null,
    'viewModel' => null,
    'backRoute' => null,
    'backText' => 'Voltar',
    'button' => null,
])

<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="{{ $icon }}"></i>
                {{ $title }}
            </h1>
            @if ($description)
                <p class="text-gray-600">{{ $description }}</p>
            @elseif($subtitle)
                <p class="text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-3">
            @if ($showViewButton && $viewRoute && $viewModel)
                <a href="{{ route($viewRoute, $viewModel) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i>
                    Visualizar
                </a>
            @endif
            @if ($backRoute)
                <a href="{{ route($backRoute) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ $backText }}
                </a>
            @endif
            @if ($button)
                <a href="{{ $button['url'] }}" class="btn btn-{{ $button['type'] ?? 'primary' }}">
                    <i class="fas fa-{{ $button['icon'] ?? 'arrow-left' }}"></i>
                    {{ $button['text'] }}
                </a>
            @endif
        </div>
    </div>
</div>
