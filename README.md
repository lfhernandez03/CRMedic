# CRM Medic

Sistema de gestión de citas y usuarios médicos construido con Laravel, Filament y Vite.

## Características

- Gestión de usuarios (doctores, administradores, pacientes)
- Gestión de citas médicas
- Panel administrativo con Filament
- Autenticación segura
- Horarios y especialidades dinámicas
- Notificaciones de citas

## Requisitos

- PHP >= 8.1
- Composer
- Node.js y npm
- Base de datos (MySQL, PostgreSQL, etc.)

## Instalación

1. **Clona el repositorio**
   ```sh
   git clone https://github.com/tuusuario/crmedic.git
   cd crmedic
   ```

2. **Instala dependencias de PHP**
   ```sh
   composer install
   ```

3. **Instala dependencias de Node**
   ```sh
   npm install
   ```

4. **Copia el archivo de entorno y configura tus variables**
   ```sh
   cp .env.example .env
   ```
   Edita `.env` y configura tu base de datos y otros parámetros.

5. **Genera la clave de la aplicación**
   ```sh
   php artisan key:generate
   ```

6. **Ejecuta las migraciones**
   ```sh
   php artisan migrate
   ```

7. **Compila los assets**
   ```sh
   npm run build
   ```

8. **Inicia el servidor de desarrollo**
   ```sh
   php artisan serve
   ```

   Accede a [http://localhost:8000](http://localhost:8000) o a la URL que te indique Render si es deploy.

## Acceso al panel administrativo

- **Filament Admin:** `/admin/login`
- **Login estándar:** `/login` (si está habilitado)

## Despliegue en Render

- Asegúrate de que tu servidor escuche en `0.0.0.0`
- Configura la variable `APP_URL` en `.env` con la URL pública de Render
- Agrega tu dominio a `server.allowedHosts` en `vite.config.js` si usas Vite

## Personalización

- Las especialidades médicas se configuran en `app/especialidades.json`
- Los horarios de doctores se gestionan desde el panel de administración

## Soporte

¿Tienes dudas o problemas?  
Abre un issue en GitHub o contacta al equipo de desarrollo.

---

¡Gracias por usar CRM Medic!