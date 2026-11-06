<?php
/**
 * Práctica 7 - Página "Añadir foto a anuncio" (anadir-foto.php).
 * Contiene un formulario con los datos necesarios para añadir una foto (foto, texto alternativo
 * obligatorio de mínimo 10 caracteres, título y anuncio al que se añade la foto).
 * - Si se accede desde el menú de la parte privada (sin parámetro 'anuncio'), muestra la lista desplegable
 *   sin ningún anuncio seleccionado por defecto para que el usuario lo elija.
 * - Si se accede desde "Mis anuncios" o "Ver anuncio" (con $_GET['anuncio']), el anuncio ya aparece
 *   seleccionado en la lista desplegable y no se puede cambiar.
 */
$tituloPagina = 'Añadir foto a anuncio | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$anunciosDisponibles = [
    1 => 'Piso luminoso en Alicante',
    2 => 'Apartamento moderno en Valencia',
    3 => 'Piso reformado en Madrid',
];

$anuncioPreseleccionado = null;
if (isset($_GET['anuncio']) && $_GET['anuncio'] !== '') {
    $idCandidato = (int) $_GET['anuncio'];
    if (isset($anunciosDisponibles[$idCandidato])) {
        $anuncioPreseleccionado = $idCandidato;
    }
}
?>
      <section>
        <h2>Añadir foto a un anuncio</h2>
        <p>
          Completa los datos para incorporar una nueva fotografía a tu anuncio.
          Recuerda que todas las imágenes deben contar con un texto alternativo descriptivo de al menos 10 caracteres.
        </p>

        <form action="mis-anuncios.php" method="post" enctype="multipart/form-data">
          <fieldset>
            <legend>Datos de la fotografía</legend>

            <p>
              <label for="anuncio">Anuncio al que se añade la foto (*):</label>
<?php if ($anuncioPreseleccionado !== null): ?>
              <select id="anuncio" name="anuncio_vista" disabled aria-readonly="true">
<?php foreach ($anunciosDisponibles as $id => $titulo): ?>
                <option value="<?= $id ?>" <?= $id === $anuncioPreseleccionado ? 'selected' : '' ?>>
                  <?= e($titulo) ?>
                </option>
<?php endforeach; ?>
              </select>
              <input type="hidden" name="anuncio" value="<?= $anuncioPreseleccionado ?>" />
              <small>Anuncio seleccionado automáticamente desde la página anterior (no modificable).</small>
<?php else: ?>
              <select id="anuncio" name="anuncio" required>
                <option value="" selected>Selecciona un anuncio</option>
<?php foreach ($anunciosDisponibles as $id => $titulo): ?>
                <option value="<?= $id ?>"><?= e($titulo) ?></option>
<?php endforeach; ?>
              </select>
<?php endif; ?>
            </p>

            <p>
              <label for="titulo_foto">Título de la foto (*):</label>
              <input
                type="text"
                id="titulo_foto"
                name="titulo_foto"
                placeholder="Ej. Salón principal con luz natural"
                required
              />
            </p>

            <p>
              <label for="texto_alternativo">Texto alternativo (*):</label>
              <input
                type="text"
                id="texto_alternativo"
                name="texto_alternativo"
                minlength="10"
                placeholder="Describe detalladamente la imagen (mínimo 10 caracteres)"
                required
              />
              <small>Obligatorio (longitud mínima de 10 caracteres).</small>
            </p>

            <p>
              <label for="foto">Archivo de imagen (*):</label>
              <input
                type="file"
                id="foto"
                name="foto"
                accept="image/*"
                required
              />
            </p>

            <p>
              <button type="submit">Añadir foto</button>
              <a class="btn-primary" href="mis-anuncios.php">Cancelar y volver a Mis anuncios</a>
            </p>
          </fieldset>
        </form>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
