<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/conexion.php';
if (es_admin()) {
    header('Location: index.php');
    exit;
}

$error = '';
$usuario = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $usuario = is_string($_POST['usuario'] ?? null) ? trim($_POST['usuario']) : '';
    $clave = is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';
    $intentos = $_SESSION['intentos_login'] ?? ['cantidad' => 0, 'desde' => time()];
    if (time() - (int)$intentos['desde'] > 600) {
        $intentos = ['cantidad' => 0, 'desde' => time()];
    }
    if ((int)$intentos['cantidad'] >= 5) {
        $error = 'Demasiados intentos. Volvé a probar dentro de unos minutos.';
    } elseif ($usuario === '' || $clave === '') {
        $error = 'Completá usuario y contraseña.';
    } else {
        $sql = 'SELECT id, usuario, clave_hash, debe_cambiar_clave FROM administradores WHERE usuario = ? LIMIT 1';
        $sentencia = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($sentencia, 's', $usuario);
        mysqli_stmt_execute($sentencia);
        $resultado = mysqli_stmt_get_result($sentencia);
        $administrador = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($sentencia);

        if ($administrador && password_verify($clave, $administrador['clave_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$administrador['id'];
            $_SESSION['admin_usuario'] = $administrador['usuario'];
            $_SESSION['cambiar_clave'] = (bool)$administrador['debe_cambiar_clave'];
            unset($_SESSION['intentos_login']);
            mensaje_flash('exito', 'Ingresaste al panel de administración.');
            header('Location: index.php');
            exit;
        }
        $intentos['cantidad']++;
        $_SESSION['intentos_login'] = $intentos;
        $error = 'Usuario o contraseña incorrectos.';
    }
}
$tituloPagina = 'Ingresar como administrador';
$baseUrl = '../';
require __DIR__ . '/../componentes/documento_inicio.php';
?>
<main class="panel-main">
    <h1>Acceso de administración</h1>
    <p>Ingresá para administrar los libros del catálogo.</p>
    <?php if ($error !== ''): ?><div role="alert" class="aviso aviso-error"><?= e($error) ?></div><?php endif; ?>
    <form class="panel-form" method="POST" action="login.php">
        <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
        <div><label for="usuario">Usuario</label><input id="usuario" name="usuario" value="<?= e($usuario) ?>" autocomplete="username" required maxlength="80"></div>
        <div><label for="clave">Contraseña</label><input type="password" id="clave" name="clave" autocomplete="current-password" required></div>
        <div class="panel-actions"><button class="panel-boton" type="submit">Ingresar</button><a href="../index.php">Volver a la biblioteca</a></div>
    </form>
</main>
<?php require __DIR__ . '/../componentes/documento_fin.php'; ?>
