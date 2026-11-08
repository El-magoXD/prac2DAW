<?php
/**
 * Práctica 7 - Página "Mis mensajes" (my-messages.php).
 */
$tituloPagina = 'Mis mensajes | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
      <p>Bienvenido/a, usuario registrado.</p>

      <section>
        <h2><u>Mensajes enviados</u></h2>

        <article>
          <fieldset>
            <p><strong>Para:</strong> Juan Pérez</p>
            <p><strong>Tipo de mensaje:</strong> Más información</p>
            <p>
              <strong>Texto:</strong> Hola, estoy interesado en el piso que
              tienes en Alicante. ¿Podrías darme más detalles?
            </p>
            <p><strong>Fecha:</strong> 25/09/2026</p>
          </fieldset>
        </article>

        <article>
          <fieldset>
            <p><strong>Para:</strong> María López</p>
            <p><strong>Tipo de mensaje:</strong> Solicitar una cita</p>
            <p>
              <strong>Texto:</strong> Buenas tardes, me gustaría saber si el
              apartamento sigue disponible y si se puede visitar.
            </p>
            <p><strong>Fecha:</strong> 24/09/2026</p>
          </fieldset>
        </article>
      </section>

      <section>
        <h2><u>Mensajes recibidos</u></h2>

        <article>
          <fieldset>
            <p><strong>De:</strong> Juan Pérez</p>
            <p><strong>Tipo de mensaje:</strong> Solicitar una cita</p>
            <p>
              <strong>Texto:</strong> Me parece perfecto, ¿podríamos concertar
              una visita para el próximo lunes?
            </p>
            <p><strong>Fecha:</strong> 28/09/2026</p>
          </fieldset>
        </article>

        <article>
          <fieldset>
            <p><strong>De:</strong> María López</p>
            <p><strong>Tipo de mensaje:</strong> Más información</p>
            <p><strong>Texto:</strong> Claro que sí, sigue disponible para visitas.</p>
            <p><strong>Fecha:</strong> 26/09/2026</p>
          </fieldset>
        </article>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
