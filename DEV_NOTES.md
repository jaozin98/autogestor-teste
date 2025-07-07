# DEV_NOTES.md - AutoGestor

## Visão Geral do Projeto

O **AutoGestor** é um sistema de gestão de produtos desenvolvido em Laravel 12, focado em fornecer uma solução completa para controle de estoque, categorias, marcas e usuários. Como desenvolvedor com conhecimento limitado na stack PHP/Laravel, utilizei IA para auxiliar em aspectos específicos do desenvolvimento, especialmente na resolução de bugs, configuração do Docker e implementação de testes.

## Stack Tecnológica

### Backend

-   **Laravel 12** - Framework PHP principal
-   **PHP 8.2+** - Versão mínima do PHP
-   **MySQL 8.0** - Banco de dados principal
-   **Redis** - Cache e sessões
-   **Spatie Laravel Permission** - Sistema de permissões e roles

### Frontend

-   **Blade Templates** - Engine de templates do Laravel
-   **Tailwind CSS** - Framework CSS utilitário
-   **Alpine.js** - JavaScript reativo
-   **Vite** - Build tool para assets

### DevOps & Testes

-   **Docker & Docker Compose** - Containerização
-   **PHPUnit** - Framework de testes
-   **Laravel Sail** - Ambiente de desenvolvimento
-   **Laravel Pint** - Code style fixer

## Arquitetura do Sistema

### Padrão Repository + Service Layer

Implementei uma arquitetura em camadas seguindo o padrão Repository + Service Layer para garantir separação de responsabilidades e facilitar testes:

```
Controller → Service → Repository → Model
```

#### Estrutura de Interfaces e Implementações

**Repositories:**

-   `ProductRepositoryInterface` → `ProductRepository`
-   `BrandRepositoryInterface` → `BrandRepository`
-   `CategoryRepositoryInterface` → `CategoryRepository`
-   `UserRepositoryInterface` → `UserRepository`

**Services:**

-   `ProductServiceInterface` → `ProductService`
-   `BrandServiceInterface` → `BrandService`
-   `CategoryServiceInterface` → `CategoryService`
-   `UserServiceInterface` → `UserService`

### Injeção de Dependência

Configurei o `AppServiceProvider` para fazer o binding das interfaces com suas implementações:

```php
// AppServiceProvider.php
$this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
$this->app->bind(ProductServiceInterface::class, ProductService::class);
```

## Estrutura de Pastas e Organização

### `/app` - Lógica Principal

```
app/
├── Contracts/          # Interfaces (Repository e Service)
├── Http/
│   ├── Controllers/    # Controllers RESTful
│   ├── Middleware/     # Middlewares customizados
│   ├── Requests/       # Form Requests para validação
│   └── Resources/      # API Resources
├── Models/             # Eloquent Models
├── Observers/          # Model Observers
├── Providers/          # Service Providers
├── Repositories/       # Implementações dos Repositories
└── Services/           # Implementações dos Services
```

### `/database` - Camada de Dados

```
database/
├── factories/          # Factories para testes
├── migrations/         # Migrações do banco
└── seeders/           # Seeders para dados iniciais
```

### `/resources/views` - Interface do Usuário

```
resources/views/
├── auth/              # Views de autenticação
├── brands/            # Views de marcas
├── categories/        # Views de categorias
├── components/        # Componentes Blade reutilizáveis
├── layouts/           # Layouts base
├── products/          # Views de produtos
├── users/             # Views de usuários
└── roles/             # Views de roles e permissões
```

## Sistema de Gestão de Entidades

### 1. **PRODUCTS (Produtos)**

**Modelo:** `Product.php`

-   Relacionamentos: `belongsTo(Category)`, `belongsTo(Brand)`
-   Atributos principais: `name`, `description`, `price`, `stock`, `sku`, `barcode`, `is_active`
-   Soft deletes habilitado

**Funcionalidades implementadas:**

-   CRUD completo com validação
-   Busca por nome, SKU e código de barras
-   Controle de estoque (adicionar, subtrair, definir)
-   Filtros por categoria, marca, estoque baixo
-   Geração automática de SKU
-   Bulk operations (atualização e exclusão em lote)
-   Toggle de status ativo/inativo

**Controller:** `ProductController.php`

-   Middleware de permissões em cada método
-   Tratamento de exceções com try-catch
-   Redirecionamentos com mensagens flash

**Service:** `ProductService.php`

-   Lógica de negócio centralizada
-   Transações de banco de dados
-   Logging de operações
-   Validação de dados
-   Geração de estatísticas

### 2. **CATEGORIES (Categorias)**

**Modelo:** `Category.php`

-   Atributos: `name`, `description`, `is_active`
-   Soft deletes habilitado
-   Scope `active()` para filtrar categorias ativas

**Funcionalidades:**

-   CRUD básico
-   Toggle de status
-   Validação de exclusão (verificar produtos vinculados)

### 3. **BRANDS (Marcas)**

**Modelo:** `Brand.php`

-   Atributos: `name`, `description`, `is_active`
-   Soft deletes habilitado
-   Scope `active()` para filtrar marcas ativas

