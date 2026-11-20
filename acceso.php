<?php
/**
 * Práctica 7, 8 y 9 - Página control de acceso (no visible).
 * Valida las credenciales contra la tabla Usuarios de la base de datos pibd
 * mediante sentencias preparadas mysqli y verificación de contraseña cifrada (password_verify).
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

$usuarioLimpio = trim((string) $usuario);

// 2. Comprobar que la conexión a la base de datos funciona antes de consultar
$conexionPrueba = obtenerConexionBD();
if (!$conexionPrueba instanceof mysqli) {
    establecerFlash('login_error', [
        'codigo'          => 'error_bd',
        'mensajes'        => [
            'Error interno: no se ha podido establecer conexión con la base de datos.',
        ],
        'error_usuario'   => '',
        'error_password'  => '',
        'usuario_previo'  => $usuarioLimpio,
    ]);
    redirigir($origen);
}
$conexionPrueba->close();

// 3. Consultar el usuario en la tabla Usuarios mediante sentencia preparada
$datosUsuario = obtenerUsuarioPorNombre($usuarioLimpio);

$credencialesCorrectas = false;
if ($datosUsuario !== null && isset($datosUsuario['Clave'])) {
    $claveAlmacenada = (string) $datosUsuario['Clave'];
    $credencialesCorrectas = password_verify($password, $claveAlmacenada) || hash_equals($claveAlmacenada, (string) $password);
}

if ($credencialesCorrectas) {
    $_SESSION['id_usuario'] = (int) $datosUsuario['IdUsuario'];
    $_SESSION['usuario'] = (string) $datosUsuario['NomUsuario'];
    $_SESSION['estilo'] = (string) $datosUsuario['EstiloFichero'];
    $_SESSION['titulo_estilo'] = (string) $datosUsuario['EstiloNombre'];

    if ($recordarme) {
        $ahoraTexto = date('d/m/Y \a \l\a\s H:i');
        $expira90Dias = time() + (90 * 24 * 60 * 60);
        $tokenUsuario = generarTokenRecordar(
            (int) $datosUsuario['IdUsuario'],
            (string) $datosUsuario['NomUsuario'],
            (string) $datosUsuario['Clave']
        );

        $_SESSION['recordado'] = true;
        $_SESSION['ultima_visita'] = $ahoraTexto;

        enviarCookieSegura('recordar_token', $tokenUsuario, $expira90Dias);
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
