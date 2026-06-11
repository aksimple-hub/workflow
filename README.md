# WorkFlow — Sistema de Gestión de Servicios de Campo

Aplicación web para la gestión integral de órdenes de trabajo entre administradores, técnicos y clientes. Desarrollada como Trabajo de Fin de Grado (TFG).

## Demo desplegada

🔗 https://workflow-99t6.onrender.com

## Descripción

WorkFlow conecta tres roles en una sola plataforma:

- **Administrador** — crea y asigna órdenes de trabajo, gestiona técnicos y clientes, aprueba cuentas, ve el historial completo.
- **Técnico** — recibe órdenes asignadas, actualiza el estado (en camino, en proceso), cierra servicios con informe, fotos y firma digital, valora al cliente.
- **Cliente** — solicita servicios, hace seguimiento en tiempo real del estado, valora al técnico al finalizar.

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Tailwind CSS v3, Alpine.js v3, Vite |
| Base de datos | Supabase PostgreSQL |
| Almacenamiento | Supabase Storage (S3-compatible) |
| Email | Brevo (SMTP transaccional) |
| Hosting | Render (Docker, free tier) |
| Keep-alive | UptimeRobot (ping cada 5 min) |

## Funcionalidades principales

- Registro y login de clientes y técnicos con validación y aprobación por admin
- Recuperación de contraseña por email
- Creación de solicitudes de servicio con fotos adjuntas (hasta 5, máx. 20 MB/foto)
- Tracker visual de estado por pasos para el cliente
- Panel de agenda para el técnico con sus órdenes activas
- Cierre de orden con informe, materiales utilizados, recomendaciones y firma digital
- Sistema de valoraciones bidireccional (cliente → técnico y técnico → cliente)
- Asignación masiva de órdenes a técnicos
- Notificaciones por email asíncronas (no bloquean la respuesta HTTP)
- Paginación en historial, clientes y técnicos
- Modo oscuro / claro
- Diseño responsive (móvil y escritorio)

## Estructura de roles

```
Admin
 ├── Dashboard con estadísticas en tiempo real
 ├── Historial completo de órdenes con búsqueda y filtros
 ├── Gestión de clientes (crear, ver, editar, dar de baja)
 ├── Gestión de técnicos (crear, ver, editar, dar de baja)
 └── Aprobación de cuentas pendientes

Técnico
 ├── Agenda con órdenes asignadas
 ├── Cambio de estado (en camino → en proceso → cierre)
 ├── Formulario de cierre (informe + materiales + firma)
 └── Historial propio con valoraciones recibidas

Cliente
 ├── Portal de servicios (servicio activo + historial)
 ├── Nueva solicitud con fotos y preferencia de horario
 ├── Tracker de estado en tiempo real
 ├── Cancelación de solicitudes pendientes
 └── Valoración del servicio al finalizar
```

## Instalación local

### Requisitos
- PHP 8.3
- Composer 2
- Node.js 22
- PostgreSQL (o conexión a Supabase)

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/aksimple-hub/WorkFlow.git
cd WorkFlow

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar .env con tus credenciales:
#    DB_*, AWS_* (Supabase Storage), MAIL_* (Brevo)

# 5. Ejecutar migraciones
php artisan migrate

# 6. Compilar assets y arrancar
npm run build
php artisan serve
```

### Variables de entorno necesarias

```env
# Base de datos (Supabase PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=...
DB_SSLMODE=require

# Storage (Supabase Storage)
FILESYSTEM_DISK=supabase
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=eu-west-1
AWS_BUCKET=...
AWS_ENDPOINT=https://<project>.supabase.co/storage/v1/s3
AWS_URL=https://<project>.supabase.co/storage/v1/object/public
AWS_USE_PATH_STYLE_ENDPOINT=true

# Email (Brevo)
MAIL_MAILER=brevo
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
MAIL_FROM_NAME=WorkFlow
```

## Despliegue en Render

El proyecto incluye `render.yaml` con la configuración del servicio. En cada deploy, el Dockerfile ejecuta automáticamente:

```
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
apache2-foreground
```

Para evitar el spin-down del free tier de Render, configurar UptimeRobot para hacer ping a `/up` cada 5 minutos.

## Autor

Akram — TFG 2026