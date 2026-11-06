<?php
/**
 * Práctica 7 - Página de contacto (contact.php).
 * Envía los datos del mensaje a message-sent.php para su validación y visualización con PHP.
 */
$tituloPagina = 'Contacta con nosotros | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
      <section>
        <h2>Contacta con nosotros</h2>

        <form action="message-sent.php" method="post" novalidate>
          <input type="hidden" name="origen" value="contact.php" />

          <fieldset>
            <legend>Detalles de tu mensaje</legend>

            <p>
              <label for="tipo_mensaje">Motivo de consulta (*):</label>
              <select id="tipo_mensaje" name="tipo_mensaje">
                <option value="">Selecciona un motivo</option>
                <option value="ayuda">Ayuda</option>
                <option value="accesibilidad">Accesibilidad</option>
                <option value="otro">Otro</option>
              </select>
            </p>

            <p>
              <label for="texto_mensaje">Texto (*):</label>
              <textarea
                id="texto_mensaje"
                name="texto_mensaje"
                rows="10"
                cols="60"
                placeholder="Escribe aquí tu mensaje..."
              ></textarea>
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
