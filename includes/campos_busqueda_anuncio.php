<?php
/**
 * Práctica 9 - Fragmento reutilizable con los desplegables de Tipo de anuncio y Tipo de vivienda
 * cargados desde las tablas TiposAnuncios y TiposViviendas (Nota 45: compartido entre búsqueda y crear anuncio).
 */
$listaTiposAnuncios = obtenerTiposAnuncios();
$listaTiposViviendas = obtenerTiposViviendas();

$tipoAnuncioSel = isset($tipoAnuncioSeleccionado) ? (string) $tipoAnuncioSeleccionado : '';
$tipoViviendaSel = isset($tipoViviendaSeleccionado) ? (string) $tipoViviendaSeleccionado : '';
$camposObligatorios = !empty($esFormularioCrearAnuncio);
$textoVacioAnuncio = $camposObligatorios ? 'Selecciona el tipo de anuncio' : 'Cualquiera';
$textoVacioVivienda = $camposObligatorios ? 'Selecciona el tipo de vivienda' : 'Cualquiera';
?>
            <p>
              <label for="tipo_anuncio">Tipo de anuncio<?= $camposObligatorios ? ' (*)' : '' ?>:</label>
              <select id="tipo_anuncio" name="tipo_anuncio"<?= $camposObligatorios ? ' required' : '' ?>>
                <option value=""><?= e($textoVacioAnuncio) ?></option>
<?php foreach ($listaTiposAnuncios as $ta): ?>
                <option value="<?= (int) $ta['IdTAnuncio'] ?>" <?= $tipoAnuncioSel === (string) $ta['IdTAnuncio'] ? 'selected' : '' ?>>
                  <?= e($ta['NomTAnuncio']) ?>
                </option>
<?php endforeach; ?>
              </select>
            </p>

            <p>
              <label for="tipo_vivienda">Tipo de vivienda<?= $camposObligatorios ? ' (*)' : '' ?>:</label>
              <select id="tipo_vivienda" name="tipo_vivienda"<?= $camposObligatorios ? ' required' : '' ?>>
                <option value=""><?= e($textoVacioVivienda) ?></option>
<?php foreach ($listaTiposViviendas as $tv): ?>
                <option value="<?= (int) $tv['IdTVivienda'] ?>" <?= $tipoViviendaSel === (string) $tv['IdTVivienda'] ? 'selected' : '' ?>>
                  <?= e($tv['NomTVivienda']) ?>
                </option>
<?php endforeach; ?>
              </select>
            </p>
