<?php
require_once __DIR__ . '/../includes/bootstrap.php';
exigir_admin();
require_once __DIR__ . '/../includes/conexion.php';

function leer_campo(string $clave): string
{
    return is_string($_POST[$clave] ?? null) ? trim($_POST[$clave]) : '';
}

// La imagen debe ser una de las portadas que ya existen en la carpeta local.
$opcionesImagen = [];
foreach (glob(__DIR__ . '/../imagenes/*') ?: [] as $archivo) {
    if (is_file($archivo) && preg_match('/\.(jpg|jpeg|png|webp)$/i', $archivo)) {
        $opcionesImagen[] = 'imagenes/' . basename($archivo);
    }
}
sort($opcionesImagen);

$id = 0;
$libro = ['titulo' => '', 'genero' => '', 'descripcion' => '', 'imagen' => '', 'enlace' => '', 'destacado' => 0];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $rawId = $_POST['id'] ?? null;
    $id = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($id === false) {
        http_response_code(400);
        exit('ID de libro inválido.');
    }
    foreach (['titulo', 'genero', 'descripcion', 'imagen', 'enlace'] as $campo) {
        $libro[$campo] = leer_campo($campo);
    }
    $libro['destacado'] = isset($_POST['destacado']) ? 1 : 0;
    if (in_array('', [$libro['titulo'], $libro['genero'], $libro['descripcion'], $libro['imagen'], $libro['enlace']], true)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (strlen($libro['titulo']) > 150 || strlen($libro['genero']) > 80 || strlen($libro['descripcion']) > 5000) {
        $error = 'Uno de los campos supera la longitud permitida.';
    } elseif (!in_array($libro['imagen'], $opcionesImagen, true)) {
        $error = 'Elegí una portada existente en la carpeta imagenes.';
    } elseif (strlen($libro['enlace']) > 1000 || !filter_var($libro['enlace'], FILTER_VALIDATE_URL) ||
        !in_array(strtolower((string)parse_url($libro['enlace'], PHP_URL_SCHEME)), ['http', 'https'], true)) {
        $error = 'Ingresá un enlace http o https válido.';
    }

    if ($error === '') {
        try {
            if ($id === 0) {
                $sql = 'INSERT INTO libros (titulo, genero, descripcion, imagen, enlace, destacado) VALUES (?, ?, ?, ?, ?, ?)';
                $sentencia = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($sentencia, 'sssssi', $libro['titulo'], $libro['genero'], $libro['descripcion'], $libro['imagen'], $libro['enlace'], $libro['destacado']);
            } else {
                $sql = 'UPDATE libros SET titulo = ?, genero = ?, descripcion = ?, imagen = ?, enlace = ?, destacado = ? WHERE id = ?';
                $sentencia = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($sentencia, 'sssssii', $libro['titulo'], $libro['genero'], $libro['descripcion'], $libro['imagen'], $libro['enlace'], $libro['destacado'], $id);
            }
            mysqli_stmt_execute($sentencia);
            mysqli_stmt_close($sentencia);
            mensaje_flash('exito', $id === 0 ? 'El libro se agregó correctamente.' : 'Los cambios del libro se guardaron.');
            header('Location: index.php');
            exit;
        } catch (mysqli_sql_exception $exception) {
            error_log('Error al guardar libro: ' . $exception->getMessage());
            $error = 'No se pudo guardar el libro en MySQL.';
        }
    }
} else {
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            http_response_code(400);
            exit('ID de libro inválido.');
        }
        $sentencia = mysqli_prepare($conexion, 'SELECT titulo, genero, descripcion, imagen, enlace, destacado FROM libros WHERE id = ?');
        mysqli_stmt_bind_param($sentencia, 'i', $id);
        mysqli_stmt_execute($sentencia);
        $resultado = mysqli_stmt_get_result($sentencia);
        $encontrado = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($sentencia);
        if (!$encontrado) {
            http_response_code(404);
            exit('No existe el libro solicitado.');
        }
        $libro = $encontrado;
    }
}
$tituloPagina = $id > 0 ? 'Editar libro' : 'Agregar libro';
$baseUrl = '../';
require __DIR__ . '/../componentes/documento_inicio.php';
?>
<main class="panel-main">
    <h1><?= e($tituloPagina) ?></h1>
    <?php if ($error !== ''): ?><div role="alert" class="aviso aviso-error"><?= e($error) ?></div><?php endif; ?>
    <form class="panel-form" action="libro.php" method="POST">
        <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <div><label for="titulo">Título</label><input id="titulo" name="titulo" value="<?= e($libro['titulo']) ?>" maxlength="150" required></div>
        <div><label for="genero">Género</label><input id="genero" name="genero" value="<?= e($libro['genero']) ?>" maxlength="80" required></div>
        <div><label for="descripcion">Descripción</label><textarea id="descripcion" name="descripcion" maxlength="5000" required><?= e($libro['descripcion']) ?></textarea></div>
        <div>
            <label for="imagen">Portada (imagen existente)</label>
            <select id="imagen" name="imagen" required>
                <option value="">Seleccioná una imagen</option>
                <?php foreach ($opcionesImagen as $opcion): ?>
                    <option value="<?= e($opcion) ?>" <?= $libro['imagen'] === $opcion ? 'selected' : '' ?>><?= e(basename($opcion)) ?></option>
                <?php endforeach; ?>
            </select>
            <small>Para usar una nueva portada, agregá primero el archivo JPG/PNG/WEBP a la carpeta imagenes.</small>
        </div>
        <div><label for="enlace">Enlace al libro</label><input type="url" id="enlace" name="enlace" value="<?= e($libro['enlace']) ?>" maxlength="1000" required placeholder="https://..."></div>
        <div><label><input type="checkbox" name="destacado" value="1" <?= (int)$libro['destacado'] === 1 ? 'checked' : '' ?>> Destacar como recomendado</label></div>
        <div class="panel-actions"><button class="panel-boton" type="submit">Guardar libro</button><a href="index.php">Cancelar</a></div>
    </form>
</main>
<?php require __DIR__ . '/../componentes/documento_fin.php'; ?>
