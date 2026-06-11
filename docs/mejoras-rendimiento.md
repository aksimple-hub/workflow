# Mejoras de rendimiento: tiempos de carga

**Fecha:** 11/06/2026  
**Entorno:** Laravel 13, PHP 8.3-Apache, Docker en Render (free tier), Supabase PostgreSQL

---

## Síntoma

La aplicación presentaba tiempos de carga lentos en las vistas más usadas:
- Navegación general entre vistas
- Registro de técnico o cliente (8-10 segundos)
- Dashboard admin
- Historial de órdenes
- Listas de clientes y técnicos

---

## Causas identificadas y soluciones

### 1. Emails síncronos bloqueando la respuesta HTTP

**Causa:** Varias acciones enviaban emails por SMTP antes de devolver la respuesta al navegador. Cada llamada SMTP tarda entre 2 y 5 segundos. El registro de técnico enviaba 2 emails (al técnico y al admin), bloqueando la respuesta durante 4-10 segundos.

**Acciones afectadas:**
- Registro de cliente → 2 emails
- Registro de técnico → 2 emails
- Asignación de técnico a orden → 1 email
- Cancelación de orden → 1 email
- Aplazamiento de orden → 1 email
- Reagendación de orden → 1 email
- Aprobación de técnico/cliente por admin → 1 email

**Fix:** Mover todos los envíos de email a `app()->terminating()`. Los callbacks registrados en `terminating` se ejecutan en la fase `terminate` del ciclo de vida de Laravel, después de que la respuesta ya está formada y en el buffer de Apache. El usuario recibe la respuesta de forma inmediata.

```php
// Antes — bloquea la respuesta
try { $user->notify(new TecnicoRegistrado()); } catch (\Throwable $e) { ... }

// Después — se ejecuta tras enviar la respuesta
app()->terminating(function() use ($user) {
    try { $user->notify(new TecnicoRegistrado()); } catch (\Throwable $e) { ... }
});
```

**Ficheros modificados:**
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/OrdenTrabajoController.php`
- `app/Http/Controllers/DashboardController.php`

---

### 2. Índices de base de datos ausentes

**Causa:** En PostgreSQL, las foreign keys **no crean índices automáticamente** en las columnas referenciantes. Las columnas `cliente_id` y `usuario_id` de `orden_trabajos` tenían constraints de foreign key pero ningún índice, por lo que cada consulta con `WHERE cliente_id = ?` o `WHERE usuario_id = ?` realizaba un full table scan.

Lo mismo ocurría con `estado` en `orden_trabajos` (filtrado en prácticamente todas las vistas) y `role` y `cliente_id` en `users`.

**Columnas indexadas:**

| Tabla | Columna | Razón |
|---|---|---|
| `orden_trabajos` | `estado` | Filtrado en todas las consultas |
| `orden_trabajos` | `cliente_id` | FK sin índice en PostgreSQL |
| `orden_trabajos` | `usuario_id` | FK sin índice en PostgreSQL |
| `orden_trabajos` | `created_at` | Ordenación `->latest()` |
| `users` | `role` | `WHERE role = 'tecnico'` / `'admin'` |
| `users` | `cliente_id` | `WHERE cliente_id = ?` |

**Fix:** Migración `2026_06_11_015743_add_performance_indexes.php` que añade los 6 índices.

---

### 3. Dashboard admin — 5 queries COUNT separadas

**Causa:** Las estadísticas del dashboard admin ejecutaban 5 consultas independientes a la base de datos, una por cada estado.

```php
// Antes — 5 viajes a Supabase
$stats = [
    'total'      => OrdenTrabajo::count(),
    'pendientes' => OrdenTrabajo::where('estado', 'pendiente')->count(),
    'en_curso'   => OrdenTrabajo::whereIn('estado', [...])->count(),
    'finalizadas'=> OrdenTrabajo::where('estado', 'finalizada')->count(),
    'canceladas' => OrdenTrabajo::where('estado', 'cancelada')->count(),
];
```

**Fix:** Una sola query con agregados condicionales:

```php
// Después — 1 viaje a Supabase
$counts = OrdenTrabajo::selectRaw("
    count(*) as total,
    count(case when estado = 'pendiente' then 1 end) as pendientes,
    count(case when estado in ('en_camino','en_proceso','asignada') then 1 end) as en_curso,
    count(case when estado = 'finalizada' then 1 end) as finalizadas,
    count(case when estado = 'cancelada' then 1 end) as canceladas
")->first();
```

---

### 4. Historial, clientes y técnicos sin paginación

**Causa:** Las vistas de historial de órdenes, lista de clientes y lista de técnicos cargaban todos los registros de la base de datos sin ningún límite.

```php
// Antes — carga todo
OrdenTrabajo::with(['cliente', 'tecnico'])->orderBy('updated_at', 'desc')->get();
Cliente::all();
User::where('role', 'tecnico')->get();
```

**Fix:** Paginación de 20 registros por página en las tres vistas. Se añadieron los links de paginación (`{{ $ordenes->links() }}`) en cada vista.

---

## Resultado esperado

| Vista | Antes | Después |
|---|---|---|
| Registro técnico/cliente | 8-10 seg | < 1 seg |
| Dashboard admin | 5+ queries al cargar | 2 queries |
| Historial / Clientes / Técnicos | Carga todos los registros | Carga 20 por página |
| Todas las vistas con filtros por estado/usuario/cliente | Full table scan | Index scan |

---

## Nota sobre la causa raíz de la lentitud general

Con Supabase PostgreSQL como base de datos externa (diferente servidor que Render), cada query tiene una latencia de red de ~50-100ms. Sin índices, queries que deberían tardar <1ms realizan full table scans que escalan con el volumen de datos. Los índices son la mejora más impactante para cualquier despliegue con base de datos remota.