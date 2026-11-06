<?php
/**
 * Práctica 7 - Página principal con formulario de acceso, últimos anuncios y búsqueda rápida.
 * Nota: Se han desactivado las validaciones HTML y JS del formulario de login para comprobar la validación en PHP (acceso.php).
 */
$tituloPagina = 'PI - Pisos e Inmuebles';
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
      <section class="conBorde">
        <h2>Iniciar sesión</h2>

        <form action="acceso.php" method="post" novalidate>
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
            <small>Usuarios de prueba permitidos: <code>usuario1</code> / <code>Pass123</code>, <code>juan</code> / <code>Juan123</code>, <code>maria</code> / <code>Maria123</code>, <code>admin</code> / <code>Admin123</code>.</small>
          </p>

          <p>
            <button type="submit">Entrar</button>
          </p>
        </form>
      </section>

<?php
require_once __DIR__ . '/includes/ultimos_anuncios.php';
require_once __DIR__ . '/includes/pie.php';
?>
