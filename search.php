<?php
/**
 * Práctica 7 - Página con el formulario de búsqueda de anuncios (search.php).
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

            <p>
              <label for="tipo_anuncio">Tipo de anuncio:</label>
              <select id="tipo_anuncio" name="tipo_anuncio">
                <option value="">Cualquiera</option>
                <option value="venta">Venta</option>
                <option value="alquiler">Alquiler</option>
              </select>
            </p>

            <p>
              <label for="tipo_vivienda">Tipo de vivienda:</label>
              <select id="tipo_vivienda" name="tipo_vivienda">
                <option value="">Cualquiera</option>
                <option value="obra_nueva">Obra nueva</option>
                <option value="vivienda">Vivienda</option>
                <option value="oficina">Oficina</option>
                <option value="local">Local</option>
                <option value="garaje">Garaje</option>
              </select>
            </p>

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
              <select id="pais" name="pais">
                <option value="">Selecciona un país</option>
                <option value="espana">España</option>
                <option value="francia">Francia</option>
                <option value="italia">Italia</option>
                <option value="alemania">Alemania</option>
                <option value="portugal">Portugal</option>
                <option value="paises-bajos">Países Bajos</option>
                <option value="belgica">Bélgica</option>
                <option value="irlanda">Irlanda</option>
                <option value="reino-unido">Reino Unido</option>
                <option value="estados-unidos">Estados Unidos</option>
              </select>
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
