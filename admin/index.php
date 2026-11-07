<?php
require_once __DIR__ . '/../includes/bootstrap.php';
exigir_admin();
require_once __DIR__ . '/../includes/conexion.php';

$resultado = mysqli_query($conexion, 'SELECT id, titulo, genero, destacado FROM libros ORDER BY id DESC');
$libros = [];
while ($libro = mysqli_fetch_assoc($resultado)) {
    $libros[] = $libro;
}
$flash = leer_flash();
$tituloPagina = 'Panel de libros';
$baseUrl = '../';
require __DIR__ . '/../componentes/documento_inicio.php';
?>
<main class="panel-main">
    <div class="panel-encabezado">
        <h1>Administración de libros</h1>
        <div class="panel-actions">
            <a class="panel-boton" href="libro.php">+ Agregar un libro</a>
            <a href="cambiar_clave.php">Cambiar contraseña</a>
        </div>
    </div>
    <p>Sesión iniciada como <strong><?= e($_SESSION['admin_usuario'] ?? 'admin') ?></strong>. Gestioná los libros guardados en MySQL.</p>
    <?php if ($flash): ?><div role="status" class="aviso aviso-<?= e($flash['tipo']) ?>"><?= e($flash['texto']) ?></div><?php endif; ?>
    <div class="panel-table-scroll">
        <table class="panel-table">
            <thead><tr><th>ID</th><th>Título</th><th>Género</th><th>Destacado</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($libros as $libro): ?>
                <tr>
                    <td><?= (int)$libro['id'] ?></td>
                    <td><?= e($libro['titulo']) ?></td>
                    <td><?= e($libro['genero']) ?></td>
                    <td><?= (int)$libro['destacado'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <div class="panel-actions">
                            <a class="panel-boton" href="libro.php?id=<?= (int)$libro['id'] ?>">Editar</a>
                            <form action="eliminar.php" method="POST" onsubmit="return confirm('¿Eliminar este libro del catálogo?');">
                                <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$libro['id'] ?>">
                                <button class="panel-boton panel-boton-peligro" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$libros): ?><tr><td colspan="5">Todavía no hay libros cargados.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <form action="salir.php" method="POST" style="margin-top:28px">
        <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
        <button class="panel-boton" type="submit">Cerrar sesión</button>
    </form>
</main>
<?php require __DIR__ . '/../componentes/documento_fin.php'; ?>
