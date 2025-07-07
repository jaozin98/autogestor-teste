@props([
    'items' => [],
    'emptyMessage' => 'Nenhum item encontrado.',
    'tableClass' => 'data-table',
])

@if ($items->count() > 0)
    <div class="table-container">
        <table class="{{ $tableClass }}">
            <thead>
                <tr>
                    {{ $header }}
                </tr>
            </thead>
            <tbody>
                {{ $body }}
            </tbody>
        </table>
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <p>{{ $emptyMessage }}</p>
    </div>
@endif
