<?php
/**
 * Práctica 7 - Página de error 404 (404.php).
 */
$tituloPagina = 'Error 404 | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
      <section class="conBorde">
        <h2>Error 404 - Página no encontrada</h2>
        <p>Lo sentimos, la página que estás buscando no existe, ha sido movida o el enlace no es correcto.</p>
        <p>Puedes volver a la página principal utilizando el siguiente enlace:</p>
        <p>
          <a class="btn-primary" href="index.php">Volver a la Página Principal</a>
        </p>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
