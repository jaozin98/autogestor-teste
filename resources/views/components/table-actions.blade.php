@props([
    'editRoute' => null,
    'deleteRoute' => null,
    'itemName' => 'item',
    'itemId' => null,
])

<div class="table-actions">
    @if ($editRoute)
        <a href="{{ $editRoute }}" class="btn btn-warning btn-sm" title="Editar">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    @if ($deleteRoute)
        <form method="POST" action="{{ $deleteRoute }}" class="inline-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" title="Excluir"
                onclick="return confirm('Tem certeza que deseja excluir este {{ $itemName }}?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif
</div>
