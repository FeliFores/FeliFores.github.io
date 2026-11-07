<?php
require_once __DIR__ . '/../includes/bootstrap.php';
exigir_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}
validar_csrf();
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    http_response_code(400);
    exit('ID inválido.');
}
require_once __DIR__ . '/../includes/conexion.php';
try {
    $sentencia = mysqli_prepare($conexion, 'DELETE FROM libros WHERE id = ?');
    mysqli_stmt_bind_param($sentencia, 'i', $id);
    mysqli_stmt_execute($sentencia);
    $eliminados = mysqli_stmt_affected_rows($sentencia);
    mysqli_stmt_close($sentencia);
    mensaje_flash($eliminados > 0 ? 'exito' : 'info', $eliminados > 0 ? 'Libro eliminado correctamente.' : 'El libro ya no existe.');
} catch (mysqli_sql_exception $exception) {
    error_log('Error al eliminar libro: ' . $exception->getMessage());
    mensaje_flash('error', 'No se pudo eliminar el libro.');
}
header('Location: index.php');
exit;