**Funcionalidades:**

-   CRUD básico
-   Toggle de status
-   Validação de exclusão (verificar produtos vinculados)

### 4. **USERS (Usuários)**

**Modelo:** `User.php`

-   Extensão do modelo padrão do Laravel
-   Trait `HasRoles` do Spatie Permission
-   Atributo virtual `is_active` baseado em `email_verified_at`

**Funcionalidades:**

-   CRUD completo
-   Sistema de roles e permissões
-   Reset de senha
-   Toggle de status ativo/inativo
-   Bulk operations

### 5. **ROLES & PERMISSIONS (Roles e Permissões)**

**Sistema:** Spatie Laravel Permission

-   Roles: `admin`, `manager`, `user`
-   Permissões granulares: `products.view`, `products.create`, `products.edit`, `products.delete`
-   Middleware `CheckPermission` para controle de acesso
-   Middleware `BlockAdminFromCRUD` para proteger usuário admin

## Sistema de Autenticação e Autorização

### Autenticação

-   Laravel Breeze para scaffolding
-   Login/registro tradicional
-   Verificação de email
-   Reset de senha

### Autorização

-   **Spatie Laravel Permission** para controle granular
-   Middleware customizado `CheckPermission`
-   Middleware `BlockAdminFromCRUD` para proteger operações críticas
-   Verificação de permissões em controllers

### Estrutura de Permissões

```
products.*     - Todas as operações de produtos
categories.*   - Todas as operações de categorias
brands.*       - Todas as operações de marcas
users.*        - Todas as operações de usuários
roles.*        - Todas as operações de roles
permissions.*  - Todas as operações de permissões
```

## Middlewares Customizados

### `CheckPermission`

-   Verifica se usuário tem permissão específica
-   Suporte a múltiplas permissões (OR)
-   Redirecionamento em caso de acesso negado

### `BlockAdminFromCRUD`

-   Protege usuário admin de operações CRUD
-   Previne exclusão acidental do admin
-   Aplicado em rotas de produtos, categorias e marcas

## Sistema de Observers

Implementei observers para cada modelo principal para centralizar lógica de eventos:

-   **ProductObserver:** Logging de operações, validações
-   **CategoryObserver:** Verificações antes de exclusão
-   **BrandObserver:** Verificações antes de exclusão
-   **UserObserver:** Logging de operações de usuário

## Validação e Form Requests

Criei Form Requests customizados para cada entidade:

-   `ProductRequest` - Validação de produtos
-   `CategoryRequest` - Validação de categorias
-   `BrandRequest` - Validação de marcas
-   `UserRequest` - Validação de usuários

## Sistema de Testes

### Cobertura de Testes

-   **Feature Tests:** Testes de integração
-   **Unit Tests:** Testes unitários de services
-   **Testes de Middleware:** Verificação de permissões
-   **Testes de Controllers:** CRUD operations

### Estrutura de Testes

```
tests/
├── Feature/
│   ├── Auth/           # Testes de autenticação
│   ├── Controllers/    # Testes de controllers
│   ├── Middleware/     # Testes de middlewares
│   └── Services/       # Testes de services
└── Unit/               # Testes unitários
```

### Comandos de Teste

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos
php artisan test --filter ProductServiceTest
```

## Configuração Docker

### Estrutura Docker

```
docker/
├── mysql/
│   └── my.cnf          # Configuração MySQL
├── nginx/
│   └── default.conf    # Configuração Nginx
├── php/
│   └── local.ini       # Configuração PHP
└── supervisor/
    └── supervisord.conf # Configuração Supervisor
