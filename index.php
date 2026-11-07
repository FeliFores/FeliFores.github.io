<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/conexion.php';

// Los filtros provienen de GET; el género elegido se recuerda en una cookie.
$busqueda = is_string($_GET['buscar'] ?? null) ? trim($_GET['buscar']) : '';
$busqueda = substr($busqueda, 0, 100);
$generoRecibido = array_key_exists('genero', $_GET) ? $_GET['genero'] : ($_COOKIE['genero_preferido'] ?? '');
$genero = is_string($generoRecibido) ? trim($generoRecibido) : '';
$genero = substr($genero, 0, 80);

$generos = [];
$resultadoGeneros = mysqli_query($conexion, 'SELECT DISTINCT genero FROM libros ORDER BY genero');
while ($filaGenero = mysqli_fetch_assoc($resultadoGeneros)) {
    $generos[] = $filaGenero['genero'];
}
if ($genero !== '' && !in_array($genero, $generos, true)) {
    $genero = '';
}
if (array_key_exists('genero', $_GET)) {
    setcookie('genero_preferido', $genero, [
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// Prepared statements para todos los filtros recibidos del navegador.
$sql = "SELECT id, titulo, genero, descripcion, imagen, enlace, destacado
        FROM libros
        WHERE (? = '' OR genero = ?)
          AND (? = '' OR titulo LIKE ? OR descripcion LIKE ?)
        ORDER BY destacado DESC, id DESC";
$sentencia = mysqli_prepare($conexion, $sql);
$buscarComo = '%' . $busqueda . '%';
mysqli_stmt_bind_param($sentencia, 'sssss', $genero, $genero, $busqueda, $buscarComo, $buscarComo);
mysqli_stmt_execute($sentencia);
$resultadoLibros = mysqli_stmt_get_result($sentencia);
$libros = [];
while ($libro = mysqli_fetch_assoc($resultadoLibros)) {
    $libros[] = $libro;
}
mysqli_stmt_close($sentencia);
$flash = leer_flash();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>FF_LIBROS</title>

    <link
        rel="stylesheet"
        href="estilo.css"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

    <link
        rel="icon"
        type="image/png"
        href="imagenes/Favicon.img.png"
    >
</head>

<body>

    <?php include_once __DIR__ . '/componentes/header.php'; ?>

    <main>

        <!-- Presentación principal -->
        <div
            class="banner-principal"
            id="inicio"
        >
            <img
                src="imagenes/logo.png"
                alt="Logo de FF Libros"
                class="logo1"
            >

            <h1 class="baner-principal-titulo">
                Biblioteca Virtual
            </h1>

             <a
                href="index.php#novedades"
                class="banner-principal_boton"
               >
                     Nuestra Recomendación del día
              </a>

            <p class="recomendaciones_día">
                FF_LIBROS es un espacio pensado para acercar la lectura a todas
                las personas, sin barreras económicas ni impedimentos.
                Creemos firmemente que el acceso a los libros debe ser libre,
                inclusivo y gratuito. En nuestra plataforma vas a encontrar
                una amplia variedad de títulos: desde clásicos de la literatura
                universal hasta autores contemporáneos. Nuestro objetivo es
                fomentar la lectura, la educación y el pensamiento crítico.
            </p>
        </div>


        <!-- Libros generados dinámicamente -->
        <section
            class="seccion-informativa"
            id="novedades"
        >

            <h2>Novedades</h2>

            <?php if ($flash): ?>
                <p role="status" class="aviso aviso-<?= e($flash['tipo']) ?>"><?= e($flash['texto']) ?></p>
            <?php endif; ?>

            <form class="filtros-libros" action="index.php" method="GET">
                <div>
                    <label for="buscar">Buscar por título o descripción</label>
                    <input type="search" id="buscar" name="buscar" value="<?= e($busqueda) ?>" maxlength="100" placeholder="Ej.: Coraline">
                </div>
                <div>
                    <label for="genero">Género</label>
                    <select id="genero" name="genero">
                        <option value="">Todos los géneros</option>
                        <?php foreach ($generos as $opcionGenero): ?>
                            <option value="<?= e($opcionGenero) ?>" <?= $genero === $opcionGenero ? 'selected' : '' ?>><?= e($opcionGenero) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Filtrar libros</button>
                <a href="index.php?genero=#novedades" class="limpiar-filtros">Limpiar filtros</a>
            </form>
            <?php if (!$libros): ?>
                <p>No encontramos libros con esos filtros.</p>
            <?php endif; ?>

            <div class="logo-container">

                <?php foreach ($libros as $libro): ?>

                    <div class="libro-card">

                        <h3 class="libro-titulo">
                            <?= htmlspecialchars($libro["titulo"], ENT_QUOTES, "UTF-8") ?>
                        </h3>

                        <p class="libro-genero">
                            <?= htmlspecialchars($libro["genero"], ENT_QUOTES, "UTF-8") ?>

                            <?php if ($libro["destacado"]): ?>
                                <strong> - Recomendado</strong>
                            <?php endif; ?>
                        </p>

                        <a
                            href="<?= htmlspecialchars($libro["enlace"], ENT_QUOTES, "UTF-8") ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <img
                                src="<?= htmlspecialchars($libro["imagen"], ENT_QUOTES, "UTF-8") ?>"
                                alt="Portada de <?= htmlspecialchars($libro["titulo"], ENT_QUOTES, "UTF-8") ?>"
                                loading="lazy"
                            >
                        </a>

                        <p class="libro-descripcion">
                            <?= htmlspecialchars($libro["descripcion"], ENT_QUOTES, "UTF-8") ?>
                        </p>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>


        <!-- Servicios -->
        <section
            class="servicios"
            id="servicios"
        >
            <h2>Nuestros Servicios</h2>

            <div class="contenedor-servicios">

                <div class="servicio">
                    <h3>Lectura Online</h3>

                    <p>
                        Accedé a miles de libros para leer directamente desde
                        tu navegador, sin descargas ni registros.
                    </p>
                </div>

                <div class="servicio">
                    <h3>Recomendaciones Personalizadas</h3>

                    <p>
                        Descubrí nuevas lecturas según tus intereses gracias
                        a nuestro sistema de sugerencias.
                    </p>
                </div>

                <div class="servicio">
                    <h3>Descarga Gratuita</h3>

                    <p>
                        Descargá libros en formato PDF o EPUB totalmente gratis
                        y sin publicidad molesta.
                    </p>
                </div>

            </div>
        </section>


        <!-- Contenido multimedia -->
        <section
            id="historia-libros"
            class="seccion-video"
        >
            <h2>Historia de los Libros</h2>

            <p>Conocé cómo surgieron.</p>

            <div class="video-container">

                <iframe
                    src="https://www.youtube.com/embed/DwWLKkW6KOE"
                    title="Historia de los libros"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    loading="lazy"
                ></iframe>

            </div>
        </section>


        <!-- Contacto -->
        <section
            class="contacto"
            id="contacto"
        >
            <div class="contacto-contenedor">

                <h2>Contacto</h2>

                <p>
                    ¿Tenés preguntas, sugerencias o querés recomendar un libro?
                    ¡Escribinos!
                </p>

                <form
                    class="formulario-contacto"
                    action="confirmacion.php"
                    method="POST"
                >

                    <label for="nombre">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Tu nombre"
                        required
                    >


                    <label for="apellido">
                        Apellido
                    </label>

                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        placeholder="Tu apellido"
                        required
                    >


                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="Tu correo electrónico"
                        required
                    >


                    <label for="motivo">
                        Motivo de contacto
                    </label>

                    <select
                        id="motivo"
                        name="motivo"
                        required
                    >
                        <option
                            value=""
                            selected
                            disabled
                        >
                            Seleccioná un motivo
                        </option>

                        <option value="consulta">
                            Consulta general
                        </option>

                        <option value="recomendacion">
                            Recomendar un libro
                        </option>

                        <option value="problema">
                            Informar un problema
                        </option>
                    </select>


                    <label for="mensaje">
                        Mensaje
                    </label>

                    <textarea
                        id="mensaje"
                        name="mensaje"
                        placeholder="Tu mensaje"
                        rows="5"
                        required
                    ></textarea>

                    <button type="submit">
                        Enviar mensaje
                    </button>

                </form>


                <div class="info-contacto">

                    <p>
                        <strong>Email:</strong>
                        contacto@ff_libros.com
                    </p>

                    <p>
                        <strong>Ubicación:</strong>
                        Rafaela, Santa Fe, Argentina
                    </p>

                </div>

            </div>
        </section>


        <!-- Ayuda -->
        <section
            class="ayuda"
            id="ayuda"
        >
            <h2>Centro de Ayuda</h2>

            <p class="ayuda-intro">
                ¿Tenés dudas? Acá respondemos lo más importante para que puedas
                disfrutar de la lectura sin problemas.
            </p>

            <div class="preguntas-frecuentes">

                <div class="pregunta">
                    <h3>¿Necesito registrarme?</h3>

                    <p>
                        No, FF_LIBROS es totalmente libre y gratuito.
                        No necesitás crear una cuenta para acceder a los libros.
                    </p>
                </div>

                <div class="pregunta">
                    <h3>¿Puedo descargar los libros?</h3>

                    <p>
                        Sí, muchos libros están disponibles para descarga
                        en formato PDF.
                    </p>
                </div>

                <div class="pregunta">
                    <h3>¿A quién contacto si tengo un problema?</h3>

                    <p>
                        Podés usar el formulario en la sección de Contacto
                        o escribirnos por redes sociales.
                        ¡Te respondemos rápido!
                    </p>
                </div>

            </div>
        </section>

    </main>

    <?php include_once __DIR__ . '/componentes/footer.php'; ?>

</body>

</html>