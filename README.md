# AutoGestor

## 📋 Descrição

O **AutoGestor** é um sistema completo de gerenciamento empresarial desenvolvido em Laravel, focado no controle de produtos, categorias, marcas e usuários. O sistema oferece uma interface moderna e intuitiva para gerenciar inventários, com sistema de permissões robusto e funcionalidades avançadas de controle de acesso.

### 🎯 Principais Funcionalidades

-   **Gestão de Produtos**: Cadastro, edição, exclusão e controle de estoque
-   **Gestão de Categorias**: Organização hierárquica de produtos
-   **Gestão de Marcas**: Controle de fabricantes e fornecedores
-   **Sistema de Usuários**: Cadastro e gerenciamento de usuários
-   **Sistema de Permissões**: Controle granular de acesso baseado em roles
-   **Dashboard Interativo**: Visão geral do sistema com estatísticas
-   **Interface Responsiva**: Design moderno e adaptável a diferentes dispositivos
-   **Sistema de Logs**: Rastreamento completo de ações dos usuários

## 🛠️ Tecnologias Utilizadas

### Backend

-   **PHP 8.2+** - Linguagem de programação principal
-   **Laravel 12.0** - Framework PHP para desenvolvimento web
-   **MySQL** - Banco de dados
-   **Spatie Laravel Permission** - Sistema de permissões e roles
-   **Laravel Breeze** - Sistema de autenticação
-   **Laravel UI** - Interface de usuário

### Frontend

-   **Tailwind CSS 3.1** - Framework CSS utilitário
-   **Alpine.js 3.4** - Framework JavaScript minimalista
-   **Bootstrap 5.2** - Componentes de interface
-   **Font Awesome 6.4** - Ícones
-   **Vite 6.2** - Build tool e bundler
-   **Sass** - Pré-processador CSS

### Ferramentas de Desenvolvimento

-   **Laravel Pint** - Code style fixer
-   **PHPUnit** - Framework de testes
-   **Laravel Pail** - Log viewer
-   **Faker** - Geração de dados fictícios para testes

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

-   **PHP 8.2 ou superior**
-   **Composer** (gerenciador de dependências PHP)
-   **Node.js 18+** e **npm** (para assets frontend)
-   **Git** (controle de versão)

### Extensões PHP Necessárias

-   BCMath PHP Extension
-   Ctype PHP Extension
-   cURL PHP Extension
-   DOM PHP Extension
-   Fileinfo PHP Extension
-   JSON PHP Extension
-   Mbstring PHP Extension
-   OpenSSL PHP Extension
-   PCRE PHP Extension
-   PDO PHP Extension
-   Tokenizer PHP Extension
-   XML PHP Extension

## 🚀 Instalação

### 🐳 Usando Docker (Recomendado)

A forma mais fácil de executar o AutoGestor é usando Docker. Isso garante que todos os ambientes sejam idênticos.

#### Pré-requisitos Docker

