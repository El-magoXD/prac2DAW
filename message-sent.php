<?php
/**
 * Práctica 7 - Respuesta "Página enviar mensaje" (message-sent.php).
 * Muestra una confirmación de que se ha enviado el mensaje y los datos introducidos por el usuario.
 * Comprueba mediante PHP que el tipo de mensaje es válido y que se ha escrito algo en el texto del mensaje.
 */
$tituloPagina = 'Respuesta envío de mensaje | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$datos = !empty($_POST) ? $_POST : $_GET;
$origen = ($datos['origen'] ?? '') === 'contact.php' ? 'contact.php' : 'message.php';
$idAnuncio = isset($datos['id_anuncio']) && is_numeric($datos['id_anuncio']) ? (int) $datos['id_anuncio'] : 1;

$tipoMensaje = $datos['tipo_mensaje'] ?? '';
$textoMensaje = $datos['texto_mensaje'] ?? '';

$tiposValidos = [
    'mas_informacion'  => 'Más información',
    'solicitar_cita'   => 'Solicitar una cita',
    'comunicar_oferta' => 'Comunicar una oferta',
    'ayuda'            => 'Ayuda',
    'accesibilidad'    => 'Accesibilidad',
    'otro'             => 'Otro',
];

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
          <a class="btn-primary" href="<?= e($origen . '?id=' . $idAnuncio) ?>">Volver al formulario de envío de mensaje</a>
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
          <a class="btn-primary" href="anuncio.php?id=<?= $idAnuncio ?>">Volver al anuncio</a>
          <a class="btn-primary" href="index.php">Volver a la página principal</a>
        </p>
      </section>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
