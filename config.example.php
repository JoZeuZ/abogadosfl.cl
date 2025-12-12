<?php
/**
 * Configuración de Credenciales - EJEMPLO
 * 
 * Copiar este archivo como config.php y actualizar con valores reales
 * 
 * INSTRUCCIONES:
 * 1. Copiar: cp config.example.php config.php
 * 2. Editar config.php con las credenciales reales
 * 3. NUNCA subir config.php a Git
 */

return [
    // Configuración SMTP
    'smtp' => [
        'host' => 'mail.tudominio.com',
        'port' => 465, // 465 para SSL/TLS, 587 para STARTTLS
        'username' => 'contacto@tudominio.com',
        'password' => 'TU_CONTRASEÑA_AQUI',
        'from_email' => 'contacto@tudominio.com',
        'from_name' => 'Nombre del Sitio',
        'to_email' => 'destinatario@tudominio.com'
    ],
    
    // reCAPTCHA v3 (obtener en https://www.google.com/recaptcha/admin)
    'recaptcha' => [
        'site_key' => 'TU_SITE_KEY_AQUI',
        'secret_key' => 'TU_SECRET_KEY_AQUI'
    ],
    
    // Backup de mensajes
    'backup' => [
        'enabled' => true,
        'file' => __DIR__ . '/logs/mensajes.csv'
    ]
];
