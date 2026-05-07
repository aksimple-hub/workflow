# ESPECIFICACIONES DE DISEÑO - WORKFLOW (DESKTOP EDITION)
## Sistema de Gestión de Servicios de Campo


**Versión:** 2.0 (Desktop Only)  
**Fecha:** 6 de Mayo 2026  
**Viewport Objetivo:** 1920x1080 (Ratio 16:9)


---


## 📋 ÍNDICE DESKTOP


1. [Especificaciones Globales](#especificaciones-globales)
2. [Pantallas Desktop](#pantallas-desktop)
   - [Login Desktop](#1-login-desktop)
   - [Dashboard Admin Desktop](#2-dashboard-admin-desktop)
   - [Asignación Rutas Desktop](#3-asignación-rutas-desktop)
   - [Agenda Técnico Desktop](#4-agenda-técnico-desktop)
   - [Detalle Avería Desktop](#5-detalle-avería-desktop)
   - [Formulario Cierre Desktop](#6-formulario-cierre-desktop)
   - [Solicitudes Cliente Desktop](#7-solicitudes-cliente-desktop)
   - [Nueva Solicitud Cliente Desktop](#8-solicitudes-cliente-desktop)
3. [Componentes Compartidos](#componentes-compartidos)


---


# ESPECIFICACIONES GLOBALES


## 🎨 PALETA DE COLORES (Tailwind Config)


### Colores Principales
- **Azul Marino Oscuro (`#1E3A5F`)**: Barras de navegación, títulos principales, sidebar.
- **Verde Esmeralda (`#10B981`)**: Botones de acción principal, estados de éxito.
- **Azul Marino Medio (`#2C5282`)**: Degradados y acentos.


### Colores de Estado e Interacción
- **Éxito**: `#10B981`.
- **Prioridad Alta**: Fondo `#FEF3C7`, Texto `#D97706`.
- **Prioridad Media**: Fondo `#DBEAFE`, Texto `#1D4ED8`.
- **Hover Botones**: `#059669`.


## 🔤 TIPOGRAFÍA Y ESCALA (Desktop)
- **H1 Grande (Login)**: 48px (3rem) / weight 500 / `text-5xl`.
- **H1 (Títulos)**: 40px (2.5rem) / weight 500 / `text-4xl`.
- **Body Standard**: 16px (1rem) / `text-base`.
- **Small (Labels)**: 14px (0.875rem) / `text-sm`.


## 📐 ESPACIADO Y COMPONENTES
- **Gaps**: `gap-4` (16px) entre componentes, `gap-6` (24px) entre secciones.
- **Botón Principal**: Padding 16px 24px, Radio 12px, Sombra `0px 2px 8px rgba(16, 185, 129, 0.25)`.
- **Inputs**: Fondo `#F5F7FA`, Radio 12px, Border 2px solid (Verde en Focus).
- **Tarjetas (Cards)**: Fondo blanco, Radio 12px, Sombra `0px 1px 3px rgba(0, 0, 0, 0.05)`.


---


# PANTALLAS DESKTOP


## 1. LOGIN DESKTOP (Split Screen)
- **Estructura:** 60% panel visual (izq) + 40% formulario (der).
- **Panel Izquierdo:** Degradado `#1E3A5F` a `#2C5282`. Incluye 4 Feature Cards con `backdrop-blur`.
- **Formulario:** Centrado, max-width `28rem`, bordes `rounded-2xl` (16px).


## 2. DASHBOARD ADMIN DESKTOP
- **Layout:** Sidebar (256px) + Header + Contenido.
- **Stats Grid:** 4 columnas (`grid-cols-4`). Valores en `text-3xl`.
- **Tabla:** Diseño Full Width con bordes redondeados y sombras `shadow-sm`.


## 3. ASIGNACIÓN RUTAS DESKTOP
- **Split View:** 50% Mapa + 50% Panel de gestión.
- **Service Cards:** Bordes de 2px, estados hover con color `#10B981`.


## 4. AGENDA TÉCNICO DESKTOP
- **Week View:** Grid de 5 días en la parte superior.
- **Service List:** Tarjetas horizontales con hora destacada a la izquierda (`text-3xl`).


## 5. FORMULARIO CIERRE DESKTOP
- **Estructura:** Dos columnas. Izquierda: Info readonly. Derecha: Inputs de cierre.
- **Cierre:** Textarea de altura `h-48` y zona de firma/upload `dashed border`.


## 6. SOLICITUDES CLIENTE DESKTOP
- **Stats:** 3 tarjetas superiores (Total, Proceso, Finalizado).
- **Historial:** Tabla completa con badges de estado circulares.


## 7. NUEVA SOLICITUD CLIENTE DESKTOP
- **Form Layout:** Grid de 2 columnas centrado (`max-w-4xl`).
- **Inputs:** Fondo `#F5F7FA`, bordes `rounded-xl`, focus en verde `#10B981`.


---


# COMPONENTES COMPARTIDOS


## SIDEBAR (Desktop Standard)
- **Ancho:** 256px (`w-64`).
- **Colores:** Fondo `#1E3A5F`, items activos en `#10B981`.
- **Comportamiento:** Fijo (Sticky) a la izquierda, altura `100vh`.


---
