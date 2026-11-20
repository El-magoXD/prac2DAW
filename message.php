<?php
/**
 * Práctica 7, 8 y 9 - Página enviar mensaje al anunciante (message.php - parte pública restringida).
 * Carga los tipos de mensaje en la lista desplegable desde la tabla TiposMensajes de la BD.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$tituloPagina = 'Enviar mensaje | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$idRaw = $_GET['id'] ?? '';
$idAnuncio = (is_string($idRaw) && ctype_digit($idRaw) && (int) $idRaw > 0) ? (int) $idRaw : 1;
$anuncioDestino = obtenerDatosAnuncioPorId($idAnuncio);

$tipoPrevio = (string) ($_GET['tipo_mensaje'] ?? '');
$textoPrevio = (string) ($_GET['texto_mensaje'] ?? '');
$tiposMensajes = obtenerTiposMensajes();
?>
      <section>
        <h2>Enviar mensaje al anunciante</h2>
<?php if ($anuncioDestino !== null): ?>
        <p>
          Anuncio seleccionado: <strong><?= e($anuncioDestino['titulo']) ?></strong>
          (anunciante: <strong><?= e($anuncioDestino['usuario']) ?></strong>).
        </p>
<?php endif; ?>

        <form action="message-sent.php" method="post" novalidate>
          <input type="hidden" name="origen" value="message.php" />
          <input type="hidden" name="id_anuncio" value="<?= $idAnuncio ?>" />

          <fieldset>
            <legend>Detalles de tu mensaje</legend>

            <p>
              <label for="tipo_mensaje">Tipo de mensaje (*):</label>
              <select id="tipo_mensaje" name="tipo_mensaje">
                <option value="">Selecciona un tipo de mensaje</option>
<?php foreach ($tiposMensajes as $tm): ?>
                <option value="<?= (int) $tm['IdTMensaje'] ?>" <?= $tipoPrevio === (string) $tm['IdTMensaje'] ? 'selected' : '' ?>>
                  <?= e($tm['NomTMensaje']) ?>
                </option>
<?php endforeach; ?>
              </select>
            </p>

            <p>
              <label for="texto_mensaje">Texto (*):</label>
              <textarea
                id="texto_mensaje"
                name="texto_mensaje"
                rows="10"
                cols="60"
                placeholder="Escribe aquí tu mensaje para el anunciante..."
              ><?= e($textoPrevio) ?></textarea>
            </p>

            <p>
              <button type="submit">Enviar mensaje</button>
            </p>
          </fieldset>
        </form>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
