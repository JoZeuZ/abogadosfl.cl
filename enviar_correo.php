<?php
/**
 * Script de procesamiento del formulario de contacto
 * Abogados FL - Bufete de Abogados
 * 
 * Este script maneja el envío seguro de formularios de contacto
 * con validación, sanitización y verificación reCAPTCHA
 */

// Importar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Configuración del sistema
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Configuración de headers de seguridad
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Solo permitir método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: /error.html?code=405&reason=method_not_allowed');
    exit('Método no permitido');
}

// Configuración del formulario
if (!getenv('SMTP_HOST') || !getenv('SMTP_USER') || !getenv('SMTP_PASS') || !getenv('RECAPTCHA_SECRET_KEY')) {
    error_log('ADVERTENCIA: Una o más variables de entorno requeridas no están configuradas');
}

$config = [
    'smtp' => [
        'host'       => getenv('SMTP_HOST')       ?: 'smtp.example.com',
        'port'       => getenv('SMTP_PORT')       ?: 587,
        'username'   => getenv('SMTP_USER')       ?: 'CONFIGURE_SMTP_USER',
        'password'   => getenv('SMTP_PASS')       ?: 'CONFIGURE_SMTP_PASS',
        'from_email' => getenv('SMTP_FROM_EMAIL') ?: 'no-reply@example.com',
        'from_name'  => getenv('SMTP_FROM_NAME')  ?: 'Abogados FL',
        'to_email'   => getenv('SMTP_TO_EMAIL')   ?: 'contacto@example.com'
    ],
    'recaptcha' => [
        'secret_key' => getenv('RECAPTCHA_SECRET_KEY') ?: 'CONFIGURE_RECAPTCHA_KEY'
    ],
    'backup' => [
        'enabled' => true,
        'file' => __DIR__ . '/logs/mensajes.csv'
    ]
];

// Función de sanitización
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

// Función de validación de email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Función de validación de teléfono (formato chileno)
function validatePhone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    return preg_match('/^(\+56)?[0-9]{7,10}$/', $phone);
}

// Función para verificar reCAPTCHA
function verifyRecaptcha($recaptcha_response, $secret_key) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secret_key,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === false) {
        return false;
    }
    
    $json = json_decode($result, true);
    return isset($json['success']) && $json['success'] === true;
}

