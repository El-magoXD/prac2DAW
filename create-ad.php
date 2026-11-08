<?php
/**
 * Práctica 7 - Página "Solicitar folleto" (create-ad.php).
 * Muestra la tabla de tarifas, la tabla de posibles costes calculada con PHP (Figura 2),
 * conserva la tabla generada con JavaScript para comparar que coinciden, y el formulario de solicitud.
 */
$tituloPagina = 'Solicitar folleto | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;
$scriptsPagina = ['js/utilidades.js', 'js/folleto.js'];

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
      <h2>Solicitar un folleto publicitario</h2>
      <p>
        Elige uno de tus anuncios y personaliza el folleto que enviaremos a la
        dirección indicada. El número de páginas y fotos depende del anuncio
        seleccionado. El coste de impresión se calcula por página y foto, según
        el tipo de impresión y la resolución, y el número de copias multiplica el
        precio unitario del folleto publicitario.
      </p>

      <section>
        <h3>Tarifas de impresión</h3>
        <p>
          Tarifas de referencia en euros por página o foto. La resolución elegida
          se aplica a las fotos.
        </p>

        <table>
          <caption>
            Tarifas
          </caption>
          <tr>
            <th scope="col">Concepto</th>
            <th scope="col">Tarifa</th>
          </tr>
          <tr>
            <th scope="row">Coste procesamiento y envío</th>
            <td>10 €</td>
          </tr>
          <tr>
            <th scope="row">&lt; 5 páginas</th>
            <td>2 € por pág.</td>
          </tr>
          <tr>
            <th scope="row">entre 5 y 10 páginas</th>
            <td>1.8 € por pág.</td>
          </tr>
          <tr>
            <th scope="row">&gt; 10 páginas</th>
            <td>1.6 € por pág.</td>
          </tr>
          <tr>
            <th scope="row">Blanco y negro</th>
            <td>0 €</td>
          </tr>
          <tr>
            <th scope="row">Color</th>
            <td>0.5 € por foto</td>
          </tr>
          <tr>
            <th scope="row">Resolución &lt;= 300 dpi</th>
            <td>0 € por foto</td>
          </tr>
          <tr>
            <th scope="row">Resolución &gt; 300 dpi</th>
            <td>0.2 € por foto</td>
          </tr>
        </table>

        <h3>Tabla con posibles costes de un folleto impreso (calculada con PHP)</h3>
        <table class="tabla-costes">
          <caption>
            Tabla con posibles costes de un álbum impreso calculada en servidor mediante PHP
          </caption>
          <thead>
            <tr>
              <th scope="col" rowspan="2">Número de páginas</th>
              <th scope="col" rowspan="2">Número de fotos</th>
              <th scope="colgroup" colspan="2">Blanco y negro</th>
              <th scope="colgroup" colspan="2">Color</th>
            </tr>
            <tr>
              <th scope="col">150-300 dpi</th>
              <th scope="col">450-900 dpi</th>
              <th scope="col">150-300 dpi</th>
              <th scope="col">450-900 dpi</th>
            </tr>
          </thead>
          <tbody>
<?php for ($paginas = 1; $paginas <= 15; $paginas++): ?>
<?php
    $fotos = $paginas * 3;
    $costeBN150 = calcularCosteFolleto($paginas, $fotos, false, false);
    $costeBN450 = calcularCosteFolleto($paginas, $fotos, false, true);
    $costeColor150 = calcularCosteFolleto($paginas, $fotos, true, false);
    $costeColor450 = calcularCosteFolleto($paginas, $fotos, true, true);
?>
            <tr>
              <td><?= $paginas ?></td>
              <td><?= $fotos ?></td>
              <td><?= e(formatearEuros($costeBN150)) ?></td>
              <td><?= e(formatearEuros($costeBN450)) ?></td>
              <td><?= e(formatearEuros($costeColor150)) ?></td>
              <td><?= e(formatearEuros($costeColor450)) ?></td>
            </tr>
