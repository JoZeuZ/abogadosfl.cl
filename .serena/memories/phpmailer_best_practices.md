# Mejores Prácticas de PHPMailer

Este documento resume las mejores prácticas para usar PHPMailer en un entorno de producción, enfocado en la seguridad y el manejo de errores.

## 1. Manejo de Excepciones Específico

PHPMailer puede lanzar excepciones (`PHPMailerException`) por diversas razones relacionadas con el envío de correos (ej. fallo de autenticación SMTP, problemas de conexión, etc.). Es crucial capturar estas excepciones de forma específica para un diagnóstico y manejo de errores más preciso.

- **Acción:** Utilizar un bloque `try...catch` que capture `PHPMailerException` por separado de la `Exception` genérica.
- **Beneficio:** Permite registrar errores detallados de SMTP y, potencialmente, mostrar un mensaje más útil al usuario o reintentar el envío.

**Ejemplo de implementación:**

```php
try {
    // ... (configuración de PHPMailer) ...
    
    $mail->send();
    
    // Redirigir a página de éxito
    header('Location: /gracias.html');
    exit();

} catch (PHPMailer\\PHPMailer\\Exception $e) {
    // Error específico de PHPMailer
    error_log("Error de PHPMailer: " . $e->errorMessage());
    // Redirigir a página de error con un código específico
    header('Location: /error.html?reason=mail_send_failed');
    exit();

} catch (Exception $e) {
    // Otros errores de la aplicación
    error_log("Error general: " . $e->getMessage());
    header('Location: /error.html?reason=generic_error');
    exit();
}
```

## 2. Codificación de Caracteres

Para asegurar que todos los caracteres, incluyendo tildes y símbolos especiales, se muestren correctamente en los correos, siempre se debe especificar la codificación.

- **Acción:** Establecer la propiedad `CharSet` de la instancia de PHPMailer a `'UTF-8'`.

**Ejemplo:**

```php
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
```

## 3. Uso de cURL para Verificación de reCAPTCHA

Aunque no es directamente de PHPMailer, es parte del mismo flujo del formulario. Para máxima compatibilidad con diferentes configuraciones de hosting (especialmente aquellas con `allow_url_fopen` desactivado), es preferible usar cURL para las peticiones a la API de Google reCAPTCHA.

- **Acción:** Reemplazar el uso de `file_get_contents` con las funciones de cURL de PHP para realizar la petición POST a la API de verificación de reCAPTCHA.
- **Beneficio:** Aumenta la robustez y portabilidad del script del formulario.
```