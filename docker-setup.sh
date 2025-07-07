#!/bin/bash

# Verificar se deve executar de forma não-interativa
NON_INTERACTIVE=${NON_INTERACTIVE:-false}

echo "🚀 Configurando AutoGestor com Docker..."

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

# Copiar arquivo de ambiente se não existir
if [ ! -f .env.docker ]; then
    echo "📝 Copiando arquivo de ambiente..."
    cp .env.docker.example .env.docker
    echo "✅ Arquivo .env.docker criado. Configure as variáveis conforme necessário."
    echo "🔑 Gerando chave de criptografia..."
    docker-compose exec app php artisan key:generate
    echo "✅ Chave de criptografia gerada."
fi

# Construir e iniciar os containers
echo "🔨 Construindo containers..."
docker-compose build

echo "🚀 Iniciando containers..."
docker-compose up -d

# Aguardar MySQL estar pronto
echo "⏳ Aguardando MySQL estar pronto..."
sleep 30

# Verificar se MySQL está respondendo
echo "🔍 Verificando conexão com MySQL..."
until docker-compose exec mysql mysqladmin ping -h"localhost" --silent; do
    echo "⏳ Aguardando MySQL..."
    sleep 5
done
echo "✅ MySQL está pronto!"

# Executar comandos do Laravel
echo "🔧 Configurando Laravel..."
docker-compose exec app php artisan key:generate

echo "📊 Executando migrações..."
if ! docker-compose exec app php artisan migrate; then
    echo "❌ Erro ao executar migrações"
    exit 1
fi

# Verificar se já existem dados no banco
echo "🔍 Verificando se seeders já foram executados..."
if docker-compose exec app php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | grep -q "^[1-9]"; then
    if [ "$NON_INTERACTIVE" = "true" ]; then
        echo "⚠️  Dados já existem no banco. Executando seeders com --force (modo não-interativo)"
        echo "🌱 Executando seeders..."
        if ! docker-compose exec app php artisan db:seed --force; then
            echo "❌ Erro ao executar seeders"
            exit 1
        fi
    else
        echo "⚠️  Dados já existem no banco. Deseja executar os seeders novamente? (s/N)"
        read -r response
        if [[ "$response" =~ ^[Ss]$ ]]; then
            echo "🌱 Executando seeders..."
            if ! docker-compose exec app php artisan db:seed --force; then
                echo "❌ Erro ao executar seeders"
                exit 1
            fi
        else
            echo "⏭️  Pulando execução dos seeders"
        fi
    fi
else
    echo "🌱 Executando seeders..."
    if ! docker-compose exec app php artisan db:seed; then
        echo "❌ Erro ao executar seeders"
        exit 1
    fi
fi
echo "🧹 Limpando caches..."
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan permissions:clear-cache
echo "⚡ Otimizando aplicação..."
docker-compose exec app php artisan optimize

# Configurar permissões
echo "🔐 Configurando permissões..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 755 /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/bootstrap/cache

echo "✅ Setup concluído!"
echo "🌐 Aplicação disponível em: http://localhost:8000"
echo "🗄️  MySQL (dev): localhost:3306"
echo "🧪 MySQL (test): localhost:3307"
echo "🔴 Redis: localhost:6379"
echo ""
echo "📋 Comandos úteis:"
echo "  - docker-compose up -d          # Iniciar containers"
echo "  - docker-compose down           # Parar containers"
echo "  - docker-compose logs -f app    # Ver logs da aplicação"
echo "  - docker-compose exec app bash  # Acessar container da aplicação"
echo "  - docker-compose exec app php artisan migrate:fresh --seed  # Recriar banco"
echo "  - docker-compose exec app php artisan cache:clear           # Limpar cache"
echo "  - docker-compose exec app php artisan permissions:clear-cache # Limpar cache de permissões"
echo "  - docker-compose exec app php artisan test                  # Executar testes"
echo ""
echo "🔄 Para executar o setup de forma não-interativa:"
echo "  - NON_INTERACTIVE=true ./docker-setup.sh"
