<?php
/**
 * Práctica 7 y 8 - Página de aviso de acceso restringido (aviso.php).
 * Muestra el mensaje de acceso denegado (y los errores recuperados mediante Flashdata)
 * junto con el formulario de inicio de sesión y la opción "Recordarme en este equipo".
 */
$tituloPagina = 'Acceso denegado | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$datosErrorFlash = obtenerFlash('login_error', []);
$codigoError = $datosErrorFlash['codigo'] ?? '';
$mensajesError = $datosErrorFlash['mensajes'] ?? [];
$errorCampoUsuario = $datosErrorFlash['error_usuario'] ?? '';
$errorCampoPassword = $datosErrorFlash['error_password'] ?? '';
$usuarioPrevio = $datosErrorFlash['usuario_previo'] ?? '';

$mensajeAccesoRestringido = obtenerFlash('error_acceso', '');
?>
      <h2>Acceso restringido</h2>

<?php if ($mensajeAccesoRestringido !== ''): ?>
      <div class="resumen-errores" role="alert">
        <p class="resumen-errores-titulo"><?= e($mensajeAccesoRestringido) ?></p>
      </div>
<?php else: ?>
      <p>
        Para poder acceder a esta página o consultar el detalle de un anuncio debes iniciar sesión como usuario registrado.
      </p>
<?php endif; ?>

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
            <input type="checkbox" id="recordarme" name="recordarme" value="1" />
            <label for="recordarme">Recordarme en este equipo</label>
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
