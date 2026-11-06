<?php
/**
 * Práctica 7 - Página control de acceso (no visible).
 * Valida las credenciales enviadas desde el formulario de inicio de sesión y redirige
 * al menú de usuario registrado (index-reg.php) o de vuelta al formulario con el error correspondiente.
 */
require_once __DIR__ . '/includes/funciones.php';

// Determinar página de origen para redirigir en caso de error (index.php por defecto, o aviso.php)
$origen = $_POST['origen'] ?? ($_GET['origen'] ?? 'index.php');
if ($origen !== 'aviso.php') {
    $origen = 'index.php';
}

// Aceptar datos por POST (o GET si se invoca directamente)
$usuario = $_POST['usuario'] ?? ($_GET['usuario'] ?? '');
$password = $_POST['password'] ?? ($_GET['password'] ?? '');

$usuarioValido = tieneContenidoSinEspacios($usuario);
$passwordValido = tieneContenidoSinEspacios($password);

// 1. Validar que se haya escrito algo en ambos campos (no vacíos ni solo espacios o tabuladores)
if (!$usuarioValido && !$passwordValido) {
    $parametros = http_build_query([
        'error' => 'ambos_vacios',
    ]);
    redirigir($origen . '?' . $parametros);
}

if (!$usuarioValido) {
    $parametros = http_build_query([
        'error' => 'usuario_vacio',
    ]);
    redirigir($origen . '?' . $parametros);
}

if (!$passwordValido) {
    $parametros = http_build_query([
        'error'   => 'password_vacio',
        'usuario' => trim($usuario),
    ]);
    redirigir($origen . '?' . $parametros);
}

// 2. Comprobar que el fichero de usuarios permitidos existe y tiene permisos de lectura
$rutaFicheroUsuarios = __DIR__ . '/includes/datos_usuarios.php';
if (!file_exists($rutaFicheroUsuarios) || !is_readable($rutaFicheroUsuarios)) {
    $parametros = http_build_query([
        'error' => 'fichero_usuarios',
    ]);
    redirigir($origen . '?' . $parametros);
}

require_once $rutaFicheroUsuarios;

$usuarioLimpio = trim($usuario);

// 3. Comprobar si el usuario pertenece a los cuatro usuarios permitidos y su contraseña coincide
if (
    isset($usuariosPermitidos[$usuarioLimpio]) &&
    $usuariosPermitidos[$usuarioLimpio] === $password
) {
    $parametrosExito = http_build_query([
        'usuario' => $usuarioLimpio,
    ]);
    redirigir('index-reg.php?' . $parametrosExito);
}

// 4. Si las credenciales no coinciden, redirigir al formulario de acceso indicando el error
$parametrosError = http_build_query([
    'error'   => 'credenciales',
    'usuario' => $usuarioLimpio,
]);
redirigir($origen . '?' . $parametrosError);
