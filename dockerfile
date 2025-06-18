FROM laravelsail/php82-composer:latest

# Copia el código
WORKDIR /var/www/html
COPY . .

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Instala dependencias Node y compila assets
RUN npm install && npm run build

# Permisos Laravel
RUN chmod -R 775 storage bootstrap/cache

# Variables necesarias
ENV APP_ENV=production
ENV APP_KEY=base64:rYonqXyOw5iccqeB4K0S0nGFoJxqfw/5sFuB3uZBeTM=
ENV APP_URL=https://crmedic-os8k.onrender.com

# Servidor web
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]