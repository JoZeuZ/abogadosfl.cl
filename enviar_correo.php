<?php
/**
 * Script de procesamiento del formulario de contacto
 * Flores León y Asociados - Estudio Jurídico
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
header('Access-Control-Allow-Origin: http://localhost:4321');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Manejar preflight request de CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo permitir método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: /error.html');
    exit('Método no permitido');
}

// Cargar configuración desde archivo seguro
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    error_log('[ERROR] Archivo config.php no encontrado');
    http_response_code(500);
    header('Location: /error.html');
    exit('Error de configuración del servidor');
}

try {
    $config = require $configFile;
} catch (Exception $e) {
    error_log('[ERROR] Error al cargar config.php: ' . $e->getMessage());
    http_response_code(500);
    header('Location: /error.html');
    exit('Error de configuración del servidor');
}

// Validar que la configuración tenga los campos necesarios
if (!isset($config['smtp']['password']) || empty($config['smtp']['password'])) {
    error_log('[ERROR] Contraseña SMTP no configurada en config.php');
    http_response_code(500);
    header('Location: /error.html');
    exit('Error de configuración del servidor');
}

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

// Función de validación de teléfono (formato Chileno)
function validatePhone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    return preg_match('/^(\+56)?[0-9]{7,10}$/', $phone);
}

// Función para verificar reCAPTCHA
function verifyRecaptcha($recaptcha_response, $config) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $config['recaptcha']['secret_key'],
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
    error_log('[INFO] ===== Iniciando procesamiento de formulario =====');
    error_log('[DEBUG] Campos POST recibidos: ' . implode(', ', array_keys($_POST)));
    
    // Verificar que existan los campos requeridos
    $required_fields = ['nombre', 'email', 'telefono', 'servicio', 'mensaje', 'privacidad'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            error_log('[ERROR] Campo obligatorio faltante: ' . $field);
            throw new Exception("El campo {$field} es obligatorio");
        }
    }
    error_log('[INFO] Todos los campos requeridos presentes');
    
    // Verificar reCAPTCHA
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        throw new Exception("Debe completar la verificación reCAPTCHA");
    }
    
    if (!verifyRecaptcha($_POST['g-recaptcha-response'], $config)) {
        error_log('[ERROR] Verificación reCAPTCHA fallida');
        throw new Exception("Verificación reCAPTCHA fallida");
    }
    error_log('[INFO] reCAPTCHA verificado exitosamente');
    
    // Sanitizar y validar datos
    $nombre = sanitizeInput($_POST['nombre']);
    $email = sanitizeInput($_POST['email']);
    $telefono = sanitizeInput($_POST['telefono']);
    $servicio = sanitizeInput($_POST['servicio']);
    $mensaje = sanitizeInput($_POST['mensaje']);
    
    // Validaciones específicas
    if (strlen($nombre) < 2 || strlen($nombre) > 100) {
        throw new Exception("El nombre debe tener entre 2 y 100 caracteres.");
    }
    
    if (!validateEmail($email)) {
        throw new Exception("El email no tiene un formato válido. Por favor, revise su dirección de correo electrónico.");
    }
    
    if (!validatePhone($telefono)) {
        throw new Exception("El teléfono no tiene un formato válido. Debe ser un número chileno (ej: +56912345678 o 912345678).");
    }
    
    if (strlen($mensaje) < 10 || strlen($mensaje) > 1000) {
        throw new Exception("El mensaje debe tener entre 10 y 1000 caracteres. Actualmente tiene " . strlen($mensaje) . " caracteres.");
    }
    
    // Verificar que se aceptaron las políticas
    if (!isset($_POST['privacidad']) || $_POST['privacidad'] !== 'on') {
        error_log('[ERROR] Políticas de privacidad no aceptadas');
        throw new Exception("Debe aceptar las políticas de privacidad");
    }
    error_log('[INFO] Validaciones completadas exitosamente');
    
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
        error_log('[INFO] Intentando guardar backup en: ' . $config['backup']['file']);
        $backup_result = saveBackup($form_data, $config['backup']['file']);
        error_log('[INFO] Resultado backup: ' . ($backup_result ? 'exitoso' : 'fallido'));
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
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
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
            <p>Flores León y Asociados</p>
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
            <p>Este mensaje fue enviado desde el formulario de contacto del sitio web de Flores León y Asociados</p>
        </div>
    </body>
    </html>';
    
    // Enviar el email
    error_log('[INFO] Intentando enviar email a: ' . $config['smtp']['to_email']);
    if ($mail->send()) {
        error_log('[INFO] Email enviado exitosamente');
        // Devolver éxito como JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Formulario enviado exitosamente'
        ]);
        exit();
    } else {
        error_log('[ERROR] Fallo al enviar email: ' . $mail->ErrorInfo);
        throw new Exception("Error al enviar el email. Por favor, intente nuevamente o contáctenos por teléfono.");
    }
    
} catch (Exception $e) {
    // Log del error
    error_log('[ERROR] Excepción en formulario de contacto: ' . $e->getMessage());
    error_log('[ERROR] Archivo: ' . $e->getFile() . ' Línea: ' . $e->getLine());
    error_log('[DEBUG] Datos POST: ' . print_r($_POST, true));
    
    // Devolver error como JSON
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit();
}
?>
