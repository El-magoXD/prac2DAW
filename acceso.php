<?php
/**
 * Práctica 7 y 8 - Página control de acceso (no visible).
 * Valida las credenciales enviadas desde el formulario de inicio de sesión:
 * - Si hay errores, emplea Flashdata en sesión (sin pasar errores por la URL) y redirige al formulario.
 * - Si es correcto, inicia la sesión del usuario, asigna su estilo CSS preferido, gestiona la cookie
 *   "Recordarme en este equipo" (90 días, HttpOnly, mediante token) y redirige a index-reg.php.
 */
require_once __DIR__ . '/includes/funciones.php';

asegurarSesionIniciada();

$origen = $_POST['origen'] ?? ($_GET['origen'] ?? 'index.php');
if ($origen !== 'aviso.php') {
    $origen = 'index.php';
}

$usuario = $_POST['usuario'] ?? ($_GET['usuario'] ?? '');
$password = $_POST['password'] ?? ($_GET['password'] ?? '');
$recordarme = !empty($_POST['recordarme']);

$usuarioValido = tieneContenidoSinEspacios($usuario);
$passwordValido = tieneContenidoSinEspacios($password);

// 1. Validar que se haya escrito algo en ambos campos (no vacíos ni solo espacios o tabuladores)
if (!$usuarioValido && !$passwordValido) {
    establecerFlash('login_error', [
        'codigo'          => 'ambos_vacios',
        'mensajes'        => [
            'El nombre de usuario no puede estar vacío ni contener solo espacios o tabuladores.',
            'La contraseña no puede estar vacía ni contener solo espacios o tabuladores.',
        ],
        'error_usuario'   => 'El nombre de usuario no puede estar vacío ni contener solo espacios o tabuladores.',
        'error_password'  => 'La contraseña no puede estar vacía ni contener solo espacios o tabuladores.',
        'usuario_previo'  => '',
    ]);
    redirigir($origen);
}

if (!$usuarioValido) {
    establecerFlash('login_error', [
        'codigo'          => 'usuario_vacio',
        'mensajes'        => [
            'El nombre de usuario no puede estar vacío ni contener solo espacios o tabuladores.',
        ],
        'error_usuario'   => 'El nombre de usuario no puede estar vacío ni contener solo espacios o tabuladores.',
        'error_password'  => '',
        'usuario_previo'  => '',
    ]);
    redirigir($origen);
}

if (!$passwordValido) {
    establecerFlash('login_error', [
        'codigo'          => 'password_vacio',
        'mensajes'        => [
            'La contraseña no puede estar vacía ni contener solo espacios o tabuladores.',
        ],
        'error_usuario'   => '',
        'error_password'  => 'La contraseña no puede estar vacía ni contener solo espacios o tabuladores.',
        'usuario_previo'  => trim($usuario),
    ]);
    redirigir($origen);
}

// 2. Comprobar que el fichero de usuarios permitidos existe y tiene permisos de lectura
$rutaFicheroUsuarios = __DIR__ . '/includes/datos_usuarios.php';
if (!file_exists($rutaFicheroUsuarios) || !is_readable($rutaFicheroUsuarios)) {
    establecerFlash('login_error', [
        'codigo'          => 'fichero_usuarios',
        'mensajes'        => [
            'Error interno: no se ha podido acceder al fichero de usuarios permitidos.',
        ],
        'error_usuario'   => '',
        'error_password'  => '',
        'usuario_previo'  => trim($usuario),
    ]);
    redirigir($origen);
}

require $rutaFicheroUsuarios;

$usuarioLimpio = trim($usuario);

// 3. Comprobar si el usuario pertenece a los cuatro usuarios permitidos y su contraseña coincide
if (
    isset($usuariosPermitidos[$usuarioLimpio]) &&
    isset($usuariosPermitidos[$usuarioLimpio]['password']) &&
    $usuariosPermitidos[$usuarioLimpio]['password'] === $password
) {
    $datosUsuario = $usuariosPermitidos[$usuarioLimpio];

    $_SESSION['usuario'] = $usuarioLimpio;
    $_SESSION['estilo'] = $datosUsuario['estilo'] ?? 'style.css';
    $_SESSION['titulo_estilo'] = $datosUsuario['titulo_estilo'] ?? 'Estilo principal';

    if ($recordarme) {
        $ahoraTexto = date('d/m/Y \a \l\a\s H:i');
        $expira90Dias = time() + (90 * 24 * 60 * 60);

        $_SESSION['recordado'] = true;
        $_SESSION['ultima_visita'] = $ahoraTexto;

        enviarCookieSegura('recordar_token', $datosUsuario['token'], $expira90Dias);
        enviarCookieSegura('recordar_ultima_visita', $ahoraTexto, $expira90Dias);
        enviarCookieSegura('recordar_expira', (string) $expira90Dias, $expira90Dias);
    } else {
        $_SESSION['recordado'] = false;
        unset($_SESSION['ultima_visita']);
        borrarCookiesRecordar();
    }

    redirigir('index-reg.php');
}

// 4. Si las credenciales no coinciden, guardar error en Flashdata y redirigir al formulario
establecerFlash('login_error', [
    'codigo'          => 'credenciales',
    'mensajes'        => [
        'Acceso denegado: el nombre de usuario no está registrado o la contraseña no es correcta.',
    ],
    'error_usuario'   => '',
    'error_password'  => '',
    'usuario_previo'  => $usuarioLimpio,
]);
redirigir($origen);
