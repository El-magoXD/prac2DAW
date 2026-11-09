<?php
/**
 * Práctica 7 - Página detalle anuncio (anuncio.php).
 * Muestra el detalle de uno de los dos anuncios almacenados en includes/datos_anuncios.php
 * dependiendo de si el identificador recibido por la URL ($_GET['id']) es un número par o impar.
 */
require_once __DIR__ . '/includes/funciones.php';

$idRecibido = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : 1;
$esPar = ($idRecibido % 2 === 0);
$claveAnuncio = $esPar ? 'par' : 'impar';

$rutaDatosAnuncios = __DIR__ . '/includes/datos_anuncios.php';
$errorFichero = false;
$anuncio = null;

if (!file_exists($rutaDatosAnuncios) || !is_readable($rutaDatosAnuncios)) {
    $errorFichero = true;
    $tituloPagina = 'Error al cargar anuncio | PI - Pisos e Inmuebles';
} else {
    require_once $rutaDatosAnuncios;
    $anuncio = $anunciosDetalle[$claveAnuncio] ?? null;
    $tituloPagina = ($anuncio ? $anuncio['titulo'] : 'Detalle de anuncio') . ' | PI - Pisos e Inmuebles';
}

$esPaginaPrivada = false;
require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
<?php if ($errorFichero || !$anuncio): ?>
      <section class="conBorde">
        <h2>Error al cargar el anuncio</h2>
        <p>No se ha podido acceder al fichero de datos de anuncios o el anuncio solicitado no está disponible.</p>
        <p><a class="btn-primary" href="index.php">Volver a la página principal</a></p>
      </section>
<?php else: ?>
      <article>
        <header>
          <h2><?= e($anuncio['titulo']) ?></h2>
          <p>
            <span class="badge"><?= e($anuncio['tipo_anuncio']) ?></span>
            <span class="badge"><?= e($anuncio['tipo_vivienda']) ?></span>
          </p>
        </header>

        <section>
          <figure>
            <img
              src="<?= e($anuncio['foto_principal']) ?>"
              width="800"
              alt="<?= e($anuncio['alt_foto_principal']) ?>"
            />
            <figcaption>Foto principal: <?= e($anuncio['titulo']) ?></figcaption>
          </figure>

          <h3>Miniaturas de fotos</h3>
<?php foreach ($anuncio['miniaturas'] as $foto): ?>
          <img
            src="<?= e($foto['src']) ?>"
            width="150"
            alt="<?= e($foto['alt']) ?>"
            title="<?= e($foto['titulo']) ?>"
          />
<?php endforeach; ?>
        </section>

        <section>
          <h3>Descripción del inmueble</h3>
          <p><?= e($anuncio['texto']) ?></p>
        </section>

        <section>
          <h3>Datos generales</h3>
          <ul>
            <li><strong>Tipo de anuncio:</strong> <?= e($anuncio['tipo_anuncio']) ?></li>
            <li><strong>Tipo de vivienda:</strong> <?= e($anuncio['tipo_vivienda']) ?></li>
            <li><strong>Fecha de publicación:</strong> <?= e($anuncio['fecha']) ?></li>
            <li><strong>Ciudad:</strong> <?= e($anuncio['ciudad']) ?></li>
            <li><strong>País:</strong> <?= e($anuncio['pais']) ?></li>
            <li><strong>Precio:</strong> <?= e($anuncio['precio']) ?></li>
          </ul>
        </section>

        <section>
          <h3>Características</h3>
          <ul>
            <li><strong>Superficie:</strong> <?= e($anuncio['caracteristicas']['superficie']) ?></li>
            <li><strong>Número de habitaciones:</strong> <?= (int) $anuncio['caracteristicas']['habitaciones'] ?></li>
            <li><strong>Número de baños:</strong> <?= (int) $anuncio['caracteristicas']['banos'] ?></li>
            <li><strong>Planta:</strong> <?= e($anuncio['caracteristicas']['planta']) ?></li>
            <li><strong>Año de construcción:</strong> <?= (int) $anuncio['caracteristicas']['anio_construccion'] ?></li>
          </ul>
        </section>

        <footer>
          <p class="anuncio">Usuario anunciante: <strong><?= e($anuncio['usuario']) ?></strong></p>
          <p>
            <a class="btn-accent" href="message.php?id=<?= $idRecibido ?>">Contactar al anunciante (Enviar mensaje)</a>
          </p>
        </footer>
      </article>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
