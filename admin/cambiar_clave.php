<?php
require_once __DIR__ . '/../includes/bootstrap.php';
exigir_admin(true);
require_once __DIR__ . '/../includes/conexion.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $actual = is_string($_POST['actual'] ?? null) ? $_POST['actual'] : '';
    $nueva = is_string($_POST['nueva'] ?? null) ? $_POST['nueva'] : '';
    $confirmacion = is_string($_POST['confirmacion'] ?? null) ? $_POST['confirmacion'] : '';

    $sentencia = mysqli_prepare($conexion, 'SELECT clave_hash FROM administradores WHERE id = ?');
    $adminId = $_SESSION['admin_id'];
    mysqli_stmt_bind_param($sentencia, 'i', $adminId);
    mysqli_stmt_execute($sentencia);
    $resultado = mysqli_stmt_get_result($sentencia);
    $administrador = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($sentencia);

    if (!$administrador || !password_verify($actual, $administrador['clave_hash'])) {
        $error = 'La contraseña actual es incorrecta.';
    } elseif (strlen($nueva) < 12 || strlen($nueva) > 200) {
        $error = 'La nueva contraseña debe tener entre 12 y 200 caracteres.';
    } elseif ($nueva !== $confirmacion) {
        $error = 'Las nuevas contraseñas no coinciden.';
    } elseif ($nueva === $actual) {
        $error = 'Elegí una contraseña diferente a la anterior.';
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $sentencia = mysqli_prepare($conexion, 'UPDATE administradores SET clave_hash = ?, debe_cambiar_clave = 0 WHERE id = ?');
        mysqli_stmt_bind_param($sentencia, 'si', $hash, $adminId);
        mysqli_stmt_execute($sentencia);
        mysqli_stmt_close($sentencia);
        session_regenerate_id(true);
        $_SESSION['cambiar_clave'] = false;
        mensaje_flash('exito', 'La contraseña se actualizó correctamente.');
        header('Location: index.php');
        exit;
    }
}
$tituloPagina = 'Cambiar contraseña';
$baseUrl = '../';
require __DIR__ . '/../componentes/documento_inicio.php';
?>
<main class="panel-main">
    <h1>Cambiar contraseña de administrador</h1>
    <?php if (!empty($_SESSION['cambiar_clave'])): ?>
        <p class="aviso aviso-info">Por seguridad, cambiá la contraseña inicial antes de administrar los libros.</p>
    <?php endif; ?>
    <?php if ($error !== ''): ?><div role="alert" class="aviso aviso-error"><?= e($error) ?></div><?php endif; ?>
    <form class="panel-form" action="cambiar_clave.php" method="POST">
        <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
        <div><label for="actual">Contraseña actual</label><input type="password" id="actual" name="actual" autocomplete="current-password" required></div>
        <div><label for="nueva">Nueva contraseña (mínimo 12 caracteres)</label><input type="password" id="nueva" name="nueva" minlength="12" autocomplete="new-password" required></div>
        <div><label for="confirmacion">Repetir nueva contraseña</label><input type="password" id="confirmacion" name="confirmacion" minlength="12" autocomplete="new-password" required></div>
        <div class="panel-actions"><button class="panel-boton" type="submit">Actualizar contraseña</button><?php if (empty($_SESSION['cambiar_clave'])): ?><a href="index.php">Cancelar</a><?php endif; ?></div>
    </form>
    <form action="salir.php" method="POST" style="margin-top:24px">
        <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
        <button class="panel-boton" type="submit">Cerrar sesión</button>
    </form>
</main>
<?php require __DIR__ . '/../componentes/documento_fin.php'; ?>
