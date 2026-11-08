<?php
/**
 * Práctica 7 - Página con el listado resultado de una búsqueda (result.php).
 * Muestra los datos reales introducidos por el usuario (desde la búsqueda rápida o desde el formulario
 * de búsqueda avanzada), además del listado de resultados de ejemplo con enlace al detalle del anuncio.
 */
$tituloPagina = 'Resultados de Búsqueda | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$datosBusqueda = !empty($_GET) ? $_GET : $_POST;
$esBusquedaRapida = array_key_exists('busqueda', $datosBusqueda);

$nombresTipoAnuncio = [
    'venta'    => 'Venta',
    'alquiler' => 'Alquiler',
];

$nombresTipoVivienda = [
    'obra_nueva' => 'Obra nueva',
    'vivienda'   => 'Vivienda',
    'oficina'    => 'Oficina',
    'local'      => 'Local',
    'garaje'     => 'Garaje',
];
?>
      <section class="conBorde">
        <h2>Criterios de búsqueda recibidos</h2>
<?php if ($esBusquedaRapida): ?>
<?php $terminoRapido = $datosBusqueda['busqueda'] ?? ''; ?>
        <p><strong>Origen:</strong> Búsqueda rápida (Página principal)</p>
        <p>
          <strong>Término buscado:</strong>
          <?= tieneContenidoSinEspacios($terminoRapido) ? e($terminoRapido) : '<em>(Cualquier anuncio - sin texto especificado)</em>' ?>
        </p>
<?php else: ?>
<?php
    $tipoAnuncio = $datosBusqueda['tipo_anuncio'] ?? '';
    $tipoVivienda = $datosBusqueda['tipo_vivienda'] ?? '';
    $ciudad = $datosBusqueda['ciudad'] ?? '';
    $pais = $datosBusqueda['pais'] ?? '';
    $precioMin = $datosBusqueda['precio_min'] ?? '';
    $precioMax = $datosBusqueda['precio_max'] ?? '';
    $fechaMin = $datosBusqueda['fecha_min'] ?? '';
    $fechaMax = $datosBusqueda['fecha_max'] ?? '';
?>
        <p><strong>Origen:</strong> Formulario de búsqueda avanzada</p>
        <ul>
          <li>
            <strong>Tipo de anuncio:</strong>
            <?= ($tipoAnuncio !== '' && isset($nombresTipoAnuncio[$tipoAnuncio])) ? e($nombresTipoAnuncio[$tipoAnuncio]) : 'Cualquiera' ?>
          </li>
          <li>
            <strong>Tipo de vivienda:</strong>
            <?= ($tipoVivienda !== '' && isset($nombresTipoVivienda[$tipoVivienda])) ? e($nombresTipoVivienda[$tipoVivienda]) : 'Cualquiera' ?>
          </li>
          <li>
            <strong>Ciudad:</strong>
            <?= tieneContenidoSinEspacios($ciudad) ? e($ciudad) : '<em>(No especificada)</em>' ?>
          </li>
          <li>
            <strong>País:</strong>
            <?= tieneContenidoSinEspacios($pais) ? e(obtenerNombrePais($pais)) : '<em>(No especificado)</em>' ?>
          </li>
          <li>
            <strong>Precio mínimo:</strong>
            <?= tieneContenidoSinEspacios($precioMin) ? e($precioMin . ' €') : '<em>(Sin límite mínimo)</em>' ?>
          </li>
          <li>
            <strong>Precio máximo:</strong>
            <?= tieneContenidoSinEspacios($precioMax) ? e($precioMax . ' €') : '<em>(Sin límite máximo)</em>' ?>
          </li>
          <li>
            <strong>Fecha desde:</strong>
            <?= tieneContenidoSinEspacios($fechaMin) ? e($fechaMin) : '<em>(No especificada)</em>' ?>
          </li>
          <li>
            <strong>Fecha hasta:</strong>
            <?= tieneContenidoSinEspacios($fechaMax) ? e($fechaMax) : '<em>(No especificada)</em>' ?>
          </li>
        </ul>
<?php endif; ?>
        <p>
          <a class="btn-primary" href="search.php">Nueva búsqueda avanzada</a>
        </p>
      </section>

      <section>
        <h2>Resultados de la búsqueda</h2>
        <p>Mostrando anuncios que cumplen con los criterios seleccionados:</p>

        <!-- Resultado 1 (ID impar = 1): Muestra el primer anuncio en anuncio.php -->
        <article>
          <a href="anuncio.php?id=1">
            <img
              src="img/piso.jpg"
              width="300"
              alt="Vista miniatura del piso luminoso en Alicante"
            />
            <h3>Piso luminoso en Alicante (ID 1 - Impar)</h3>
          </a>

          <p><strong>Fecha:</strong> 25/09/2026</p>
          <p><strong>Ciudad:</strong> Alicante</p>
          <p><strong>País:</strong> España</p>
          <p class="anuncio"><strong>Precio:</strong> 185.000 €</p>
        </article>

        <!-- Resultado 2 (ID par = 2): Muestra el segundo anuncio en anuncio.php -->
        <article>
          <a href="anuncio.php?id=2">
            <img
              src="img/piso2.jpg"
              width="300"
              alt="Vista miniatura del apartamento moderno en Valencia"
            />
            <h3>Apartamento moderno en Valencia (ID 2 - Par)</h3>
          </a>

          <p><strong>Fecha:</strong> 24/09/2026</p>
          <p><strong>Ciudad:</strong> Valencia</p>
          <p><strong>País:</strong> España</p>
          <p class="anuncio"><strong>Precio:</strong> 950 € / mes</p>
        </article>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
