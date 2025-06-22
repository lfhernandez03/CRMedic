# Usar imagen base optimizada para Laravel
FROM laravelsail/php82-composer:latest

# Cambiar a root para instalaciones
USER root

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    libicu-dev \
    unzip \
    supervisor \
    && docker-php-ext-install intl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de dependencias primero (para cache de Docker)
COPY composer.json composer.lock package.json package-lock.json ./

# Instalar dependencias de PHP (sin scripts para evitar errores)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Instalar dependencias de Node.js
RUN npm ci --only=production

# Copiar el resto del código
COPY . .

# Crear directorios necesarios y establecer permisos
RUN mkdir -p storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R sail:sail /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Cambiar al usuario sail
USER sail

# Compilar assets (después de copiar todo el código)
RUN npm run build

# Crear enlace simbólico para storage
RUN php artisan storage:link || true

# Limpiar caches y optimizar (sin cache de config por variables de entorno)
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Variables de entorno
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:rYonqXyOw5iccqeB4K0S0nGFoJxqfw/5sFuB3uZBeTM=
ENV APP_URL=https://crmedic-c38h.onrender.com
ENV LOG_CHANNEL=stderr

# Puerto dinámico para Render
EXPOSE ${PORT:-8080}

# Script de inicio mejorado
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php -S 0.0.0.0:${PORT:-8080} -t public