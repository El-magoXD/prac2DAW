<?php
/**
 * Práctica 7 y 8 - Respuesta "Página con el formulario de registro como nuevo usuario" (register-response.php).
 * Comprueba mediante PHP que se ha escrito algo en nombre de usuario, contraseña y repetir contraseña,
 * y que contraseña y repetir contraseña coinciden:
 * - Si hay errores: guarda los mensajes de error y los datos previos en Flashdata ($_SESSION['flash'])
 *   y redirige inmediatamente a register.php sin usar la URL.
 * - Si todo es correcto: muestra los datos que el usuario ha introducido (sin la foto de perfil).
 */
require_once __DIR__ . '/includes/funciones.php';

iniciarSesionYRecordar();

$datos = !empty($_POST) ? $_POST : $_GET;

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

// Si hay errores, almacenar en Flashdata y redirigir a register.php (Práctica 8, pág. 4)
if (!empty($errores)) {
    establecerFlash('registro_error', [
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
    redirigir('register.php');
}

$tituloPagina = 'Respuesta de registro | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$nombresSexo = [
    'hombre' => 'Hombre',
    'mujer'  => 'Mujer',
    'otro'   => 'Otro',
];
?>
      <section>
        <h2>Registro completado correctamente</h2>
        <p>Inserción realizada, tus datos recibidos son los siguientes:</p>

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
            <?= ($sexo !== '' && isset($nombresSexo[$sexo])) ? e($nombresSexo[$sexo]) : '<em>(No especificado)</em>' ?>
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
            <?= tieneContenidoSinEspacios($pais) ? e(obtenerNombrePais($pais)) : '<em>(No especificado)</em>' ?>
          </p>
        </fieldset>

        <p>
          <a class="btn-primary" href="index.php">Volver a la página principal</a>
        </p>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
