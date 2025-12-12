# Credenciales y Configuración de Producción

## reCAPTCHA v3 (Google)

### Site Key (Clave de Sitio)
```
6LceRAksAAAAAJrvVA017oaAMaMJnPR7wTxXDYY3
```
**Uso**: Layout.astro línea 67
**Estado**: ✅ Configurada

### Secret Key (Clave Secreta)
```
6LceRAksAAAAAMVncmHnayHiujv2ExcIC9dG58nF
```
**Uso**: enviar_correo.php línea 46
**Estado**: ✅ Configurada

---

## Configuración SMTP

### Servidor de Correo
- **Host**: `mail.abogadosfl.cl`
- **Puerto SMTP**: `465` (SSL/TLS)
- **Usuario**: `contacto@abogadosfl.cl`
- **Contraseña**: ⚠️ Configurar en enviar_correo.php línea 39

### Configuración en enviar_correo.php (líneas 34-48)
```php
'smtp' => [
    'host' => 'mail.abogadosfl.cl',
    'port' => 465,
    'username' => 'contacto@abogadosfl.cl',
    'password' => 'TU_CONTRASEÑA_AQUI', // ⚠️ Actualizar
    'from_email' => 'contacto@abogadosfl.cl',
    'from_name' => 'AbogadosFL',
    'to_email' => 'contacto@abogadosfl.cl'
]
```

---

## Checklist Pre-Deploy

### ✅ Configurado
- reCAPTCHA site key en Layout.astro
- reCAPTCHA secret key en enviar_correo.php
- SMTP host, puerto, usuario configurados

### ⚠️ Pendiente
- **Contraseña del correo** en enviar_correo.php línea 39
- Verificar que Contact.astro use la misma site key de reCAPTCHA
- Crear carpeta `logs/` en servidor con permisos 755

**Última actualización**: 11 Diciembre 2025
