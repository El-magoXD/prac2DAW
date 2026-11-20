<?php
/**
 * Práctica 9 - Fragmento reutilizable para mostrar la lista desplegable de países
 * a partir de la tabla Paises de la base de datos.
 */
$listaPaises = obtenerPaises();
$paisSeleccionadoActual = isset($paisSeleccionado) ? (string) $paisSeleccionado : '';
$textoVacioPais = $textoOpcionVaciaPais ?? 'Selecciona un país';
$esPaisObligatorio = !empty($paisObligatorio);
?>
            <select id="<?= e($idSelectPais ?? 'pais') ?>" name="<?= e($nameSelectPais ?? 'pais') ?>"<?= $esPaisObligatorio ? ' required' : '' ?>>
              <option value=""><?= e($textoVacioPais) ?></option>
<?php foreach ($listaPaises as $paisFila): ?>
              <option value="<?= (int) $paisFila['IdPais'] ?>" <?= $paisSeleccionadoActual === (string) $paisFila['IdPais'] ? 'selected' : '' ?>>
                <?= e($paisFila['NomPais']) ?>
              </option>
<?php endforeach; ?>
            </select>
