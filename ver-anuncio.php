<?php
/**
 * Práctica 7, 8 y 9 - Página "Ver anuncio" de la parte privada (ver-anuncio.php).
 * Protegida mediante sesión PHP (exigirAutenticacion()).
 * Comparte el fragmento común includes/ficha_anuncio.php con anuncio.php (Nota 44).
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$idRaw = $_GET['id'] ?? '';
$idAnuncio = (is_string($idRaw) && ctype_digit($idRaw) && (int) $idRaw > 0) ? (int) $idRaw : 0;
$anuncio = ($idAnuncio > 0) ? obtenerDatosAnuncioPorId($idAnuncio) : null;

if ($anuncio !== null) {
    registrarVisitaAnuncio($idAnuncio);
    $tituloPagina = 'Ver anuncio: ' . $anuncio['titulo'] . ' | PI - Pisos e Inmuebles';
} else {
    $tituloPagina = 'Anuncio no encontrado | PI - Pisos e Inmuebles';
}

$esPaginaPrivada = true;
require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
<?php if (!$anuncio): ?>
      <section class="conBorde">
        <h2>Anuncio no encontrado</h2>
        <p>El identificador de anuncio solicitado no es válido o no existe en la base de datos.</p>
        <p><a class="btn-primary" href="mis-anuncios.php">Volver a Mis anuncios</a></p>
      </section>
<?php else: ?>
<?php
$esVistaPrivadaFicha = true;
require __DIR__ . '/includes/ficha_anuncio.php';
?>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
