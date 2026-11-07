<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Inicializamos las variables para evitar warnings o variables no definidas.
$nombre = "";
$apellido = "";
$correo = "";
$motivo = "";
$mensaje = "";

$error = "";
$textoMotivo = "";


/*
 * Comprobamos que la página haya sido llamada
 * mediante el envío del formulario por POST.
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verificamos que todos los campos hayan sido enviados.
    if (
        isset($_POST["nombre"]) && is_string($_POST["nombre"]) &&
        isset($_POST["apellido"]) && is_string($_POST["apellido"]) &&
        isset($_POST["correo"]) && is_string($_POST["correo"]) &&
        isset($_POST["motivo"]) && is_string($_POST["motivo"]) &&
        isset($_POST["mensaje"]) && is_string($_POST["mensaje"])
    ) {

        /*
         * Eliminamos espacios accidentales al principio
         * y al final de cada dato recibido.
         */
        $nombre = trim($_POST["nombre"]);
        $apellido = trim($_POST["apellido"]);
        $correo = trim($_POST["correo"]);
        $motivo = trim($_POST["motivo"]);
        $mensaje = trim($_POST["mensaje"]);


        // Comprobamos que ningún campo obligatorio esté vacío.
        if (
            empty($nombre) ||
            empty($apellido) ||
            empty($correo) ||
            empty($motivo) ||
            empty($mensaje)
        ) {
            $error = "Todos los campos son obligatorios.";
        }

        // Validamos una longitud mínima para el nombre.
        elseif (strlen($nombre) < 2) {
            $error = "El nombre debe tener al menos 2 caracteres.";
        }

        // Validamos el formato del correo electrónico.
        elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = "El correo electrónico ingresado no es válido.";
        }

        // Comprobamos que el motivo sea una opción válida.
        elseif (
            $motivo !== "consulta" &&
            $motivo !== "recomendacion" &&
            $motivo !== "problema"
        ) {
            $error = "El motivo de contacto seleccionado no es válido.";
        }

        // Si todos los datos son correctos, los saneamos para mostrarlos.
        else {

            $nombre = htmlspecialchars($nombre, ENT_QUOTES, "UTF-8");
            $apellido = htmlspecialchars($apellido, ENT_QUOTES, "UTF-8");
            $correo = htmlspecialchars($correo, ENT_QUOTES, "UTF-8");
            $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8");


            // Personalizamos la respuesta según el motivo seleccionado.
            if ($motivo === "consulta") {

                $textoMotivo =
                    "Nos contactaste para realizar una consulta general.";

            } elseif ($motivo === "recomendacion") {

                $textoMotivo =
                    "Nos contactaste para recomendarnos un libro.";

            } elseif ($motivo === "problema") {

                $textoMotivo =
                    "Nos contactaste para informarnos un problema.";
            }
        }

    } else {

        $error = "Faltan datos obligatorios en el formulario.";
    }

} else {

    /*
     * Este mensaje aparece cuando alguien intenta entrar
     * directamente a confirmacion.php mediante la URL.
     */
    $error = "No se puede acceder directamente a esta página.";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Confirmación | FF_LIBROS</title>

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

        <section class="contacto">

            <div class="contacto-contenedor">

                <?php if (!empty($error)): ?>

                    <h1>Hubo un inconveniente</h1>

                    <p>
                        <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                    </p>

                    <a href="index.php#contacto">
                        Volver al formulario de contacto
                    </a>


                <?php else: ?>

                    <h1>
                        ¡Gracias, <?= $nombre ?> <?= $apellido ?>!
                    </h1>

                    <p>
                        El formulario fue procesado correctamente. No se envió un correo automáticamente.
                    </p>

                    <p>
                        La dirección que ingresaste fue:
                        <strong><?= $correo ?></strong>
                    </p>

                    <p>
                        <?= $textoMotivo ?>
                    </p>

                    <h2>Resumen de tu consulta</h2>

                    <p>
                        <strong>Motivo:</strong>

                        <?php if ($motivo === "consulta"): ?>

                            Consulta general

                        <?php elseif ($motivo === "recomendacion"): ?>

                            Recomendación de un libro

                        <?php elseif ($motivo === "problema"): ?>

                            Informe de un problema

                        <?php endif; ?>
                    </p>

                    <p>
                        <strong>Mensaje:</strong><br>
                        <?= nl2br($mensaje) ?>
                    </p>

                    <a href="index.php">
                        Volver al inicio
                    </a>

                <?php endif; ?>

            </div>

        </section>

    </main>

    <?php include_once __DIR__ . '/componentes/footer.php'; ?>

</body>

</html>