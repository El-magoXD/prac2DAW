<?php
/**
 * Práctica 7, 8 y 9 - Página con el formulario de registro como nuevo usuario (register.php).
 * Carga los países desde la tabla Paises mediante el formulario común includes/formulario_usuario.php.
 */
$tituloPagina = 'Registrarse | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$flashRegistro = obtenerFlash('registro_error', []);

$valoresUsuario = [
    'usuario'          => $flashRegistro['usuario'] ?? '',
    'password'         => $flashRegistro['password'] ?? '',
    'password2'        => $flashRegistro['password2'] ?? '',
    'email'            => $flashRegistro['email'] ?? '',
    'sexo'             => $flashRegistro['sexo'] ?? '',
    'fecha-nacimiento' => $flashRegistro['fecha-nacimiento'] ?? '',
    'ciudad'           => $flashRegistro['ciudad'] ?? '',
    'pais'             => $flashRegistro['pais'] ?? '',
];

$erroresUsuario = [
    'err_usuario'   => $flashRegistro['err_usuario'] ?? '',
    'err_password'  => $flashRegistro['err_password'] ?? '',
    'err_password2' => $flashRegistro['err_password2'] ?? '',
    'mensajes'      => $flashRegistro['mensajes'] ?? [],
];

$modoFormularioUsuario = 'registro';
?>
      <h2>Registro de nuevo usuario</h2>

<?php
require __DIR__ . '/includes/formulario_usuario.php';
?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
