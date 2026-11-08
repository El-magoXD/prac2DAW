<?php
/**
 * Práctica 7 - Página de búsqueda sin resultados (search-noresult.php).
 */
$tituloPagina = 'Resultados de Búsqueda | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
      <section class="conBorde">
        <h2>Resultados de la búsqueda</h2>
        <p>Sin resultados que coincidan con los criterios de búsqueda.</p>
        <p>
          <a class="btn-primary" href="search.php">Volver al formulario de búsqueda</a>
        </p>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
