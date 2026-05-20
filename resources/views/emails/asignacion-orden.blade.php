<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Orden Asignada</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F7FA; color: #374151; }
        .wrapper { max-width: 600px; margin: 32px auto; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }

        /* Header brand-dark */
        .header { background: #1E3A5F; padding: 36px 40px; text-align: center; }
        .header .logo { font-size: 22px; font-weight: 700; color: #10B981; letter-spacing: 0.5px; }
        .header h1 { font-size: 24px; font-weight: 600; color: #ffffff; margin-top: 12px; line-height: 1.3; }
        .header .badge { display: inline-block; margin-top: 12px; padding: 4px 16px; background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.4); border-radius: 99px; font-size: 12px; font-weight: 600; color: #10B981; letter-spacing: 0.5px; text-transform: uppercase; }

        /* Body */
        .body { background: #ffffff; padding: 36px 40px; }
        .greeting { font-size: 16px; font-weight: 600; color: #1E3A5F; margin-bottom: 12px; }
        .intro { font-size: 14px; color: #6B7280; line-height: 1.7; margin-bottom: 28px; }

        /* Orden card */
        .orden-card { background: #F5F7FA; border-radius: 12px; padding: 24px; margin-bottom: 28px; border-left: 4px solid #10B981; }
        .orden-id { font-size: 11px; font-weight: 700; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px; }
        .orden-titulo { font-size: 18px; font-weight: 700; color: #1E3A5F; margin-bottom: 16px; }
        .orden-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .orden-field label { font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px; }
        .orden-field span { font-size: 14px; font-weight: 500; color: #1E3A5F; }
        .prioridad-alta  { color: #D97706; }
        .prioridad-media { color: #1D4ED8; }
        .prioridad-baja  { color: #6B7280; }

        /* CTA button brand-green */
        .cta-wrap { text-align: center; margin-bottom: 28px; }
        .cta { display: inline-block; background: #10B981; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 32px; border-radius: 12px; box-shadow: 0 2px 8px rgba(16,185,129,0.35); }
        .cta:hover { background: #059669; }

        .divider { border: none; border-top: 1px solid #E5E7EB; margin: 0 0 24px; }
        .note { font-size: 13px; color: #9CA3AF; line-height: 1.6; text-align: center; }

        /* Footer */
        .footer { background: #1E3A5F; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: rgba(255,255,255,0.45); line-height: 1.8; }
        .footer strong { color: rgba(255,255,255,0.75); }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="logo">WorkFlow</div>
        <h1>Se te ha asignado<br>una nueva orden de trabajo</h1>
        <span class="badge">Nueva asignación</span>
    </div>

    <!-- Body -->
    <div class="body">
        <p class="greeting">Hola, {{ $tecnico->name }}</p>
        <p class="intro">
            El equipo de administración te ha asignado la siguiente avería.
            Revisa los detalles y accede a tu agenda para organizar tu jornada.
        </p>

        <!-- Orden card -->
        <div class="orden-card">
            <div class="orden-id">#OT-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="orden-titulo">{{ $orden->titulo }}</div>

            <div class="orden-grid">
                <div class="orden-field">
                    <label>Cliente</label>
                    <span>{{ $orden->cliente->nombre ?? 'N/A' }}</span>
                </div>
                <div class="orden-field">
                    <label>Prioridad</label>
                    <span class="prioridad-{{ $orden->prioridad }}">{{ ucfirst($orden->prioridad) }}</span>
                </div>
                <div class="orden-field">
                    <label>Dirección</label>
                    <span>{{ $orden->cliente->direccion ?? 'N/A' }}</span>
                </div>
                <div class="orden-field">
                    <label>Fecha de asignación</label>
                    <span>{{ now()->format('d/m/Y H:i') }}</span>
                </div>
                @if($orden->descripcion)
                <div class="orden-field" style="grid-column: span 2;">
                    <label>Descripción</label>
                    <span>{{ Str::limit($orden->descripcion, 120) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- CTA -->
        <div class="cta-wrap">
            <a href="{{ url('/dashboard') }}" class="cta">Ver mi Agenda</a>
        </div>

        <hr class="divider">
        <p class="note">
            Si tienes dudas sobre esta asignación, contacta con el administrador.<br>
            Este correo fue generado automáticamente, por favor no respondas a él.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>WorkFlow — Gestión de Rutas</strong><br>
            © {{ date('Y') }} Todos los derechos reservados.
        </p>
    </div>

</div>
</body>
</html>
