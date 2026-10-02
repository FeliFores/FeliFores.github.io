<?php

$libros = [
    [
        "titulo" => "Coraline",
        "genero" => "Aventura",
        "descripcion" => "Una historia de aventura y fantasía en la que Coraline descubre un mundo alternativo.",
        "imagen" => "imagenes/Caroline.jpg",
        "enlace" => "https://docs.google.com/file/d/0B_Q4k6k8MyYJbkFoLVdlWTJ0bzA/view?resourcekey=0-ReugSdUUQH0747n74-_xDg",
        "destacado" => false
    ],
    [
        "titulo" => "Frankenstein",
        "genero" => "Ciencia Ficción",
        "descripcion" => "La historia de un científico que da vida a una criatura creada a partir de distintos cuerpos.",
        "imagen" => "imagenes/frankenstein-9781982146160_xlg.jpg",
        "enlace" => "https://drive.google.com/file/d/0B8Bcptq_5FyXamtia1VwQ3hxNGM/view?resourcekey=0-iM1c9DHUqbsCZUHsIbO9zg",
        "destacado" => false
    ],
    [
        "titulo" => "Otelo",
        "genero" => "Tragedia",
        "descripcion" => "Una tragedia de William Shakespeare atravesada por los celos, la manipulación y la traición.",
        "imagen" => "imagenes/otelo_classicos_14x21_9788525429926_hd.jpg",
        "enlace" => "https://drive.google.com/file/d/0B1JjW_N9HUizLVdVN28wZjR3UWs/view?resourcekey=0-kLVCrs1FoVmyA7CVpoLinA",
        "destacado" => false
    ],
    [
        "titulo" => "It",
        "genero" => "Terror",
        "descripcion" => "Una novela de terror en la que un grupo de amigos se enfrenta a una presencia aterradora.",
        "imagen" => "imagenes/terror.jpeg",
        "enlace" => "https://docs.google.com/file/d/0B3N8jtudSydeYWgwbzJxOXZYS1U/edit?resourcekey=0-G7ZxKLWMipMM8vqegxBU3Q",
        "destacado" => true
    ],
    [
        "titulo" => "Romeo y Julieta",
        "genero" => "Romance",
        "descripcion" => "La historia de dos jóvenes enamorados pertenecientes a familias enfrentadas.",
        "imagen" => "imagenes/Rome y Julieta.webp",
        "enlace" => "https://ia800305.us.archive.org/29/items/romeo-y-julieta-texto-completo/Romeo%20y%20Julieta%20-%20William%20Shakespeare.pdf",
        "destacado" => false
    ],
    [
        "titulo" => "Los Escorpiones",
        "genero" => "Ficción",
        "descripcion" => "Una propuesta de ficción contemporánea incorporada al catálogo de FF_LIBROS.",
        "imagen" => "imagenes/Los-Escorpiones-i7n24218684.jpg",
        "enlace" => "https://www.casadellibro.com/libro-los-escorpiones/9788426418784/14461451",
        "destacado" => false
    ]
];

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
                href="404.html"
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