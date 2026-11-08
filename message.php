<?php
/**
 * Práctica 7 - Página enviar mensaje al anunciante (message.php).
 * Nota: Validaciones HTML y JS desactivadas para comprobar la validación con PHP en message-sent.php.
 */
$tituloPagina = 'Enviar mensaje | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$idAnuncio = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : 1;
$tipoPrevio = $_GET['tipo_mensaje'] ?? '';
$textoPrevio = $_GET['texto_mensaje'] ?? '';
?>
      <section>
        <h2>Enviar mensaje al anunciante</h2>

        <form action="message-sent.php" method="post" novalidate>
          <input type="hidden" name="origen" value="message.php" />
          <input type="hidden" name="id_anuncio" value="<?= $idAnuncio ?>" />

          <fieldset>
            <legend>Detalles de tu mensaje</legend>

            <p>
              <label for="tipo_mensaje">Tipo de mensaje (*):</label>
              <select id="tipo_mensaje" name="tipo_mensaje">
                <option value="">Selecciona un tipo de mensaje</option>
                <option value="mas_informacion" <?= $tipoPrevio === 'mas_informacion' ? 'selected' : '' ?>>Más información</option>
                <option value="solicitar_cita" <?= $tipoPrevio === 'solicitar_cita' ? 'selected' : '' ?>>Solicitar una cita</option>
                <option value="comunicar_oferta" <?= $tipoPrevio === 'comunicar_oferta' ? 'selected' : '' ?>>Comunicar una oferta</option>
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
