#!/usr/bin/env bash
# build.sh - Script para construir la aplicación Laravel en Render

set -o errexit  # exit on error

echo "🚀 Iniciando build de Laravel..."

# Instalar dependencias de PHP
echo "📦 Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node.js para Filament
echo "📦 Instalando dependencias de NPM..."
npm ci

# Compilar assets (importante para Filament)
echo "🎨 Compilando assets..."
npm run build

# Generar clave de aplicación si no existe
echo "🔑 Configurando APP_KEY..."
php artisan key:generate --force

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Cachear configuración para mejor rendimiento
echo "⚡ Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
echo "🔗 Creando enlace de storage..."
php artisan storage:link

echo "✅ Build completado exitosamente!"