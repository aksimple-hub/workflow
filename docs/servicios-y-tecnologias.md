# Servicios y tecnologías del proyecto WorkFlow

## Resumen rápido

| Categoría | Servicio / Tecnología |
|---|---|
| Hosting | Render (free tier) |
| Base de datos | Supabase PostgreSQL |
| Almacenamiento de archivos | Supabase Storage |
| Correo electrónico | Brevo (SMTP) |
| Keep-alive | UptimeRobot |
| Framework backend | Laravel 13 |
| Runtime | PHP 8.3 + Apache |
| Frontend CSS | Tailwind CSS v3 |
| Frontend JS | Alpine.js v3 |
| Build tool | Vite |

---

## Hosting — Render

**URL:** [render.com](https://render.com)  
**Plan:** Free tier

Render ejecuta la aplicación como un **contenedor Docker**. El archivo `render.yaml` define el servicio y las variables de entorno no secretas. Las variables secretas (`APP_KEY`, `DB_PASSWORD`, claves de Supabase, etc.) se configuran manualmente en el dashboard de Render.

**Particularidades del free tier:**
- El servidor se apaga tras ~15 minutos de inactividad y tarda ~30-60 segundos en volver a arrancar (cold start).
- Para evitar esto se usa UptimeRobot (ver más abajo).
- El sistema de ficheros es efímero: cualquier archivo guardado localmente desaparece en cada redeploy. Por eso todos los uploads van a Supabase Storage, no al disco local.

**Arranque del contenedor** (`Dockerfile` CMD):
```
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
apache2-foreground
```

---

## Base de datos — Supabase PostgreSQL

**URL:** [supabase.com](https://supabase.com)  
**Plan:** Free tier

Supabase proporciona una base de datos PostgreSQL gestionada. La conexión usa SSL obligatorio (`DB_SSLMODE=require`).

Variables de entorno necesarias en Render:
```
DB_CONNECTION=pgsql
DB_HOST=<host-supabase>
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=<password>
DB_SSLMODE=require
```

**Importante — índices en PostgreSQL:**  
A diferencia de MySQL, PostgreSQL **no crea automáticamente índices** en columnas de foreign key. Se añadieron manualmente en la migración `add_performance_indexes` para las columnas más consultadas: `estado`, `cliente_id`, `usuario_id`, `created_at` en `orden_trabajos` y `role`, `cliente_id` en `users`.

---

## Almacenamiento de archivos — Supabase Storage

**URL:** [supabase.com](https://supabase.com) → Storage  
**Plan:** Free tier (1 GB incluido)

Supabase Storage es compatible con la API de S3 de Amazon. Laravel lo usa a través del paquete `league/flysystem-aws-s3-v3`.

El disco por defecto está configurado como `supabase` (`FILESYSTEM_DISK=supabase`), por lo que todas las llamadas a `Storage::put()`, `Storage::store()`, etc. van directamente a Supabase sin especificar el disco.

**Archivos almacenados:**
- Fotos adjuntas a órdenes de trabajo → bucket `fotos-ordenes`
- Fotos de perfil de usuarios → bucket `fotos-perfil`
- CVs de técnicos → bucket `cvs`
- Firmas de cliente → `firmas/`

Variables de entorno necesarias en Render:
```
AWS_ACCESS_KEY_ID=<supabase-storage-key>
AWS_SECRET_ACCESS_KEY=<supabase-storage-secret>
AWS_DEFAULT_REGION=eu-west-1
AWS_BUCKET=<nombre-bucket>
AWS_ENDPOINT=https://<project-id>.supabase.co/storage/v1/s3
AWS_URL=https://<project-id>.supabase.co/storage/v1/object/public
AWS_USE_PATH_STYLE_ENDPOINT=true
```

---

## Correo electrónico — Brevo

**URL:** [brevo.com](https://brevo.com)  
**Plan:** Free tier (300 emails/día)

Brevo (antes SendinBlue) gestiona el envío de emails transaccionales: registro, aprobación de cuenta, notificaciones de órdenes, recuperación de contraseña.

Se usa el paquete `symfony/brevo-mailer` junto con `symfony/http-client`. La configuración en Laravel usa `MAIL_MAILER=brevo`.

Variables de entorno necesarias en Render:
```
MAIL_MAILER=brevo
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=<tu-email-brevo>
MAIL_PASSWORD=<smtp-key-brevo>
MAIL_FROM_ADDRESS=<email-remitente>
MAIL_FROM_NAME=WorkFlow
```

**Optimización:** Todos los envíos de email usan `app()->terminating()` para ejecutarse después de que la respuesta HTTP ya está enviada al navegador. Esto evita que el usuario espere los 2-5 segundos que tarda cada llamada SMTP.

---

## Keep-alive — UptimeRobot

**URL:** [uptimerobot.com](https://uptimerobot.com)  
**Plan:** Free tier

UptimeRobot envía una petición HTTP GET a la ruta `/up` de la aplicación cada **5 minutos**. Esto impide que Render apague el contenedor por inactividad.

Configuración del monitor:
- Tipo: HTTP(s)
- URL: `https://<tu-app>.onrender.com/up`
- Intervalo: 5 minutos

La ruta `/up` es el health check de Laravel, configurado también en `render.yaml` como `healthCheckPath: /up`.

---

## Framework backend — Laravel 13

**Versión:** `laravel/framework ^13.0`  
**Auth:** Laravel Breeze (scaffolding de autenticación)

Componentes de Laravel utilizados:
- **Eloquent ORM** — modelos y relaciones
- **Notifications** — sistema de notificaciones por email
- **Storage** (Flysystem) — gestión de archivos
- **Migrations** — control de esquema de base de datos
- **Session driver: database** — sesiones guardadas en PostgreSQL
- **Cache driver: database** — caché en PostgreSQL
- **Queue driver: database** — colas en PostgreSQL (preparadas pero emails usan `terminating`)
- **Artisan** — CLI, caching de config/rutas/vistas en producción

---

## Runtime — PHP 8.3 + Apache

El contenedor usa la imagen oficial `php:8.3-apache`.

Extensiones PHP instaladas:
- `pdo`, `pdo_pgsql` — conexión a PostgreSQL
- `zip` — descompresión de paquetes
- `opcache` — caché de bytecode PHP (256 MB, revalidación desactivada en producción)

Configuración personalizada:
- `upload_max_filesize=20M` — fotos de hasta 20 MB
- `post_max_size=60M` — para peticiones con múltiples fotos

Apache tiene habilitados los módulos `rewrite` y `headers`, necesarios para las rutas de Laravel y las cabeceras CORS/cache.

---

## Frontend — Tailwind CSS + Alpine.js + Vite

| Paquete | Versión | Uso |
|---|---|---|
| `tailwindcss` | ^3.1 | Estilos |
| `@tailwindcss/forms` | ^0.5 | Reset de estilos de formularios |
| `alpinejs` | ^3.4 | Interactividad (modales, toggles, lightbox) |
| `vite` | ^8.0 | Bundler y compilación de assets |
| `laravel-vite-plugin` | ^3.0 | Integración Vite ↔ Laravel |
| `axios` | ^1.11 | Peticiones HTTP desde JS |
| `node.js` | 22.x | Compilación de assets en Docker |

El build (`npm run build`) se ejecuta dentro del Dockerfile durante el deploy, generando los assets en `public/build/`.

---

## Variables de entorno — resumen completo

Las siguientes variables deben estar configuradas en el dashboard de Render (las marcadas con `render.yaml` están en el archivo, el resto son secretas y van solo en Render):

```
# App
APP_KEY=base64:...               # generada con php artisan key:generate
APP_ENV=production               # render.yaml
APP_DEBUG=false                  # render.yaml

# Base de datos (Supabase PostgreSQL)
DB_CONNECTION=pgsql              # render.yaml
DB_HOST=...
DB_PORT=5432                     # render.yaml
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=...
DB_SSLMODE=require               # render.yaml

# Storage (Supabase Storage / S3)
FILESYSTEM_DISK=supabase         # render.yaml
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=eu-west-1
AWS_BUCKET=...
AWS_ENDPOINT=...
AWS_URL=...
AWS_USE_PATH_STYLE_ENDPOINT=true

# Email (Brevo)
MAIL_MAILER=brevo
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
MAIL_FROM_NAME=WorkFlow

# Sesión / Caché / Cola
SESSION_DRIVER=database          # render.yaml
SESSION_LIFETIME=480             # render.yaml (8 horas)
SESSION_EXPIRE_ON_CLOSE=false    # render.yaml
CACHE_STORE=database             # render.yaml
QUEUE_CONNECTION=database        # render.yaml

# Logs
LOG_CHANNEL=stderr               # render.yaml
LOG_LEVEL=error                  # render.yaml
```