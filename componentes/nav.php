<?php $baseUrl = $baseUrl ?? ''; ?>
<nav aria-label="Navegación principal">
    <a class="link-navegacion" href="<?= e($baseUrl) ?>index.php#inicio">Inicio</a>
    <a class="link-navegacion" href="<?= e($baseUrl) ?>index.php#servicios">Servicios</a>
    <a class="link-navegacion" href="<?= e($baseUrl) ?>index.php#novedades">Libros</a>
</nav>
<nav aria-label="Navegación secundaria">
    <a class="link-navegacion" href="<?= e($baseUrl) ?>index.php#ayuda">Ayuda</a>
    <a class="link-navegacion" href="<?= e($baseUrl) ?>index.php#contacto">Contacto</a>
    <?php if (es_admin()): ?>
        <a class="link-navegacion" href="<?= e($baseUrl) ?>admin/index.php">Administrar</a>
    <?php else: ?>
        <a class="link-navegacion" href="<?= e($baseUrl) ?>admin/login.php">Admin</a>
    <?php endif; ?>
</nav>
