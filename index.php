<?php
/**
 * Práctica 7 y 8 - Página principal (index.php).
 * - Si el usuario no está autenticado ni recordado: muestra el formulario de acceso con la opción
 *   "Recordarme en este equipo" y los posibles mensajes de error recuperados mediante Flashdata.
 * - Si el usuario está autenticado o recordado (Figura 2): muestra su saludo, la fecha y hora de su
 *   última visita (cuando está siendo recordado) y los botones "Acceder" y "Salir".
 */
$tituloPagina = 'PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$datosErrorFlash = obtenerFlash('login_error', []);
$codigoError = $datosErrorFlash['codigo'] ?? '';
$mensajesError = $datosErrorFlash['mensajes'] ?? [];
$errorCampoUsuario = $datosErrorFlash['error_usuario'] ?? '';
$errorCampoPassword = $datosErrorFlash['error_password'] ?? '';
$usuarioPrevio = $datosErrorFlash['usuario_previo'] ?? '';

$mensajeAccesoFlash = obtenerFlash('error_acceso', '');
if ($mensajeAccesoFlash !== '') {
    $mensajesError[] = $mensajeAccesoFlash;
}

$autenticado = usuarioAutenticado();
$usuarioActual = obtenerUsuarioActual();
$estaSiendoRecordado = !empty($_SESSION['recordado']) && !empty($_SESSION['ultima_visita']);
?>
      <section class="conBorde">
<?php if ($autenticado): ?>
        <h2>Sesión iniciada</h2>
        <p><strong><?= e(obtenerSaludoHorario($usuarioActual)) ?></strong></p>
<?php if ($estaSiendoRecordado): ?>
        <p>
          Hola <strong><?= e($usuarioActual) ?></strong>, su última visita fue el
          <strong><?= e($_SESSION['ultima_visita']) ?></strong>.
        </p>
<?php endif; ?>
        <p>
          <a class="btn-primary" href="index-reg.php">Acceder</a>
          <a class="btn-accent" href="salir.php">Salir</a>
        </p>
<?php else: ?>
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
            <input type="checkbox" id="recordarme" name="recordarme" value="1" />
            <label for="recordarme">Recordarme en este equipo</label>
          </p>

          <p>
            <button type="submit">Entrar</button>
          </p>
        </form>
<?php endif; ?>
      </section>

<?php
require_once __DIR__ . '/includes/ultimos_anuncios.php';
require_once __DIR__ . '/includes/pie.php';
?>
