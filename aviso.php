<?php
/**
 * Práctica 7 - Página de aviso de acceso restringido con formulario de control de acceso.
 * Validaciones HTML y JS desactivadas para comprobar la validación en servidor (acceso.php).
 */
$tituloPagina = 'Acceso denegado | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$codigoError = $_GET['error'] ?? '';
$usuarioPrevio = $_GET['usuario'] ?? '';
$mensajesError = [];
$errorCampoUsuario = '';
$errorCampoPassword = '';

if ($codigoError === 'ambos_vacios') {
    $errorCampoUsuario = 'El nombre de usuario no puede estar vacío ni contener solo espacios o tabuladores.';
    $errorCampoPassword = 'La contraseña no puede estar vacía ni contener solo espacios o tabuladores.';
    $mensajesError[] = $errorCampoUsuario;
    $mensajesError[] = $errorCampoPassword;
} elseif ($codigoError === 'usuario_vacio') {
    $errorCampoUsuario = 'El nombre de usuario no puede estar vacío ni contener solo espacios o tabuladores.';
    $mensajesError[] = $errorCampoUsuario;
} elseif ($codigoError === 'password_vacio') {
    $errorCampoPassword = 'La contraseña no puede estar vacía ni contener solo espacios o tabuladores.';
    $mensajesError[] = $errorCampoPassword;
} elseif ($codigoError === 'credenciales') {
    $mensajesError[] = 'Acceso denegado: el nombre de usuario no está registrado o la contraseña no es correcta.';
} elseif ($codigoError === 'fichero_usuarios') {
    $mensajesError[] = 'Error interno: no se ha podido acceder al fichero de usuarios permitidos.';
}
?>
      <h2>Acceso restringido</h2>

      <p>
        Para poder acceder a esta funcionalidad debes iniciar sesión con un usuario registrado.
      </p>

      <section class="conBorde">
        <h2>Iniciar sesión</h2>

        <form action="acceso.php" method="post" novalidate>
          <input type="hidden" name="origen" value="aviso.php" />
<?php if (!empty($mensajesError)): ?>
          <div class="resumen-errores" role="alert">
            <p class="resumen-errores-titulo">No se ha podido iniciar sesión:</p>
            <ul>
<?php foreach ($mensajesError as $msg): ?>
              <li><?= e($msg) ?></li>
<?php endforeach; ?>
            </ul>
          </div>
<?php endif; ?>

          <p>
            <label for="usuario" class="<?= $errorCampoUsuario !== '' ? 'etiqueta-error' : '' ?>">
              <i class="fa-solid fa-user" aria-label="Usuario" role="img"></i> Nombre de usuario:
            </label>
            <input
              type="text"
              id="usuario"
              name="usuario"
              value="<?= e($usuarioPrevio) ?>"
              class="<?= ($errorCampoUsuario !== '' || $codigoError === 'credenciales') ? 'campo-error' : '' ?>"
            />
<?php if ($errorCampoUsuario !== ''): ?>
            <span class="mensaje-error" role="alert"><?= e($errorCampoUsuario) ?></span>
<?php endif; ?>
          </p>

          <p>
            <label for="password" class="<?= $errorCampoPassword !== '' ? 'etiqueta-error' : '' ?>">
              <i class="fa-solid fa-lock" aria-label="Contraseña" role="img"></i> Contraseña:
            </label>
            <input
              type="password"
              id="password"
              name="password"
              class="<?= ($errorCampoPassword !== '' || $codigoError === 'credenciales') ? 'campo-error' : '' ?>"
            />
<?php if ($errorCampoPassword !== ''): ?>
            <span class="mensaje-error" role="alert"><?= e($errorCampoPassword) ?></span>
<?php endif; ?>
          </p>

          <p>
            <button type="submit">Entrar</button>
          </p>
        </form>
      </section>

      <p>
        ¿Todavía no tienes una cuenta?
        <a href="register.php">Regístrate aquí</a>.
      </p>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
