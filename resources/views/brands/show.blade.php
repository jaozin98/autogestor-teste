@extends('layouts.app')

@section('title', $brand->name)

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-copyright"></i>
                    {{ $brand->name }}
                </h1>
                <p class="page-subtitle">Detalhes da marca</p>
            </div>
            <div>
                @can('brands.edit')
                    <a href="{{ route('brands.edit', $brand) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                @endcan
                <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações Principais -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Informações da Marca
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tag"></i>
                                    Nome da Marca
                                </label>
                                <p class="mb-0">{{ $brand->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hashtag"></i>
                                    ID
                                </label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary">#{{ $brand->id }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-flag"></i>
                                    País de Origem
                                </label>
                                <p class="mb-0">
                                    @if ($brand->country_of_origin)
                                        <span class="badge bg-info">{{ $brand->country_of_origin }}</span>
                                    @else
                                        <span class="text-muted">Não informado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar"></i>
                                    Ano de Fundação
                                </label>
                                <p class="mb-0">
                                    @if ($brand->founded_year)
                                        <span class="badge bg-primary">{{ $brand->founded_year }}</span>
                                        @if ($brand->age)
                                            <br>
                                            <small class="text-muted">{{ $brand->age }} anos de existência</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Não informado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($brand->website)
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-globe"></i>
                                Website
                            </label>
                            <p class="mb-0">
                                <a href="{{ $brand->website }}" target="_blank" class="text-decoration-none"
                                    title="Visitar website">
                                    <i class="fas fa-external-link-alt"></i>
                                    {{ $brand->formatted_website }}
                                </a>
                            </p>
                        </div>
                    @endif

                    @if ($brand->description)
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-align-left"></i>
                                Descrição
                            </label>
                            <p class="mb-0">{{ $brand->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Produtos da Marca -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-box"></i>
                        Produtos da Marca
                        <span class="badge bg-primary ms-2">{{ $brand->products->count() }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    @if ($brand->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Preço</th>
                                        <th>Categoria</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brand->products as $product)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">#{{ $product->id }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                @if ($product->description)
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($product->category)
                                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Ativo</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum produto associado a esta marca</h5>
                            <p class="text-muted">Esta marca ainda não possui produtos cadastrados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Estatísticas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-bar"></i>
                        Estatísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Total de Produtos:</span>
                            <span class="badge bg-primary">{{ $brand->products->count() }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-{{ $brand->products->count() > 0 ? 'success' : 'warning' }}">
                                {{ $brand->products->count() > 0 ? 'Ativa' : 'Sem Produtos' }}
                            </span>
                        </div>
                    </div>
                    @if ($brand->age)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Idade da Marca:</span>
                                <span class="badge bg-info">{{ $brand->age }} anos</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-cog"></i>
                        Informações do Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Criada em:</strong><br>
                        <small class="text-muted">{{ $brand->created_at->format('d/m/Y H:i:s') }}</small>
                    </div>
                    <div class="mb-3">
                        <strong>Última atualização:</strong><br>
                        <small class="text-muted">{{ $brand->updated_at->format('d/m/Y H:i:s') }}</small>
                    </div>
                    <div class="mb-3">
                        <strong>Tempo no sistema:</strong><br>
                        <small class="text-muted">{{ $brand->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    @can('brands.edit')
                        <a href="{{ route('brands.edit', $brand) }}" class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="fas fa-edit"></i>
                            Editar Marca
                        </a>
                    @endcan

                    @can('brands.delete')
                        @if (!$brand->hasProducts())
                            <button type="button" class="btn btn-danger btn-sm w-100 mb-2"
                                onclick="confirmDelete('{{ $brand->id }}', '{{ $brand->name }}')">
                                <i class="fas fa-trash"></i>
                                Excluir Marca
                            </button>
                        @else
                            <button type="button" class="btn btn-danger btn-sm w-100 mb-2" disabled
                                title="Não é possível excluir uma marca com produtos associados">
                                <i class="fas fa-trash"></i>
                                Excluir Marca
                            </button>
                        @endif
                    @endcan

                    <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-list"></i>
                        Ver Todas as Marcas
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(brandId, brandName) {
            if (confirm(`Tem certeza que deseja excluir a marca "${brandName}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/brands/${brandId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