// Función para guardar backup
function saveBackup($data, $file_path) {
    try {
        $dir = dirname($file_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $csv_data = [
            date('Y-m-d H:i:s'),
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['servicio'],
            str_replace(["\r", "\n"], ' ', $data['mensaje']),
            $_SERVER['REMOTE_ADDR']
        ];
        
        $fp = fopen($file_path, 'a');
        if ($fp) {
            fputcsv($fp, $csv_data);
            fclose($fp);
            return true;
        }
    } catch (Exception $e) {
        error_log("Error guardando backup: " . $e->getMessage());
    }
    return false;
}

// Procesar el formulario
try {
    // Verificar que existan los campos requeridos
    $required_fields = ['nombre', 'email', 'telefono', 'servicio', 'mensaje', 'politicas'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            http_response_code(400);
            header('Location: /error.html?code=400&reason=missing_fields&field=' . urlencode($field));
            exit('Campo requerido faltante: ' . $field);
        }
    }
    
    // Verificar reCAPTCHA
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        http_response_code(403);
        header('Location: /error.html?code=403&reason=recaptcha_missing');
        exit('Verificación reCAPTCHA requerida');
    }
    
    if (!verifyRecaptcha($_POST['g-recaptcha-response'], $config['recaptcha']['secret_key'])) {
        http_response_code(403);
        header('Location: /error.html?code=403&reason=recaptcha_failed');
        exit('Verificación reCAPTCHA fallida');
    }
    
    // Sanitizar y validar datos
    $nombre = sanitizeInput($_POST['nombre']);
    $email = sanitizeInput($_POST['email']);
    $telefono = sanitizeInput($_POST['telefono']);
    $servicio = sanitizeInput($_POST['servicio']);
    $mensaje = sanitizeInput($_POST['mensaje']);
    
    // Validaciones específicas
    if (strlen($nombre) < 2 || strlen($nombre) > 100) {
        http_response_code(400);
        header('Location: /error.html?code=400&reason=invalid_name');
        exit('El nombre debe tener entre 2 y 100 caracteres');
    }
    
    if (!validateEmail($email)) {
        http_response_code(400);
        header('Location: /error.html?code=400&reason=invalid_email');
        exit('El email no tiene un formato válido');
    }
    
    if (!validatePhone($telefono)) {
        http_response_code(400);
        header('Location: /error.html?code=400&reason=invalid_phone');
        exit('El teléfono no tiene un formato válido');
    }
    
    if (strlen($mensaje) < 10 || strlen($mensaje) > 1000) {
        http_response_code(400);
        header('Location: /error.html?code=400&reason=' . (strlen($mensaje) < 10 ? 'short_message' : 'long_message'));
        exit('El mensaje debe tener entre 10 y 1000 caracteres');
    }
    
    // Verificar que se aceptaron las políticas
    if ($_POST['politicas'] !== 'on') {
        http_response_code(400);
        header('Location: /error.html?code=400&reason=no_policy');
        exit('Debe aceptar las políticas de privacidad');
    }
    
    // Preparar datos para guardar y enviar
    $form_data = [
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => $telefono,
        'servicio' => $servicio,
        'mensaje' => $mensaje,
        'fecha' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR']
    ];
    
    // Guardar backup si está habilitado
    if ($config['backup']['enabled']) {
        saveBackup($form_data, $config['backup']['file']);
    }
    
    // Preparar el email
    require_once __DIR__ . '/vendor/autoload.php'; // Composer autoload para PHPMailer
    
    $mail = new PHPMailer(true);
    
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = $config['smtp']['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp']['username'];
    $mail->Password = $config['smtp']['password'];
    $mail->SMTPSecure = ($config['smtp']['port'] == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['smtp']['port'];
    $mail->CharSet = 'UTF-8';
    
    // Configuración del email
    $mail->setFrom($config['smtp']['from_email'], $config['smtp']['from_name']);
    $mail->addAddress($config['smtp']['to_email']);
    $mail->addReplyTo($email, $nombre);
    
    // Contenido del email
    $mail->isHTML(true);
    $mail->Subject = 'Nueva Consulta Legal - ' . $servicio;
    
    $mail->Body = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: #1f2c3d; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #c5a47e; }
            .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Nueva Consulta Legal</h2>
            <p>Abogados FL</p>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Nombre:</span> ' . $nombre . '
            </div>
            <div class="field">
                <span class="label">Email:</span> ' . $email . '
            </div>
            <div class="field">
                <span class="label">Teléfono:</span> ' . $telefono . '
            </div>
            <div class="field">
                <span class="label">Servicio:</span> ' . $servicio . '
            </div>
            <div class="field">
                <span class="label">Mensaje:</span><br>
                ' . nl2br($mensaje) . '
            </div>
            <div class="field">
                <span class="label">Fecha:</span> ' . $form_data['fecha'] . '
            </div>
            <div class="field">
                <span class="label">IP:</span> ' . $form_data['ip'] . '
            </div>
        </div>
        <div class="footer">
            <p>Este mensaje fue enviado desde el formulario de contacto del sitio web de Abogados FL</p>
        </div>
    </body>
    </html>';
    
    // Enviar el email
    if ($mail->send()) {
        // Redirigir a página de éxito
        header('Location: /gracias.html');
        exit();
    } else {
        throw new Exception("Error al enviar el email: " . $mail->ErrorInfo);
    }
    
} catch (Exception $e) {
    // Log del error
    error_log("Error en formulario de contacto: " . $e->getMessage());
    error_log("Datos POST: " . print_r($_POST, true));
    
    // Redirigir a página de error
    header('Location: /error.html');
    exit();
}
?>
