# Imagen base con PHP, Composer, Node, Nginx y supervisord
FROM webdevops/php-nginx:8.2

# Establece el directorio de trabajo
WORKDIR /app

# Copia todos los archivos del proyecto al contenedor
COPY . .

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Instala dependencias de Node y compila assets con Vite
RUN npm install && npm run build

# Asigna permisos para Laravel (storage y cache)
RUN chmod -R 775 storage bootstrap/cache

# Copia el archivo .env.production si existe, como .env
# (Opcional, si usas Render secrets para generar tu .env)
# COPY .env.production .env

# Establece la APP_ENV y APP_URL si Render no lo hace por variables
ENV APP_ENV=production
ENV APP_URL=https://crmedic-os8k.onrender.com

# Expone el puerto para que Render lo use
EXPOSE 80

# Comando para iniciar Laravel con php-fpm + nginx
CMD ["/opt/docker/bin/entrypoint.sh"]
