FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_mysql \
        intl \
        zip \
        bcmath

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copiar composer files primero para cache layers
COPY composer.json composer.lock ./

# Instalar dependencias sin autoloader optimizado primero
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --no-autoloader --no-scripts --no-interaction

# Copiar el resto del código
COPY . .

# Generar autoloader después de copiar todo el código
RUN COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize --no-dev

# Regenerar el autoloader de Laravel/Filament
RUN php artisan clear-compiled || true
RUN php artisan package:discover --ansi

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000
CMD ["apache2-foreground"]