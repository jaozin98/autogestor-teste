#!/bin/bash

echo "🔄 Resetando banco de dados AutoGestor..."

# Verificar se os containers estão rodando
if ! docker-compose ps | grep -q "Up"; then
    echo "❌ Containers não estão rodando. Execute 'docker-compose up -d' primeiro."
    exit 1
fi

echo "🗑️  Limpando banco de dados..."
docker-compose exec app php artisan migrate:fresh

echo "🌱 Executando seeders..."
if ! docker-compose exec app php artisan db:seed; then
    echo "❌ Erro ao executar seeders"
    exit 1
fi

echo "🧹 Limpando caches..."
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan permissions:clear-cache

echo "⚡ Otimizando aplicação..."
docker-compose exec app php artisan optimize

echo "✅ Banco de dados resetado com sucesso!"
echo "🌐 Aplicação disponível em: http://localhost:8000"
