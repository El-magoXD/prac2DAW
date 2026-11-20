<?php
/**
 * Práctica 7, 8 y 9 - Respuesta "Página enviar mensaje" (message-sent.php - parte pública restringida).
 * Valida el tipo de mensaje contra la tabla TiposMensajes de la BD (y las opciones de contact.php).
 */
require_once __DIR__ . '/includes/funciones.php';

$datos = !empty($_POST) ? $_POST : $_GET;
$origen = ($datos['origen'] ?? '') === 'contact.php' ? 'contact.php' : 'message.php';
if ($origen !== 'contact.php') {
    exigirAutenticacion();
}

$tituloPagina = 'Respuesta envío de mensaje | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$idRaw = $datos['id_anuncio'] ?? '';
$idAnuncio = (is_string($idRaw) && ctype_digit($idRaw) && (int) $idRaw > 0) ? (int) $idRaw : 1;

$tipoMensaje = trim((string) ($datos['tipo_mensaje'] ?? ''));
$textoMensaje = (string) ($datos['texto_mensaje'] ?? '');

$tiposValidos = [
    'ayuda'         => 'Ayuda',
    'accesibilidad' => 'Accesibilidad',
    'otro'          => 'Otro',
];

foreach (obtenerTiposMensajes() as $tm) {
    $tiposValidos[(string) $tm['IdTMensaje']] = (string) $tm['NomTMensaje'];
}

$errores = [];

if ($tipoMensaje === '' || !isset($tiposValidos[$tipoMensaje])) {
    $errores[] = 'El tipo de mensaje seleccionado no es válido. Debes elegir una opción válida de la lista.';
}

if (!tieneContenidoSinEspacios($textoMensaje)) {
    $errores[] = 'El texto del mensaje no puede estar vacío ni contener únicamente espacios en blanco o tabuladores.';
}
?>
<?php if (!empty($errores)): ?>
      <section>
        <h2>Error al enviar el mensaje</h2>
        <div class="resumen-errores" role="alert">
          <p class="resumen-errores-titulo">No se ha podido enviar el mensaje por los siguientes motivos:</p>
          <ul>
<?php foreach ($errores as $error): ?>
            <li><?= e($error) ?></li>
<?php endforeach; ?>
          </ul>
        </div>
        <p>
          <a class="btn-primary" href="<?= e($origen . ($origen === 'message.php' ? '?id=' . $idAnuncio : '')) ?>">Volver al formulario de envío de mensaje</a>
        </p>
      </section>
<?php else: ?>
      <section>
        <h2>Mensaje enviado</h2>
        <p>Tu mensaje se ha enviado con éxito. A continuación puedes comprobar los datos registrados:</p>
      </section>

      <section>
        <fieldset>
          <legend>Confirmación del mensaje</legend>
          <h3><u>Datos del mensaje</u></h3>

          <p><b>Tipo de mensaje:</b> <?= e($tiposValidos[$tipoMensaje]) ?></p>

          <p><b>Texto:</b> <?= e($textoMensaje) ?></p>
        </fieldset>
      </section>

      <section>
        <p>
<?php if ($origen === 'message.php'): ?>
          <a class="btn-primary" href="anuncio.php?id=<?= $idAnuncio ?>">Volver al anuncio</a>
<?php endif; ?>
          <a class="btn-primary" href="index.php">Volver a la página principal</a>
        </p>
      </section>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
