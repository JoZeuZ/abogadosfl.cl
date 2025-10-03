<?php
/**
 * Script de procesamiento del formulario de contacto
 * Abogados FL - Bufete de Abogados
 * 
 * Este script maneja el envío seguro de formularios de contacto
 * con validación, sanitización y verificación reCAPTCHA
 */

// Cargar dependencias de Composer
require __DIR__ . '/vendor/autoload.php';

// Importar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Configuración del sistema
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Función para enviar respuesta de error centralizada
function send_error_response($code, $reason) {
    http_response_code($code);
    // En un entorno de producción, podrías tener una página de error más amigable
    // header('Location: /error.html?code=' . $code . '&reason=' . urlencode($reason));
    error_log("Error en formulario: " . $reason);
    exit('Error: ' . $reason);
}

// Configuración de headers de seguridad
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Solo permitir método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error_response(405, 'method_not_allowed');
}

// Cargar y validar variables de entorno
$required_env = ['SMTP_HOST', 'SMTP_USER', 'SMTP_PASS', 'RECAPTCHA_SECRET_KEY', 'SMTP_TO_EMAIL'];
foreach ($required_env as $key) {
    if (empty(getenv($key))) {
        error_log('FATAL: La variable de entorno requerida no está configurada: ' . $key);
        send_error_response(500, 'server_configuration_error');
    }
}

$config = [
    'smtp' => [
        'host'       => getenv('SMTP_HOST'),
        'port'       => getenv('SMTP_PORT')       ?: 587,
        'username'   => getenv('SMTP_USER'),
        'password'   => getenv('SMTP_PASS'),
        'from_email' => getenv('SMTP_FROM_EMAIL') ?: 'no-reply@' . $_SERVER['SERVER_NAME'],
        'from_name'  => getenv('SMTP_FROM_NAME')  ?: 'Formulario de Contacto',
        'to_email'   => getenv('SMTP_TO_EMAIL')
    ],
    'recaptcha' => [
        'secret_key' => getenv('RECAPTCHA_SECRET_KEY')
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
    return preg_match('/^(\+?56)?[0-9]{9,11}$/', $phone);
}

// Función para verificar reCAPTCHA
function verifyRecaptcha($recaptcha_response, $secret_key) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secret_key,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    // Usar cURL para mayor compatibilidad
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $result = curl_exec($ch);
    
    if (curl_errno($ch)) {
        error_log('Error de cURL en reCAPTCHA: ' . curl_error($ch));
        curl_close($ch);
        return false;
    }
    
    curl_close($ch);
    
    if ($result === false) {
        error_log('Error en la solicitud a la API de reCAPTCHA.');
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
    // 1. Verificar que existan los campos requeridos
    $required_fields = ['nombre', 'email', 'telefono', 'servicio', 'mensaje', 'politicas', 'g-recaptcha-response'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            send_error_response(400, 'missing_field_' . $field);
        }
    }

    // 2. Sanitizar todos los inputs
    $nombre   = sanitizeInput($_POST['nombre']);
    $email    = sanitizeInput($_POST['email']);
    $telefono = sanitizeInput($_POST['telefono']);
    $servicio = sanitizeInput($_POST['servicio']);
    $mensaje  = sanitizeInput($_POST['mensaje']);
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // 3. Validar datos
    if (strlen($nombre) > 100) send_error_response(400, 'invalid_name');
    if (!validateEmail($email)) send_error_response(400, 'invalid_email');
    if (!validatePhone($telefono)) send_error_response(400, 'invalid_phone');
    if (strlen($servicio) > 50) send_error_response(400, 'invalid_service');
    if (strlen($mensaje) > 5000) send_error_response(400, 'invalid_message');
    if ($_POST['politicas'] !== 'on') send_error_response(400, 'policies_not_accepted');

    // 4. Verificar reCAPTCHA
    if (!verifyRecaptcha($recaptcha_response, $config['recaptcha']['secret_key'])) {
        send_error_response(403, 'recaptcha_failed');
    }

    // 5. Guardar backup si está habilitado
    if ($config['backup']['enabled']) {
        $backup_data = compact('nombre', 'email', 'telefono', 'servicio', 'mensaje');
        saveBackup($backup_data, $config['backup']['file']);
    }

    // 6. Enviar el correo electrónico
    $mail = new PHPMailer(true);
    
    // Configuración del servidor
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Descomentar para depuración
    $mail->isSMTP();
    $mail->Host       = $config['smtp']['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['smtp']['username'];
    $mail->Password   = $config['smtp']['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $config['smtp']['port'];
    $mail->CharSet    = 'UTF-8';

    // Destinatarios
    $mail->setFrom($config['smtp']['from_email'], $config['smtp']['from_name']);
    $mail->addAddress($config['smtp']['to_email']);
    $mail->addReplyTo($email, $nombre);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Nuevo Mensaje de Contacto de ' . $nombre;
    
    // Cuerpo del correo en formato HTML
    $mail->Body    = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                h2 { color: #003366; }
                strong { color: #003366; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Nuevo Mensaje del Formulario de Contacto</h2>
                <p><strong>Nombre:</strong> {$nombre}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Teléfono:</strong> {$telefono}</p>
                <p><strong>Área de Interés:</strong> {$servicio}</p>
                <hr>
                <h3>Mensaje:</h3>
                <p>" . nl2br($mensaje) . "</p>
            </div>
        </body>
        </html>";
    
    // Cuerpo alternativo para clientes de correo que no soportan HTML
    $mail->AltBody = "Nuevo Mensaje de Contacto\n\n" .
                     "Nombre: {$nombre}\n" .
                     "Email: {$email}\n" .
                     "Teléfono: {$telefono}\n" .
                     "Área de Interés: {$servicio}\n\n" .
                     "Mensaje:\n{$mensaje}";

    $mail->send();
    
    // Redirigir a la página de agradecimiento
    header('Location: /gracias.html');
    exit();

} catch (PHPMailerException $e) {
    error_log("Error de PHPMailer: {$mail->ErrorInfo}");
    send_error_response(500, 'mail_send_error');
} catch (Exception $e) {
    error_log("Error general: " . $e->getMessage());
    send_error_response(500, 'general_error');
}
?>
