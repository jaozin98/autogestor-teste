#!/bin/bash

echo "🧪 Executando testes do AutoGestor..."

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

# Parar containers de teste se estiverem rodando
echo "🛑 Parando containers de teste anteriores..."
docker-compose -f docker-compose.testing.yml down

# Construir e iniciar containers de teste
echo "🔨 Construindo containers de teste..."
docker-compose -f docker-compose.testing.yml build

echo "🚀 Iniciando containers de teste..."
docker-compose -f docker-compose.testing.yml up -d

# Aguardar MySQL estar pronto
echo "⏳ Aguardando MySQL de teste estar pronto..."
sleep 30

# Executar migrações de teste
echo "🔧 Configurando banco de dados de teste..."
docker-compose -f docker-compose.testing.yml exec app_testing php artisan migrate --env=testing

# Executar testes
echo "🧪 Executando testes..."
docker-compose -f docker-compose.testing.yml exec app_testing php artisan test

# Capturar código de saída dos testes
TEST_EXIT_CODE=$?

# Parar containers de teste
echo "🛑 Parando containers de teste..."
docker-compose -f docker-compose.testing.yml down

# Verificar resultado dos testes
if [ $TEST_EXIT_CODE -eq 0 ]; then
    echo "✅ Todos os testes passaram!"
else
    echo "❌ Alguns testes falharam."
fi

exit $TEST_EXIT_CODE
