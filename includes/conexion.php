<?php
// Conexión centralizada con MySQLi; no revelar contraseñas ni errores SQL al público.
$rutaConfiguracion = __DIR__ . '/config.local.php';
if (!is_file($rutaConfiguracion)) {
    http_response_code(503);
    exit('Falta configurar MySQL. Copiá includes/config.ejemplo.php como includes/config.local.php y completá los datos.');
}
$configBD = require $rutaConfiguracion;
if (!is_array($configBD)) {
    http_response_code(503);
    exit('Configuración de base de datos no válida.');
}

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conexion = mysqli_connect(
        (string)$configBD['host'],
        (string)$configBD['usuario'],
        (string)$configBD['clave'],
        (string)$configBD['base'],
        (int)($configBD['puerto'] ?? 3306)
    );
    mysqli_set_charset($conexion, 'utf8mb4');
} catch (Throwable $error) {
    error_log('Error de conexión a MySQL: ' . $error->getMessage());
    http_response_code(503);
    exit('No se pudo conectar a la base de datos. Revisá los datos de configuración y que MySQL esté iniciado.');
}
