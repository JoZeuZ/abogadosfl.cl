# Configuración y Despliegue

La configuración para el entorno de producción es crucial para el funcionamiento del formulario de contacto.

- **Variables de Entorno:** El script `enviar_correo.php` depende de variables de entorno para las credenciales SMTP y la clave secreta de reCAPTCHA. Estas deben ser configuradas en el servidor de hosting y **NO** deben ser guardadas en el código fuente. Las variables necesarias son:
  - `SMTP_HOST`
  - `SMTP_PORT`
  - `SMTP_USER`
  - `SMTP_PASS`
  - `SMTP_FROM_EMAIL`
  - `SMTP_FROM_NAME`
  - `SMTP_TO_EMAIL`
  - `RECAPTCHA_SECRET_KEY`

- **reCAPTCHA:** La clave del sitio de reCAPTCHA debe ser insertada directamente en el componente `src/components/Contact.astro`.

- **Proceso de Despliegue:**
  1. Ejecutar `npm run build` para generar los archivos estáticos.
  2. Subir el contenido del directorio `dist/` al servidor web.
  3. Subir el archivo `enviar_correo.php` y el directorio `vendor/` (generado por Composer) al servidor.
  4. Asegurarse de que las variables de entorno estén configuradas correctamente en el servidor.