```

### Serviços Docker

-   **app:** Aplicação Laravel (PHP 8.2 + Nginx)
-   **mysql:** Banco principal (porta 3306)
-   **mysql_testing:** Banco de testes (porta 3307)
-   **redis:** Cache e sessões (porta 6379)
-   **nginx:** Servidor web (porta 80)

### Scripts de Automação

-   `docker-setup.sh` - Setup inicial do ambiente
-   `docker-test.sh` - Execução de testes no Docker

## Logs e Monitoramento

### Sistema de Logging

-   Logs estruturados com contexto
-   Diferentes níveis: info, warning, error
-   Logging de operações críticas (CRUD)
-   Logging de erros com stack trace

### Exemplo de Log

```php
Log::info('Produto criado com sucesso', [
    'product_id' => $product->id,
    'name' => $product->name,
    'user_id' => Auth::id(),
]);
```

## Comandos Artisan Customizados

### `AssignUserRoles`

-   Atribui roles padrão aos usuários
-   Execução em massa
-   Validação de roles existentes

### `ClearPermissionCache`

-   Limpa cache de permissões
-   Útil após alterações de roles/permissões
-   Execução automática via scheduler

## Frontend e UI/UX

### Componentes Blade

-   Componentes reutilizáveis em `/resources/views/components/`
-   Layouts responsivos
-   Sistema de alertas e notificações
-   Formulários padronizados

### Estilos

-   **Tailwind CSS** para estilização
-   Componentes customizados
-   Design responsivo
-   Tema consistente

## Decisões de Desenvolvimento

### Por que Repository Pattern?

Como desenvolvedor com conhecimento limitado em Laravel, escolhi o Repository Pattern para:

-   Facilitar testes unitários
-   Separar lógica de negócio da camada de dados
-   Permitir troca de implementações facilmente
-   Manter código organizado e testável

### Por que Service Layer?

-   Centralizar lógica de negócio
-   Facilitar reutilização de código
-   Melhorar testabilidade
-   Separar responsabilidades

### Por que Spatie Permission?

-   Biblioteca madura e bem mantida
-   Integração nativa com Laravel
-   Funcionalidades avançadas (caching, guards)
-   Documentação excelente

### Uso de IA no Desenvolvimento

**Aspectos onde IA foi fundamental:**

1. **Configuração Docker:** Resolução de problemas de networking e volumes
2. **Sistema de Testes:** Estruturação de testes e mocks
3. **Debugging:** Identificação de problemas de middleware e permissões
4. **Otimizações:** Sugestões de performance e cache
5. **Componentização e estilização:** Ajudou muito a quebrar as telas em componentes menores e reutilizar usando o padrão do Blade

**Desenvolvimento sem IA:**

-   Estruturação geral do projeto
-   Implementação de CRUDs
-   Lógica de negócio
-   Validações e regras

## Próximos Passos e Melhorias

### Funcionalidades Planejadas

-   Sistema de notificações
-   Relatórios avançados
-   Importação/exportação de dados
-   Dashboard com gráficos

### Melhorias Técnicas

-   Implementar cache Redis
-   Otimizar queries com eager loading
-   Adicionar testes de performance
-   Implementar CI/CD
-   Documentação da API

### Considerações de Segurança

-   Implementar rate limiting
-   Adicionar auditoria completa
-   Melhorar validação de entrada
-   Implementar backup automático

## Conclusão

O AutoGestor representa um projeto sólido de gestão de produtos, construído com boas práticas de desenvolvimento e arquitetura escalável. A combinação de padrões estabelecidos (Repository, Service Layer) com ferramentas modernas (Laravel 12, Docker, Spatie Permission) resultou em um sistema robusto e manutenível.

O uso estratégico de IA para resolver problemas específicos permitiu focar no desenvolvimento da lógica de negócio e na experiência do usuário, enquanto as ferramentas auxiliares cuidavam dos aspectos técnicos mais complexos.

O projeto demonstra que é possível criar sistemas profissionais mesmo com conhecimento limitado em uma stack específica, desde que se utilize as ferramentas e padrões adequados.

## Problemas Conhecidos e Soluções

### Erro de Duplicação em Seeders

**Problema:**

```
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'admin@autogestor.com' for key 'users.users_email_unique'
```

**Causa:**

-   Tentativa de executar seeders múltiplas vezes
-   Uso de `create()` em vez de `firstOrCreate()` nos seeders
-   Script `docker-setup.sh` não verificava se dados já existiam

**Solução Implementada (com ajuda da IA):**

1. **Modificação dos Seeders:**

    - Alterado `User::create()` para `User::firstOrCreate()` no `DatabaseSeeder.php`
    - Alterado todos os `create()` para `firstOrCreate()` no `SampleDataSeeder.php`
    - Implementado verificação de dados existentes antes de executar seeders

2. **Melhorias no Script `docker-setup.sh`:**

    - Adicionada verificação se seeders já foram executados
    - Opção interativa para decidir se re-executar seeders
    - Modo não-interativo para CI/CD (`NON_INTERACTIVE=true`)
    - Uso de `--force` quando necessário

3. **Comandos de Uso:**

    ```bash
    # Modo interativo (padrão)
    ./docker-setup.sh

    # Modo não-interativo (CI/CD)
    NON_INTERACTIVE=true ./docker-setup.sh
    ```

**Prevenção:**

-   Sempre usar `firstOrCreate()` em seeders para evitar duplicação
-   Verificar existência de dados antes de executar seeders
-   Implementar modo não-interativo para automação

### Outros Problemas Resolvidos com IA

1. **Configuração Docker:**

    - Problemas de networking entre containers
    - Configuração de volumes persistentes
    - Otimização de performance

2. **Sistema de Permissões:**

    - Debugging de middleware de permissões
    - Cache de permissões
    - Atribuição automática de roles

3. **Testes:**

    - Estruturação de testes unitários e de integração
    - Mocks e factories
    - Cobertura de testes

4. **Frontend:**
    - Componentização com Blade
    - Estilização com Tailwind CSS
    - Responsividade e UX

### Lições Aprendidas

-   **IA como Ferramenta de Desenvolvimento:** Fundamental para resolver problemas específicos e otimizar código
-   **Padrões de Desenvolvimento:** Repository + Service Layer facilitam manutenção e testes
-   **Docker para Desenvolvimento:** Ambiente consistente e isolado
-   **Testes desde o Início:** Cobertura de testes evita regressões
-   **Documentação:** DEV_NOTES.md ajuda na manutenção e onboarding
