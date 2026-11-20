<?php
/**
 * Práctica 7 y 9 - Página con el formulario de búsqueda de anuncios (search.php).
 * Carga las listas desplegables de Tipo de anuncio, Tipo de vivienda y País desde la BD.
 */
$tituloPagina = 'Búsqueda de Anuncios | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
      <section>
        <h2>Buscar inmuebles</h2>
        <p>Rellena uno o varios campos para encontrar el anuncio que buscas:</p>

        <form action="result.php" method="get">
          <fieldset>
            <legend>Criterios de búsqueda</legend>

<?php
$esFormularioCrearAnuncio = false;
$tipoAnuncioSeleccionado = '';
$tipoViviendaSeleccionado = '';
require __DIR__ . '/includes/campos_busqueda_anuncio.php';
?>

            <p>
              <label for="ciudad">Ciudad:</label>
              <input
                type="text"
                id="ciudad"
                name="ciudad"
                placeholder="Ej. Alicante"
              />
            </p>

            <p>
              <label for="pais">País:</label>
<?php
$paisSeleccionado = '';
$textoOpcionVaciaPais = 'Cualquiera';
$paisObligatorio = false;
require __DIR__ . '/includes/select_paises.php';
?>
            </p>

            <p>
              <label for="precio_min">Precio entre:</label>
              <input
                type="number"
                id="precio_min"
                name="precio_min"
                min="0"
                placeholder="Mínimo"
              />
              <label for="precio_max">y</label>
              <input
                type="number"
                id="precio_max"
                name="precio_max"
                min="0"
                placeholder="Máximo"
              />
            </p>

            <p>
              <label for="fecha_min">Fecha entre:</label>
              <input type="date" id="fecha_min" name="fecha_min" />
              <label for="fecha_max">y</label>
              <input type="date" id="fecha_max" name="fecha_max" />
            </p>

            <p>
              <button type="submit">Buscar anuncios</button>
            </p>
          </fieldset>
        </form>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