<?php endfor; ?>
          </tbody>
        </table>

        <p>
          <button type="button" id="btn-mostrar-costes" aria-expanded="false">
            Mostrar tabla de posibles costes (JavaScript)
          </button>
        </p>
        <div id="contenedor-tabla-costes" hidden></div>
      </section>

      <form action="ad-response.php" method="post">
        <fieldset>
          <legend>Personalización del folleto</legend>

          <p>
            <em>Los campos marcados con un asterisco (*) son obligatorios.</em>
          </p>

          <p>
            <label for="anuncio">Anuncio propio (*):</label>
            <select id="anuncio" name="anuncio" required>
              <option value="">Selecciona uno de tus anuncios</option>
              <option value="piso-alicante">
                Piso luminoso en Alicante (5 páginas, 6 fotos)
              </option>
              <option value="apartamento-valencia">
                Apartamento moderno en Valencia (8 páginas, 15 fotos)
              </option>
            </select>
            <small>Solo puedes solicitar folletos de anuncios publicados por ti.</small>
          </p>

          <p>
            <label for="texto-adicional">
              Texto adicional (opcional, máximo 4000 caracteres):
            </label>
            <textarea
              id="texto-adicional"
              name="texto_adicional"
              rows="6"
              maxlength="4000"
            ></textarea>
          </p>

          <p>
            <label for="color-portada">Color de la portada (*):</label>
            <input
              type="color"
              id="color-portada"
              name="color_portada"
              value="#000000"
            />
          </p>

          <p>
            <label for="copias">Número de copias (1-99) (*):</label>
            <input
              type="number"
              id="copias"
              name="copias"
              min="1"
              max="99"
              value="1"
              required
            />
          </p>

          <p>
            <label for="resolucion">Resolución de las fotos (*):</label>
            <input
              type="range"
              id="resolucion"
              name="resolucion"
              min="150"
              max="900"
              step="150"
              value="150"
            />
            <output id="salida_resolucion">150 DPI</output>
          </p>

          <fieldset>
            <legend>Impresión</legend>
            <p>¿Cómo quieres imprimir el folleto? (*)</p>
            <p>
              <input
                type="radio"
                id="blanco-negro"
                name="impresion"
                value="blanco-negro"
                checked
                required
              />
              <label for="blanco-negro">Blanco y negro</label>
            </p>
            <p>
              <input
                type="radio"
                id="todo-color"
                name="impresion"
                value="color"
              />
              <label for="todo-color">A todo color</label>
            </p>

            <p>¿Quieres que aparezca el precio del anuncio? (*)</p>
            <p>
              <input
                type="radio"
                id="con-precio"
                name="mostrar_precio"
                value="si"
                checked
                required
              />
              <label for="con-precio">Sí, imprimir el precio</label>
            </p>
            <p>
              <input
                type="radio"
                id="sin-precio"
                name="mostrar_precio"
                value="no"
              />
              <label for="sin-precio">No imprimir el precio</label>
            </p>
          </fieldset>
        </fieldset>

        <fieldset>
          <legend>Datos del destinatario</legend>

          <p>
            <label for="nombre">Nombre y apellidos (máximo 200 caracteres) (*):</label>
            <input
              type="text"
              id="nombre"
              name="nombre"
              maxlength="200"
              autocomplete="name"
              required
            />
          </p>

          <p>
            <label for="email">Correo electrónico (máximo 200 caracteres) (*):</label>
            <input
              type="email"
              id="email"
              name="email"
              maxlength="200"
              autocomplete="email"
              required
            />
          </p>

          <p>
            <label for="calle">Dirección (*):</label>
            <input
              type="text"
              id="calle"
              name="calle"
              autocomplete="address-line1"
              placeholder="Calle"
              required
            />
            <input
              type="text"
              id="numero"
              name="numero"
              placeholder="Número"
              required
            />
            <input
              type="text"
              id="piso"
              name="piso"
              placeholder="Piso"
              required
            />
            <input
              type="text"
              id="puerta"
              name="puerta"
              placeholder="Puerta"
              required
            />
            <input
              type="text"
              id="codigo-postal"
              name="codigo_postal"
              placeholder="Código postal"
              autocomplete="postal-code"
              required
            />
            <select id="localidad" name="localidad" required>
              <option value="">Localidad</option>
              <option value="Alicante">Alicante</option>
              <option value="Elche">Elche</option>
              <option value="Benidorm">Benidorm</option>
              <option value="San Vicente del Raspeig">San Vicente del Raspeig</option>
            </select>
            <select id="provincia" name="provincia" required>
              <option value="">Provincia</option>
              <option value="Alicante">Alicante</option>
              <option value="Valencia">Valencia</option>
              <option value="Castellón">Castellón</option>
            </select>
            <select id="pais" name="pais" required>
              <option value="">País</option>
              <option value="espana">España</option>
              <option value="francia">Francia</option>
              <option value="italia">Italia</option>
              <option value="portugal">Portugal</option>
            </select>
          </p>

          <p>
            <label for="telefono">Teléfono (opcional):</label>
            <input
              type="tel"
              id="telefono"
              name="telefono"
              autocomplete="tel"
            />
          </p>

          <p>
            <label for="fecha-recepcion">
              Fecha aproximada de recepción (opcional):
            </label>
            <input type="date" id="fecha-recepcion" name="fecha_recepcion" />
          </p>
        </fieldset>

        <p>
          <button type="submit">Solicitar folleto</button>
          <button type="reset">Limpiar formulario</button>
        </p>
      </form>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