-   **Docker** - [Instalar Docker](https://docs.docker.com/get-docker/)
-   **Docker Compose** - [Instalar Docker Compose](https://docs.docker.com/compose/install/)

#### Setup Rápido com Docker

1. **Clone o repositório**

```bash
git clone git@github.com:jaozin98/autogestor-teste.git
cd autogestor-teste
```

2. **Execute o script de setup**

```bash
./docker-setup.sh
```

3. **Acesse a aplicação**

-   🌐 **Aplicação**: http://localhost:8000
-   🗄️ **MySQL (dev)**: localhost:3308
-   🧪 **MySQL (test)**: localhost:3307
-   🔴 **Redis**: localhost:6379

#### Scripts de Automação

O projeto inclui scripts para facilitar o desenvolvimento:

```bash
# Setup inicial completo
./docker-setup.sh

# Resetar banco de dados (migrações + seeders)
./docker-reset-db.sh

# Executar testes
./docker-test.sh
```

#### Comandos Docker Úteis

```bash
# Iniciar todos os serviços
docker-compose up -d

# Parar todos os serviços
docker-compose down

# Ver logs da aplicação
docker-compose logs -f app

# Acessar container da aplicação
docker-compose exec app bash

# Executar comandos Artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan migrate:fresh --seed  # Recriar banco
docker-compose exec app php artisan cache:clear           # Limpar cache
docker-compose exec app php artisan permissions:clear-cache # Limpar cache de permissões

# Executar testes
./docker-test.sh

# Reconstruir containers
docker-compose build --no-cache
```

#### Configuração de Ambiente Docker

O Docker Compose inclui:

-   **Aplicação Laravel** (PHP 8.2 + Nginx)
-   **MySQL 8.0** para desenvolvimento
-   **MySQL 8.0** separado para testes
-   **Redis 7** para cache e sessões
-   **Nginx** como servidor web

#### Variáveis de Ambiente

Copie o arquivo de exemplo e configure conforme necessário:

```bash
cp .env.docker.example .env.docker
```

### Instalação Manual

### macOS

#### 1. Instalar Homebrew (se não tiver)

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

#### 2. Instalar PHP

```bash
brew install php@8.2
brew link php@8.2
```

#### 3. Instalar Composer

```bash
brew install composer
```

#### 4. Instalar Node.js

```bash
brew install node
```

#### 5. Instalar Git

```bash
brew install git
```

### Windows

#### 1. Instalar XAMPP ou Laragon

-   **XAMPP**: Baixe em [https://www.apachefriends.org/](https://www.apachefriends.org/)
-   **Laragon**: Baixe em [https://laragon.org/](https://laragon.org/)

#### 2. Instalar Composer

-   Baixe o instalador em [https://getcomposer.org/download/](https://getcomposer.org/download/)
-   Execute o arquivo baixado e siga as instruções

#### 3. Instalar Node.js

-   Baixe em [https://nodejs.org/](https://nodejs.org/)
-   Execute o instalador e siga as instruções

#### 4. Instalar Git

-   Baixe em [https://git-scm.com/](https://git-scm.com/)
-   Execute o instalador e siga as instruções

### Linux (Ubuntu/Debian)

#### 1. Atualizar o sistema

```bash
sudo apt update && sudo apt upgrade -y
```

#### 2. Instalar PHP e extensões

```bash
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-json php8.2-tokenizer php8.2-fileinfo php8.2-pdo php8.2-pdo-mysql php8.2-dom php8.2-ctype php8.2-pcre php8.2-openssl -y
```

#### 3. Instalar Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 4. Instalar Node.js

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

#### 5. Instalar Git

```bash
sudo apt install git -y
```

## 🔧 Configuração do Projeto

### 1. Clonar o repositório

```bash
git clone git@github.com:jaozin98/autogestor-teste.git
cd autogestor-teste
```

### 2. Instalar dependências PHP

```bash
composer install
```

### 3. Instalar dependências Node.js

```bash
npm install
```

### 4. Configurar arquivo de ambiente

```bash
cp .env.example .env
```

### 5. Gerar chave da aplicação

```bash
php artisan key:generate
```

### 6. Configurar banco de dados

#### Para MySQL:

Edite o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=autogestor
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 7. Executar migrações

```bash
php artisan migrate
```

### 8. Executar seeders

```bash
php artisan db:seed
```

### 9. Compilar assets

```bash
npm run build
```

### 10. Configurar permissões (Linux/macOS)

```bash
chmod -R 775 storage bootstrap/cache
```

## 🏃‍♂️ Executando o Projeto

### 🐳 Desenvolvimento com Docker

#### 1. Iniciar ambiente de desenvolvimento

```bash
docker-compose up -d
```

#### 2. Acessar a aplicação

O projeto estará disponível em: `http://localhost:8000`

#### 3. Compilar assets em modo de desenvolvimento

```bash
docker-compose exec app npm run dev
```

#### 4. Executar comandos Artisan

```bash
# Migrações
docker-compose exec app php artisan migrate

# Seeders
docker-compose exec app php artisan db:seed

# Limpar cache
docker-compose exec app php artisan cache:clear

# Ver logs
docker-compose exec app php artisan pail
```

#### 5. Acessar container para desenvolvimento

```bash
docker-compose exec app bash
```

### Desenvolvimento Manual

#### 1. Iniciar servidor de desenvolvimento

```bash
php artisan serve
```

O projeto estará disponível em: `http://localhost:8000`

#### 2. Compilar assets em modo de desenvolvimento

```bash
npm run dev
```

#### 3. Executar ambos simultaneamente

```bash
composer run dev
```

### Produção

#### 1. Otimizar para produção

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

#### 2. Configurar servidor web

Configure seu servidor web (Apache/Nginx) para apontar para a pasta `public/`

## 👤 Usuários Padrão

Após executar os seeders, os seguintes usuários estarão disponíveis:

### Administrador

-   **Email**: `admin@autogestor.com`
-   **Senha**: `12345678`
-   **Permissões**: Acesso total ao sistema

### Usuário Padrão

-   **Email**: Qualquer email que você criar
-   **Senha**: Definida durante o cadastro
-   **Permissões**: Acesso básico a produtos, categorias e marcas

## 📊 Estrutura do Banco de Dados

### Tabelas Principais

-   `users` - Usuários do sistema
-   `products` - Produtos
-   `categories` - Categorias
-   `brands` - Marcas
-   `roles` - Papéis de usuário
-   `permissions` - Permissões
-   `model_has_roles` - Relacionamento usuário-papel
-   `model_has_permissions` - Relacionamento usuário-permissão
-   `role_has_permissions` - Relacionamento papel-permissão

## 🔐 Sistema de Permissões

O AutoGestor utiliza o pacote Spatie Laravel Permission para controle de acesso:

### Roles Padrão

-   **admin**: Acesso a gerenciamento de usuários, roles e permissions
-   **user**: Acesso a gerenciamendo de produtos, categorias e marcas

### Permissões Principais

-   `products.*` - Gerenciamento de produtos
-   `categories.*` - Gerenciamento de categorias
-   `brands.*` - Gerenciamento de marcas
-   `users.*` - Gerenciamento de usuários
-   `roles.*` - Gerenciamento de papéis
-   `permissions.*` - Gerenciamento de permissões

## 🧪 Testes

### 🐳 Testes com Docker (Recomendado)

Para executar testes em um ambiente isolado e consistente:

```bash
# Executar todos os testes
./docker-test.sh

# Ou manualmente:
docker-compose -f docker-compose.testing.yml up -d
docker-compose -f docker-compose.testing.yml exec app_testing php artisan migrate --env=testing
docker-compose -f docker-compose.testing.yml exec app_testing php artisan test
docker-compose -f docker-compose.testing.yml down
```

### Testes Manuais

#### Executar todos os testes

```bash
php artisan test
```

#### Executar testes específicos

```bash
php artisan test --filter=ProductServiceTest
```

#### Executar testes com cobertura

```bash
php artisan test --coverage
```

## 📝 Comandos Artisan Úteis

### Cache e Otimização

```bash
php artisan config:clear    # Limpar cache de configuração
php artisan route:clear     # Limpar cache de rotas
php artisan view:clear      # Limpar cache de views
php artisan cache:clear     # Limpar cache geral
php artisan optimize        # Otimizar aplicação
```

### Banco de Dados

```bash
php artisan migrate:fresh   # Recriar banco e executar migrações
php artisan migrate:refresh # Reverter e executar migrações
php artisan db:seed         # Executar seeders
php artisan db:wipe         # Limpar banco de dados
```

### Permissões

```bash
php artisan permission:cache-reset  # Limpar cache de permissões
php artisan assign:user-roles       # Atribuir roles aos usuários
```

### Logs

```bash
php artisan pail            # Visualizar logs em tempo real
```

## 🐛 Solução de Problemas

### 🐳 Problemas com Docker

#### Container não inicia

```bash
# Verificar logs
docker-compose logs app

# Reconstruir containers
docker-compose build --no-cache
docker-compose up -d
```

#### Erro de conexão com banco de dados

```bash
# Verificar se MySQL está rodando
docker-compose ps

# Reiniciar apenas o MySQL
docker-compose restart mysql
```

#### Erro de permissões no container

```bash
# Corrigir permissões
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
```

#### Limpar volumes Docker

```bash
# Parar e remover volumes
docker-compose down -v

# Remover imagens não utilizadas
docker system prune -a
```

### Problemas Manuais

#### Erro de permissões (Linux/macOS)

```bash
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

#### Erro de extensões PHP

Verifique se todas as extensões necessárias estão instaladas:

```bash
php -m | grep -E "(bcmath|ctype|curl|dom|fileinfo|json|mbstring|openssl|pcre|pdo|tokenizer|xml)"
```

#### Erro de dependências Node.js

```bash
rm -rf node_modules package-lock.json
npm install
```

#### Erro de dependências Composer

```bash
rm -rf vendor composer.lock
composer install
```

#### Erro de banco de dados

```bash
php artisan migrate:fresh --seed
```

## 📁 Estrutura do Projeto

```
AutoGestor/
├── app/
│   ├── Console/Commands/          # Comandos personalizados
│   ├── Contracts/                 # Interfaces e contratos
│   ├── Http/
│   │   ├── Controllers/           # Controladores
│   │   ├── Middleware/            # Middlewares
│   │   ├── Requests/              # Validações
│   │   └── Resources/             # API Resources
│   ├── Models/                    # Modelos Eloquent
│   ├── Observers/                 # Observers dos modelos
│   ├── Providers/                 # Service Providers
│   ├── Repositories/              # Repositórios
│   └── Services/                  # Serviços de negócio
├── config/                        # Arquivos de configuração
├── database/
│   ├── factories/                 # Factories para testes
│   ├── migrations/                # Migrações do banco
│   └── seeders/                   # Seeders
├── docker/                        # Configurações Docker
│   ├── nginx/                     # Configuração Nginx
│   ├── mysql/                     # Configuração MySQL
│   ├── php/                       # Configuração PHP
│   └── supervisor/                # Configuração Supervisor
├── public/                        # Arquivos públicos
├── resources/
│   ├── css/                       # Estilos CSS
│   ├── js/                        # JavaScript
│   ├── lang/                      # Traduções
│   ├── sass/                      # Arquivos Sass
│   └── views/                     # Views Blade
├── routes/                        # Definição de rotas
├── storage/                       # Arquivos de armazenamento
├── tests/                         # Testes automatizados
├── Dockerfile                     # Imagem Docker da aplicação
├── docker-compose.yml             # Configuração Docker Compose
├── docker-compose.testing.yml     # Configuração Docker para testes
├── docker-setup.sh                # Script de setup Docker
├── docker-test.sh                 # Script de execução de testes
├── env.docker.example             # Exemplo de variáveis de ambiente
└── .dockerignore                  # Arquivos ignorados no build Docker
```

## 🤝 Contribuindo

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📞 Suporte

Para suporte e dúvidas:

-   Abra uma issue no repositório
-   Entre em contato com a equipe de desenvolvimento

## 🔄 Atualizações

Para manter o projeto atualizado:

```bash
# Atualizar dependências PHP
composer update

# Atualizar dependências Node.js
npm update

# Executar migrações pendentes
php artisan migrate

# Limpar caches
php artisan optimize
```

---

**AutoGestor** - Sistema de Gerenciamento Empresarial Completo
