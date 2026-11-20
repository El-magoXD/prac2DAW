<?php
/**
 * Práctica 7, 8 y 9 - Página "Crear anuncio" (crear-anuncio.php - parte privada).
 * Carga las listas desplegables de Tipo de anuncio, Tipo de vivienda y País desde la BD
 * mediante fragmentos reutilizables (Nota 45).
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

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

<?php
$esFormularioCrearAnuncio = true;
$tipoAnuncioSeleccionado = '';
$tipoViviendaSeleccionado = '';
require __DIR__ . '/includes/campos_busqueda_anuncio.php';
?>

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
                step="0.01"
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
<?php
$paisSeleccionado = '';
$textoOpcionVaciaPais = 'Selecciona un país';
$paisObligatorio = true;
require __DIR__ . '/includes/select_paises.php';
?>
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
              <label for="alt_foto_principal">Texto alternativo de la foto principal (*):</label>
              <input
                type="text"
                id="alt_foto_principal"
                name="alt_foto_principal"
                minlength="10"
                maxlength="255"
                placeholder="Descripción de la foto principal (mínimo 10 caracteres)"
                required
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
                step="0.01"
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
                type="number"
                id="planta"
                name="planta"
                placeholder="Ej. 4 (0 para Bajo, -1 para sótano)"
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
