# Documentación del Sistema de Correo — WorkFlow

## 1. Contexto y objetivo

La aplicación WorkFlow necesita enviar correos electrónicos automáticos a usuarios (clientes, técnicos y administradores) cuando ocurren eventos importantes: registros, aprobaciones, asignaciones de órdenes, etc.

En entorno local se usaba **Mailpit** (servidor de correo falso para desarrollo). En producción (Render) esto no funciona, por lo que se necesitó un servicio de correo real.

---

## 2. Servicio usado: Brevo

**Brevo** (antes Sendinblue) es un servicio de email transaccional gratuito.

- Web: https://www.brevo.com
- Plan gratuito: 300 correos/día, sin límite mensual
- No requiere dominio propio (funciona con Gmail u otros)

### Cuenta configurada
- Email de la cuenta: `akramtrabajo@gmail.com`
- Remitente verificado: `akramtrabajo@gmail.com` (estado: Verificado)

---

## 3. Problemas encontrados y soluciones

### Problema 1: Credenciales SMTP intercambiadas
Las variables `MAIL_USERNAME` y `MAIL_PASSWORD` estaban invertidas en Render.

**Solución:** Corregir los valores:
- `MAIL_USERNAME` = login SMTP de Brevo (`ae4564001@smtp-brevo.com`)
- `MAIL_PASSWORD` = contraseña SMTP de Brevo (`UQxACWB1HhOTIbZv`)

---

### Problema 2: Render bloquea puertos SMTP salientes
Render (plan gratuito) bloquea las conexiones SMTP salientes en los puertos **587** (TLS) y **465** (SSL). Todos los intentos de conexión resultaban en `Connection timed out`.

**Solución:** Abandonar SMTP y usar la **API HTTP de Brevo** (puerto 443, nunca bloqueado).

Paquetes instalados:
```bash
composer require symfony/brevo-mailer symfony/http-client
```

Transporte registrado en `app/Providers/AppServiceProvider.php`:
```php
Mail::extend('brevo', function (array $config) {
    return new BrevoApiTransport($config['key']);
});
```

Mailer añadido en `config/mail.php`:
```php
'brevo' => [
    'transport' => 'brevo',
    'key' => env('BREVO_API_KEY'),
],
```

---

### Problema 3: Error "Undefined array key name"
El array `mail.to` en `config/mail.php` solo tenía la clave `address` pero Laravel también necesita la clave `name`.

**Solución:** Añadir ambas claves:
```php
'to' => [
    'address' => env('MAIL_TO_ADDRESS', null),
    'name'    => env('MAIL_TO_NAME', null),
],
```

---

### Problema 4: Restricción de IP en Brevo
Brevo bloqueaba las llamadas a la API desde las IPs de Render por ser nuevas/desconocidas.

**Solución:** En Brevo → Settings → Remitentes, dominio, IP → desactivar la restricción de IP para claves API.

---

## 4. Variables de entorno en Render

| Variable | Valor | Descripción |
|---|---|---|
| `MAIL_MAILER` | `brevo` | Usar el transporte Brevo API |
| `MAIL_FROM_ADDRESS` | `akramtrabajo@gmail.com` | Dirección de envío |
| `MAIL_FROM_NAME` | `WorkFlow` | Nombre del remitente |
| `BREVO_API_KEY` | `xkeysib-...` | API Key v3 de Brevo (Settings → SMTP y API → API Keys) |
| `MAIL_TO_ADDRESS` | `akramtrabajo@gmail.com` | Redirige TODOS los correos a esta dirección (modo demo) |
| `MAIL_TO_NAME` | *(vacío)* | Nombre del destinatario global (opcional) |

> **Nota:** Las variables `MAIL_HOST`, `MAIL_PORT`, `MAIL_ENCRYPTION`, `MAIL_USERNAME`, `MAIL_PASSWORD` y `MAIL_SCHEME` ya no son necesarias con el transporte API, pero se pueden dejar en Render sin efecto.

---

## 5. Cómo funciona la redirección global (modo demo)

Para la presentación del TFG, **todos los correos se redirigen a `akramtrabajo@gmail.com`** sin importar el destinatario real. Esto permite al tribunal ver que el sistema de correo funciona sin necesitar direcciones de email reales en la base de datos.

El mecanismo es nativo de Laravel: cuando `MAIL_TO_ADDRESS` está definido, Laravel llama internamente a `Mail::alwaysTo()` y sobreescribe el destinatario de todos los mensajes salientes.

---

## 6. Notificaciones implementadas

| Evento | Quién recibe | Clase |
|---|---|---|
| Cliente se registra | El propio cliente | `ClienteRegistrado` |
| Cliente se registra | Todos los admins | `NuevoClienteRegistrado` |
| Técnico se registra | El propio técnico | `TecnicoRegistrado` |
| Técnico se registra | Todos los admins | `NuevoTecnicoRegistrado` |
| Admin aprueba cliente | El cliente aprobado | `ClienteAprobado` |
| Admin aprueba técnico | El técnico aprobado | `TecnicoAprobado` |
| Admin asigna orden a técnico | El técnico asignado | `AsignacionOrdenTecnico` |
| Técnico cancela orden | Todos los admins | `OrdenCanceladaAdmin` |
| Cliente cancela orden | Todos los admins | `OrdenCanceladaAdmin` |
| Técnico aplaza orden | Todos los admins | `OrdenAplazadaAdmin` |

---

## 7. Pasar a producción real (emails reales por usuario)

Cuando se quiera que cada usuario reciba el correo en su propia dirección (no en la del admin), hay que:

### Paso 1: Eliminar la redirección global
En Render → Environment, **eliminar** la variable `MAIL_TO_ADDRESS`.

Sin esa variable, Laravel envía cada correo al destinatario real (el email del usuario en la base de datos).

### Paso 2: Asegurarse de que los usuarios tienen emails reales
Cada usuario en la tabla `users` debe tener su email real en el campo `email`. Las notificaciones ya usan ese campo automáticamente — no hay que cambiar código.

### Paso 3: Verificar el remitente en Brevo
El email configurado en `MAIL_FROM_ADDRESS` debe estar verificado en Brevo como remitente (Settings → Remitentes, dominio, IP).

Actualmente está verificado: `akramtrabajo@gmail.com`.

### Paso 4 (opcional): Usar un dominio propio
Para mejor entregabilidad y evitar que los correos vayan a spam, lo ideal es:
1. Registrar un dominio (ej. `workflow-app.com`)
2. Verificarlo en Brevo añadiendo registros DNS (SPF, DKIM)
3. Cambiar `MAIL_FROM_ADDRESS` a `noreply@workflow-app.com`

Sin dominio propio los correos funcionan pero pueden llegar a spam en algunos clientes de correo.

---

## 8. Obtener la API Key de Brevo

1. Ir a [app.brevo.com](https://app.brevo.com)
2. Settings → SMTP y API → pestaña **API Keys**
3. Copiar la clave existente o crear una nueva
4. La clave tiene el formato: `xkeysib-xxxxxxxxxxxxxxxx...`
5. Pegar el valor en la variable `BREVO_API_KEY` de Render