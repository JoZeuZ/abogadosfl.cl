# Estado Actual del Proyecto - Flores León y Asociados

**Última actualización:** 13 de diciembre de 2025

## Branding Actual

- **Nombre oficial:** Flores León y Asociados - Estudio Jurídico
- **Dominio email:** contacto@abogadosfl.cl
- **Logo principal:** LOGO-abogadosfl.png (con letras)
- **Logo favicon:** logo-abogados-sinletra.png (sin letras, usado en pestaña del navegador)
- **Colores principales:**
  - Azul oscuro: #1f2c3d
  - Dorado/Accent: definido en CSS
  - Tema del navegador: #1f2c3d

## Sistema de Formulario de Contacto

### Estado: ✅ FUNCIONANDO CORRECTAMENTE

### Configuración Técnica
- **Frontend:** Astro v5.16.5 en localhost:4321
- **Backend:** PHP 8.2.12 en localhost:8080
- **reCAPTCHA v3:** Site key 6LceRAksAAAAAJrvVA017oaAMaMJnPR7wTxXDYY3
- **SMTP:** mail.abogadosfl.cl:465 (SMTPS)
  - Usuario: contacto@abogadosfl.cl
  - Contraseña: Patolo1234
- **CORS:** Configurado para permitir localhost:4321 → localhost:8080

### Validaciones del Formulario
- **Nombre:** 2-100 caracteres
- **Email:** Formato válido
- **Teléfono:** Formato chileno (+56 o 9 dígitos)
- **Mensaje:** 10-1000 caracteres
- **Checkbox privacidad:** Requerido (name="privacidad")

### Archivos Clave
1. **src/components/Contact.astro**
   - Formulario con reCAPTCHA v3
   - Checkbox de privacidad: 32x32px, fondo azul (#1f2c3d), tick dorado
   - Gap entre checkbox y texto: 2.5rem
   - Fetch a: http://localhost:8080/enviar_correo.php
   - Manejo de respuestas JSON con mensajes de error específicos

2. **enviar_correo.php**
   - Retorna respuestas JSON: {success: bool, error/message: string}
   - Headers CORS configurados
   - Logging completo en cada paso
   - Validación de campos requeridos: nombre, email, telefono, mensaje, privacidad
   - Backup a CSV: logs/mensajes.csv
   - Envío de email vía PHPMailer

3. **config.php**
   - Credenciales SMTP
   - reCAPTCHA secret key
   - from_name: 'Flores León y Asociados'
   - Archivo en .gitignore (NO COMMITEAR)

### Logs
- **PHP errors:** logs/php_errors.log
- **Mensajes recibidos:** logs/mensajes.csv
- Los directorios de logs se crean automáticamente si no existen

## Archivos Actualizados con Nuevo Branding

### Layouts
- src/layouts/Layout.astro
  - Meta description: "Flores León y Asociados - Estudio Jurídico"
  - Keywords incluyen: "Flores León"
  - Schema.org: name="Flores León y Asociados", type="LegalService"
  - Favicon: /images/logo-abogados-sinletra.png

### Páginas
- src/pages/index.astro
  - Title: "Flores León y Asociados - Estudio Jurídico Profesional"
  - Description actualizada

### Componentes
- src/components/Header.astro
- src/components/Footer.astro
- src/components/About.astro
- src/components/Team.astro
- src/components/Map.astro
- src/components/Testimonials.astro
- src/components/Contact.astro

### Archivos PHP
- config.php: from_name actualizado
- enviar_correo.php: mensajes con nuevo nombre

### Páginas HTML
- public/gracias.html: título y contenido actualizado

### JavaScript
- src/components/map-client.js: popup del mapa actualizado
- public/js/map-client.js: sincronizado

### Políticas
- src/pages/politica-cookies.astro
- src/pages/politica-privacidad.astro

## Favicon Actual

**Archivo:** public/images/logo-abogados-sinletra.png
- **Tipo:** PNG
- **Uso:** Icono de pestaña del navegador (favicon)
- **Características:** Logo de balanza de justicia con laureles, sin texto
- **Configuración en Layout.astro:**
  ```html
  <link rel="icon" type="image/png" href="/images/logo-abogados-sinletra.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="/images/logo-abogados-sinletra.png" />
  ```

## Notas Importantes

1. **Checkbox de Privacidad:**
   - El campo se llama "privacidad" en HTML y PHP (NO "politicas")
   - Estilo personalizado con z-index: 10 para evitar superposición
   - Color del tick: var(--color-accent)

2. **Errores Comunes:**
   - Siempre usar http://localhost:8080 completo en fetch (no rutas relativas)
   - Los navegadores cachean favicons agresivamente: usar Ctrl+Shift+R

3. **Servidor de Desarrollo:**
   - Astro: `npm run dev` (puerto 4321)
   - PHP: `php -S localhost:8080` (en directorio raíz)

4. **reCAPTCHA:**
   - Script cargado en Layout.astro
   - Token generado en Contact.astro antes del submit
   - Validado en enviar_correo.php con secret key
