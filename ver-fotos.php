<?php
/**
 * Práctica 9 - Página "Ver fotos" (pública restringida: ver-fotos.php).
 * Muestra todas las fotos de un anuncio, el número total de fotos y su información básica.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$idRaw = $_GET['id'] ?? '';
$idAnuncio = (is_string($idRaw) && ctype_digit($idRaw) && (int) $idRaw > 0) ? (int) $idRaw : 0;
$galeria = ($idAnuncio > 0) ? obtenerGaleriaAnuncio($idAnuncio) : null;

$tituloPagina = ($galeria !== null)
    ? 'Fotos de ' . $galeria['anuncio']['titulo'] . ' | PI - Pisos e Inmuebles'
    : 'Anuncio no encontrado | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
<?php if ($galeria === null): ?>
      <section class="conBorde">
        <h2>Anuncio no encontrado</h2>
        <p>El identificador de anuncio solicitado no es válido o no existe en la base de datos.</p>
        <p><a class="btn-primary" href="index.php">Volver a la página principal</a></p>
      </section>
<?php else: ?>
<?php
$esGaleriaPrivada = false;
require __DIR__ . '/includes/galeria_fotos_anuncio.php';
?>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
