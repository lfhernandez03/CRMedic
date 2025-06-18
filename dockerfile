FROM laravelsail/php82-composer:latest

# Instala Node.js y dependencias necesarias del sistema
USER root

RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    libicu-dev \
    unzip \
    && docker-php-ext-install intl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el proyecto
COPY . .

# Instala dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Instala dependencias de Node y compila assets
RUN npm install && npm run build

# Asigna permisos a Laravel
RUN chmod -R 775 storage bootstrap/cache

# Variables de entorno
ENV APP_ENV=production
ENV APP_KEY=base64:rYonqXyOw5iccqeB4K0S0nGFoJxqfw/5sFuB3uZBeTM=
ENV APP_URL=https://crmedic-os8k.onrender.com

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
