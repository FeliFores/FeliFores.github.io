<?php
// Esta inclusión se usa al inicio de cada página antes de imprimir HTML.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function e($dato): string
{
    return htmlspecialchars((string)$dato, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function mensaje_flash(string $tipo, string $texto): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'texto' => $texto];
}

function leer_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function token_csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function validar_csrf(): void
{
    if (!isset($_POST['csrf']) || !is_string($_POST['csrf']) ||
        !hash_equals(token_csrf(), $_POST['csrf'])) {
        http_response_code(403);
        exit('Solicitud no válida. Volvé al formulario e intentá nuevamente.');
    }
}

function es_admin(): bool
{
    return isset($_SESSION['admin_id']) && is_int($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
}

function exigir_admin(bool $permitirCambioClave = false): void
{
    if (!es_admin()) {
        header('Location: login.php');
        exit;
    }
    if (!$permitirCambioClave && !empty($_SESSION['cambiar_clave'])) {
        header('Location: cambiar_clave.php');
        exit;
    }
}
