# Bug: Fotos del cliente no se subían ni mostraban

**Fecha:** 11/06/2026  
**Entorno:** Laravel 13, PHP 8.3-Apache, Docker en Render (free tier), Supabase Storage (S3)

---

## Síntoma

El cliente subía fotos al crear una solicitud de servicio. La orden se creaba correctamente (HTTP 302 → dashboard), pero:

- La sección "Fotos del cliente" no aparecía en ninguna vista (cliente, técnico, admin)
- El bucket de Supabase Storage permanecía vacío
- No había ningún error en los logs de Render

---

## Cadena de bugs (3 capas)

### Capa 1 — PHP descartaba los archivos silenciosamente

**Causa:** PHP tiene por defecto `upload_max_filesize=2M`. Las fotos de móvil pesan entre 3 y 8 MB. PHP descartaba los archivos antes de que llegaran a Laravel, sin generar ningún error en los logs de Apache.

**Síntoma clave:** `$request->hasFile('fotos')` devolvía `false`. El POST llegaba con `Content-Length` correcto (megabytes) pero `$_FILES` estaba vacío.

**Fix:** Añadir fichero de configuración PHP en el Dockerfile:

```dockerfile
RUN printf "upload_max_filesize=20M\npost_max_size=60M\n" \
    > /usr/local/etc/php/conf.d/uploads.ini
```

---

### Capa 2 — `document.querySelector('form')` seleccionaba el form equivocado

**Causa:** El componente sidebar (`resources/views/components/sidebar.blade.php`) contiene un `<form method="POST" action="/logout">`. Al usar `document.querySelector('form')` en el script del formulario de solicitud, el navegador devolvía el formulario de logout (primer `<form>` en el DOM), no el de la solicitud.

El listener de `submit` se registraba en el form de logout → nunca se ejecutaba al enviar la solicitud → `syncInput()` nunca se llamaba → el input de fotos llegaba vacío al servidor.

**Síntoma clave:** Log diagnóstico mostraba `hasFile=NO files=[] contentLength=861`. 861 bytes es imposible si hubiera archivos adjuntos.

**Fix:** Añadir `id="solicitud-form"` al form y usar `document.getElementById('solicitud-form')` en lugar de `document.querySelector('form')`.

---

### Capa 3 — `input.files = dataTransfer.files` no funciona de forma fiable en móvil

**Causa:** El JavaScript del formulario usaba la API `DataTransfer` para reconstruir el `FileList` del input al hacer submit. Esta asignación (`input.files = dt.files`) es frágil en ciertos navegadores móviles y no garantiza que los archivos estén disponibles cuando el formulario se serializa.

El flujo fallido era:
1. Usuario selecciona fotos → `change` event → archivos en array `allFiles`
2. `this.value = ''` limpia el input (para permitir re-seleccionar el mismo archivo)
3. Al hacer submit: `syncInput()` llama `input.files = dt.files` — pero en el dispositivo probado esto no funcionaba
4. Formulario se envía con el input vacío

**Fix:** Reemplazar la lógica de submit por `fetch` + `FormData`, añadiendo los archivos directamente desde el array `allFiles` sin depender del input:

```javascript
document.getElementById('solicitud-form').addEventListener('submit', function(e) {
    if (allFiles.length === 0) return; // sin fotos: submit normal

    e.preventDefault();
    const fd = new FormData(this);
    fd.delete('fotos[]');
    allFiles.forEach(f => fd.append('fotos[]', f));

    const btn = this.querySelector('button[type=submit]');
    if (btn) { btn.disabled = true; btn.textContent = 'Enviando...'; }

    fetch(this.action, { method: 'POST', body: fd })
        .then(r => { window.location.href = r.url; })
        .catch(() => { if (btn) { btn.disabled = false; btn.textContent = 'Enviar Solicitud'; } });
});
```

---

### Bug adicional — Validación de Laravel rechazaba fotos grandes

Una vez que los archivos llegaban correctamente a PHP, la regla de validación `max:5120` (5 MB) rechazaba las fotos de móvil.

**Fix:** Subir el límite en el controller y actualizar el texto de la UI:

```php
// Antes
'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',

// Después
'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:20480',
```

---

## Diagnóstico utilizado

Para confirmar en qué capa fallaba el sistema se añadió un log temporal al inicio del método `store()`:

```php
\Log::error('DEBUG store: hasFile=' . ($request->hasFile('fotos') ? 'SI' : 'NO')
    . ' files=' . json_encode(array_keys($request->allFiles()))
    . ' contentLength=' . $request->header('Content-Length'));
```

Resultados:
- `hasFile=NO, contentLength=861` → archivos no llegaban a PHP (capas 1 y 2 sin resolver)
- `hasFile=SI, contentLength=24092554` → sistema funcionando correctamente

---

## Ficheros modificados

| Fichero | Cambio |
|---|---|
| `Dockerfile` | `upload_max_filesize=20M`, `post_max_size=60M` |
| `resources/views/cliente/nueva-solicitud.blade.php` | `id="solicitud-form"`, submit via `fetch`+`FormData`, límite UI a 20 MB |
| `app/Http/Controllers/OrdenTrabajoController.php` | Validación `max:20480` |

---

## Lección principal

Cuando un formulario con archivos llega al servidor con `Content-Length` pequeño y `$_FILES` vacío, hay que revisar en este orden:

1. Límites PHP (`upload_max_filesize`, `post_max_size`) — se configuran en `php.ini` o en un fichero `.ini` en `/usr/local/etc/php/conf.d/`
2. JavaScript que manipula el FileList del input — `input.files = x` es frágil; preferir `fetch` + `FormData`
3. `document.querySelector('form')` en layouts con múltiples formularios — usar siempre IDs explícitos