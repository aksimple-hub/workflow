# Problema: Cierre de sesión por inactividad en Render free tier

**Fecha:** 11/06/2026  
**Entorno:** Laravel 13, Render free tier, Supabase PostgreSQL

---

## Síntoma

El usuario quedaba desconectado de la aplicación tras ~2 minutos de inactividad, siendo redirigido a la pantalla de login sin haber cerrado sesión manualmente.

---

## Causa raíz: spin-down de Render free tier

Render en su plan gratuito apaga automáticamente el contenedor Docker cuando no recibe tráfico HTTP durante aproximadamente **15 minutos**. Al volver a la app:

1. El navegador hace una petición a la app
2. Render tarda 30-60 segundos en arrancar el contenedor (cold start)
3. Durante ese arranque, la petición falla o no llega a Laravel
4. El navegador interpreta la falta de respuesta como sesión inválida y redirige al login

Aunque las sesiones se almacenan en Supabase PostgreSQL (persistente), el cold start hace que el primer request nunca llegue al servidor correctamente.

---

## Por qué `SESSION_LIFETIME` no era suficiente

`SESSION_LIFETIME=120` (2 horas por defecto en Laravel) define cuánto tiempo dura la sesión en la base de datos, no cuánto tiempo el servidor está encendido. Aunque la sesión exista en PostgreSQL, si el contenedor está apagado el usuario experimenta el cierre.

---

## Solución

### 1. Aumentar SESSION_LIFETIME (prevención parcial)

Se añadió a `render.yaml`:

```yaml
- key: SESSION_LIFETIME
  value: "480"
- key: SESSION_EXPIRE_ON_CLOSE
  value: "false"
```

Esto establece sesiones de **8 horas** que no expiran al cerrar el navegador. Evita cierres por timeout legítimo de sesión.

### 2. UptimeRobot keep-alive (solución principal)

Se configuró un monitor en **UptimeRobot** (servicio gratuito) que hace una petición HTTP al endpoint `/up` de la app cada 5 minutos:

- **URL monitorizada:** `https://workflow-99t6.onrender.com/up`
- **Intervalo:** 5 minutos
- **Tipo:** HTTP

Al recibir tráfico constante cada 5 minutos, Render nunca considera el servicio como inactivo y **nunca apaga el contenedor**. El cold start desaparece por completo.

---

## Resultado

| Problema | Antes | Después |
|---|---|---|
| Cold start | Ocurría tras ~15 min sin uso | Nunca ocurre |
| Cierre de sesión | ~2 min de inactividad real | 8 horas de sesión activa |
| Disponibilidad | Intermitente (spin-down) | Continua (100% uptime) |

---

## Nota sobre el endpoint `/up`

Laravel incluye de serie la ruta `GET /up` (health check) que devuelve HTTP 200 si la app está funcionando. Render ya la usaba para sus propios health checks internos. UptimeRobot reutiliza esta misma ruta como destino del ping periódico.