<?php
/**
 * Práctica 7, 8 y 9 - Página detalle anuncio (anuncio.php - parte pública restringida).
 * - Solo visible para usuarios autenticados o recordados (exigirAutenticacion()).
 * - Consulta todos los datos del anuncio y sus fotos en la BD mediante sentencia preparada
 *   y los visualiza usando el fragmento común includes/ficha_anuncio.php (Nota 44).
 * - Registra la visita al anuncio en la cookie 'ultimos_anuncios'.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$idRaw = $_GET['id'] ?? '';
$idRecibido = (is_string($idRaw) && ctype_digit($idRaw) && (int) $idRaw > 0) ? (int) $idRaw : 0;

$anuncio = ($idRecibido > 0) ? obtenerDatosAnuncioPorId($idRecibido) : null;
if ($anuncio !== null) {
    registrarVisitaAnuncio($idRecibido);
    $tituloPagina = $anuncio['titulo'] . ' | PI - Pisos e Inmuebles';
} else {
    $tituloPagina = 'Anuncio no encontrado | PI - Pisos e Inmuebles';
}

$esPaginaPrivada = false;
require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
<?php if (!$anuncio): ?>
      <section class="conBorde">
        <h2>Anuncio no encontrado</h2>
        <p>El identificador de anuncio solicitado no es válido o el anuncio no existe en la base de datos.</p>
        <p><a class="btn-primary" href="index.php">Volver a la página principal</a></p>
      </section>
<?php else: ?>
<?php
$esVistaPrivadaFicha = false;
require __DIR__ . '/includes/ficha_anuncio.php';
?>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
