<?php
/**
 * Práctica 7, 8 y 9 - Respuesta "Página con el formulario de registro como nuevo usuario" / "Mis datos"
 * (register-response.php). Comparte las mismas validaciones para ambos formularios y consulta
 * el nombre del país en la tabla Paises.
 */
require_once __DIR__ . '/includes/funciones.php';

iniciarSesionYRecordar();

$datos = !empty($_POST) ? $_POST : $_GET;
$modoFormulario = (($datos['modo_formulario'] ?? '') === 'mis_datos') ? 'mis_datos' : 'registro';

if ($modoFormulario === 'mis_datos') {
    exigirAutenticacion();
}

$usuario = $datos['usuario'] ?? '';
$password = $datos['password'] ?? '';
$password2 = $datos['password2'] ?? '';
$email = $datos['email'] ?? '';
$sexo = $datos['sexo'] ?? '';
$fechaNacimiento = $datos['fecha-nacimiento'] ?? '';
$ciudad = $datos['ciudad'] ?? '';
$pais = $datos['pais'] ?? '';

$errUsuario = '';
$errPassword = '';
$errPassword2 = '';
$errores = [];

// 1. Comprobar que se ha escrito algo en el nombre de usuario
if (!tieneContenidoSinEspacios($usuario)) {
    $errUsuario = 'El campo "Nombre de usuario" es obligatorio y no puede estar vacío.';
    $errores[] = $errUsuario;
}

// 2. Comprobar que se ha escrito algo en la contraseña
if (!tieneContenidoSinEspacios($password)) {
    $errPassword = 'El campo "Contraseña" es obligatorio y no puede estar vacío.';
    $errores[] = $errPassword;
}

// 3. Comprobar que se ha escrito algo en repetir contraseña y que ambas coinciden
if (!tieneContenidoSinEspacios($password2)) {
    $errPassword2 = 'El campo "Repetir contraseña" es obligatorio y no puede estar vacío.';
    $errores[] = $errPassword2;
} elseif ($password !== $password2) {
    $errPassword2 = 'Los campos "Contraseña" y "Repetir contraseña" no coinciden.';
    $errores[] = $errPassword2;
}

// Si hay errores, almacenar en Flashdata y redirigir al formulario correspondiente
if (!empty($errores)) {
    $claveFlash = ($modoFormulario === 'mis_datos') ? 'mis_datos_error' : 'registro_error';
    $destino = ($modoFormulario === 'mis_datos') ? 'mis-datos.php' : 'register.php';

    establecerFlash($claveFlash, [
        'usuario'          => $usuario,
        'password'         => $password,
        'password2'        => $password2,
        'email'            => $email,
        'sexo'             => $sexo,
        'fecha-nacimiento' => $fechaNacimiento,
        'ciudad'           => $ciudad,
        'pais'             => $pais,
        'err_usuario'      => $errUsuario,
        'err_password'     => $errPassword,
        'err_password2'    => $errPassword2,
        'mensajes'         => $errores,
    ]);
    redirigir($destino);
}

$esPaginaPrivada = ($modoFormulario === 'mis_datos');
$tituloPagina = $esPaginaPrivada
    ? 'Validación de Mis datos | PI - Pisos e Inmuebles'
    : 'Respuesta de registro | PI - Pisos e Inmuebles';

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$nombresSexo = [
    '1'      => 'Hombre',
    '2'      => 'Mujer',
    '3'      => 'Otro',
    'hombre' => 'Hombre',
    'mujer'  => 'Mujer',
    'otro'   => 'Otro',
];
?>
      <section>
        <h2><?= $esPaginaPrivada ? 'Datos validados correctamente' : 'Registro completado correctamente' ?></h2>
        <p>
          <?= $esPaginaPrivada
              ? 'Tus datos enviados desde "Mis datos" se han validado correctamente (la actualización en BD se implementará en la siguiente práctica):'
              : 'Inserción realizada, tus datos recibidos son los siguientes:' ?>
        </p>

        <fieldset>
          <legend>Datos de acceso registrados</legend>
          <p><strong>Nombre de usuario:</strong> <?= e(trim($usuario)) ?></p>
          <p><strong>Contraseña:</strong> <?= e($password) ?></p>
          <p><strong>Repetir contraseña:</strong> <?= e($password2) ?></p>
          <p><strong>Dirección de email:</strong> <?= tieneContenidoSinEspacios($email) ? e($email) : '<em>(No especificada)</em>' ?></p>
        </fieldset>

        <fieldset>
          <legend>Datos personales registrados</legend>
          <p>
            <strong>Sexo:</strong>
            <?= ($sexo !== '' && isset($nombresSexo[(string) $sexo])) ? e($nombresSexo[(string) $sexo]) : '<em>(No especificado)</em>' ?>
          </p>
          <p>
            <strong>Fecha de nacimiento:</strong>
            <?= tieneContenidoSinEspacios($fechaNacimiento) ? e($fechaNacimiento) : '<em>(No especificada)</em>' ?>
          </p>
          <p>
            <strong>Ciudad de residencia:</strong>
            <?= tieneContenidoSinEspacios($ciudad) ? e($ciudad) : '<em>(No especificada)</em>' ?>
          </p>
          <p>
            <strong>País de residencia:</strong>
            <?= tieneContenidoSinEspacios((string) $pais) ? e(obtenerNombrePais($pais)) : '<em>(No especificado)</em>' ?>
          </p>
        </fieldset>

        <p>
<?php if ($esPaginaPrivada): ?>
          <a class="btn-primary" href="mis-datos.php">Volver a Mis datos</a>
          <a class="btn-primary" href="index-reg.php">Volver al menú de usuario</a>
<?php else: ?>
          <a class="btn-primary" href="index.php">Volver a la página principal</a>
<?php endif; ?>
        </p>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
