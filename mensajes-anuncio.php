<?php
/**
 * Práctica 9 - Página "Mensajes" de un anuncio (mensajes-anuncio.php - parte privada).
 * Muestra la información básica del anuncio, el número total de mensajes recibidos en ese anuncio
 * y el listado de todos los mensajes recibidos.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$idRaw = $_GET['id'] ?? '';
$idAnuncio = (is_string($idRaw) && ctype_digit($idRaw) && (int) $idRaw > 0) ? (int) $idRaw : 0;
$anuncio = ($idAnuncio > 0) ? obtenerDatosAnuncioPorId($idAnuncio) : null;
$mensajesAnuncio = ($anuncio !== null) ? obtenerMensajesPorAnuncio($idAnuncio) : [];

$tituloPagina = ($anuncio !== null)
    ? 'Mensajes de ' . $anuncio['titulo'] . ' | PI - Pisos e Inmuebles'
    : 'Anuncio no encontrado | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
<?php if ($anuncio === null): ?>
      <section class="conBorde">
        <h2>Anuncio no encontrado</h2>
        <p>El identificador de anuncio solicitado no es válido o no existe en la base de datos.</p>
        <p><a class="btn-primary" href="mis-anuncios.php">Volver a Mis anuncios</a></p>
      </section>
<?php else: ?>
      <section class="conBorde">
        <h2>Mensajes recibidos en el anuncio</h2>
        <h3><?= e($anuncio['titulo']) ?></h3>
        <ul>
          <li><strong>Tipo:</strong> <?= e($anuncio['tipo_anuncio']) ?> — <?= e($anuncio['tipo_vivienda']) ?></li>
          <li><strong>Ciudad:</strong> <?= e($anuncio['ciudad']) ?> (<?= e($anuncio['pais']) ?>)</li>
          <li><strong>Precio:</strong> <?= e($anuncio['precio']) ?></li>
          <li><strong>Número total de mensajes recibidos:</strong> <?= count($mensajesAnuncio) ?></li>
        </ul>
        <p>
          <a class="btn-primary" href="ver-anuncio.php?id=<?= (int) $anuncio['id'] ?>">Volver al anuncio</a>
          <a class="btn-primary" href="mis-anuncios.php">Volver a Mis anuncios</a>
        </p>
      </section>

      <section>
        <h2>Listado de mensajes (<?= count($mensajesAnuncio) ?>)</h2>
<?php if (empty($mensajesAnuncio)): ?>
        <p>Este anuncio todavía no ha recibido ningún mensaje.</p>
<?php else: ?>
<?php foreach ($mensajesAnuncio as $msg): ?>
        <article>
          <fieldset>
            <p>
              <strong>De:</strong>
              <a href="perfil-usuario.php?id=<?= (int) $msg['IdRemitente'] ?>">
                <?= e($msg['NomRemitente']) ?>
              </a>
            </p>
            <p><strong>Tipo de mensaje:</strong> <?= e($msg['NomTMensaje']) ?></p>
            <p><strong>Texto:</strong> <?= e($msg['Texto']) ?></p>
            <p><strong>Fecha:</strong> <?= e(formatearFechaBD($msg['FRegistro'], true)) ?></p>
          </fieldset>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
