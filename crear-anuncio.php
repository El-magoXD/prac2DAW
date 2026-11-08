<?php
/**
 * Práctica 7 - Página "Crear anuncio" (crear-anuncio.php).
 * Contiene un formulario con todos los datos necesarios para crear un anuncio nuevo.
 */
$tituloPagina = 'Crear anuncio | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
      <section>
        <h2>Crear un nuevo anuncio</h2>
        <p>Rellena la información general y las características del inmueble que deseas publicar:</p>

        <form action="mis-anuncios.php" method="post" enctype="multipart/form-data">
          <fieldset>
            <legend>Datos generales del anuncio</legend>

            <p>
              <label for="tipo_anuncio">Tipo de anuncio (*):</label>
              <select id="tipo_anuncio" name="tipo_anuncio" required>
                <option value="">Selecciona el tipo de anuncio</option>
                <option value="venta">Venta</option>
                <option value="alquiler">Alquiler</option>
              </select>
            </p>

            <p>
              <label for="tipo_vivienda">Tipo de vivienda (*):</label>
              <select id="tipo_vivienda" name="tipo_vivienda" required>
                <option value="">Selecciona el tipo de vivienda</option>
                <option value="obra_nueva">Obra nueva</option>
                <option value="vivienda">Vivienda</option>
                <option value="oficina">Oficina</option>
                <option value="local">Local</option>
                <option value="garaje">Garaje</option>
              </select>
            </p>

            <p>
              <label for="titulo">Título del anuncio (*):</label>
              <input
                type="text"
                id="titulo"
                name="titulo"
                placeholder="Ej. Piso luminoso en Alicante"
                required
              />
            </p>

            <p>
              <label for="precio">Precio (€) (*):</label>
              <input
                type="number"
                id="precio"
                name="precio"
                min="0"
                step="1"
                placeholder="Ej. 185000"
                required
              />
            </p>

            <p>
              <label for="ciudad">Ciudad (*):</label>
              <input
                type="text"
                id="ciudad"
                name="ciudad"
                placeholder="Ej. Alicante"
                required
              />
            </p>

            <p>
              <label for="pais">País (*):</label>
              <select id="pais" name="pais" required>
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
              <label for="texto">Texto descriptivo (*):</label>
              <textarea
                id="texto"
                name="texto"
                rows="6"
                placeholder="Describe con detalle el inmueble, su estado y ubicación..."
                required
              ></textarea>
            </p>

            <p>
              <label for="foto_principal">Foto principal:</label>
              <input
                type="file"
                id="foto_principal"
                name="foto_principal"
                accept="image/*"
              />
            </p>

            <p>
              <label for="alt_foto_principal">Texto alternativo de la foto principal:</label>
              <input
                type="text"
                id="alt_foto_principal"
                name="alt_foto_principal"
                minlength="10"
                placeholder="Descripción de la foto principal (mínimo 10 caracteres)"
              />
            </p>
          </fieldset>

          <fieldset>
            <legend>Características de la vivienda</legend>

            <p>
              <label for="superficie">Superficie (m²) (*):</label>
              <input
                type="number"
                id="superficie"
                name="superficie"
                min="1"
                placeholder="Ej. 90"
                required
              />
            </p>

            <p>
              <label for="habitaciones">Número de habitaciones (*):</label>
              <input
                type="number"
                id="habitaciones"
                name="habitaciones"
                min="0"
                placeholder="Ej. 3"
                required
              />
            </p>

            <p>
              <label for="banos">Número de baños (*):</label>
              <input
                type="number"
                id="banos"
                name="banos"
                min="0"
                placeholder="Ej. 2"
                required
              />
            </p>

            <p>
              <label for="planta">Planta (*):</label>
              <input
                type="text"
                id="planta"
                name="planta"
                placeholder="Ej. 4ª planta / Bajo"
                required
              />
            </p>

            <p>
              <label for="anio_construccion">Año de construcción (*):</label>
              <input
                type="number"
                id="anio_construccion"
                name="anio_construccion"
                min="1800"
                max="2026"
                placeholder="Ej. 2010"
                required
              />
            </p>
          </fieldset>

          <p>
            <button type="submit">Publicar anuncio</button>
            <button type="reset">Limpiar formulario</button>
            <a class="btn-primary" href="mis-anuncios.php">Volver a Mis anuncios</a>
          </p>
        </form>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
