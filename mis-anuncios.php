<?php
/**
 * Práctica 7, 8 y 9 - Página "Mis anuncios" (mis-anuncios.php - parte privada).
 * Protegida mediante sesión PHP (exigirAutenticacion()).
 * Muestra el número total de anuncios y el listado de anuncios del usuario desde la tabla Anuncios.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$tituloPagina = 'Mis anuncios | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$idUsuario = obtenerIdUsuarioActual();
$misAnuncios = obtenerAnunciosDeUsuario($idUsuario);
?>
      <section class="conBorde">
        <h2>Gestión de mis anuncios</h2>
        <p>Desde aquí puedes consultar tus inmuebles publicados, añadir nuevas fotografías a un anuncio concreto o publicar un anuncio nuevo.</p>
        <p><strong>Número total de anuncios publicados:</strong> <?= count($misAnuncios) ?></p>
        <p>
          <a class="btn-primary" href="crear-anuncio.php"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Crear nuevo anuncio</a>
          <a class="btn-accent" href="anadir-foto.php"><i class="fa-solid fa-camera" aria-hidden="true"></i> Añadir foto (elegir anuncio)</a>
        </p>
      </section>

      <section>
        <h2>Mis anuncios (<?= count($misAnuncios) ?>)</h2>
<?php if (empty($misAnuncios)): ?>
        <p>Todavía no tienes ningún anuncio publicado en la base de datos.</p>
<?php else: ?>
<?php foreach ($misAnuncios as $anuncio): ?>
        <article>
          <a href="ver-anuncio.php?id=<?= (int) $anuncio['id'] ?>">
            <img
              src="<?= e($anuncio['imagen']) ?>"
              width="300"
              alt="<?= e($anuncio['alt']) ?>"
            />
            <h3><?= e($anuncio['titulo']) ?></h3>
          </a>

          <p><strong>Ciudad:</strong> <?= e($anuncio['ciudad']) ?></p>
          <p><strong>País:</strong> <?= e($anuncio['pais']) ?></p>
          <p class="anuncio"><strong>Precio:</strong> <?= e($anuncio['precio']) ?></p>

          <p>
            <a class="btn-primary" href="ver-anuncio.php?id=<?= (int) $anuncio['id'] ?>">Ver anuncio</a>
            <a class="btn-primary" href="ver-fotos-privada.php?id=<?= (int) $anuncio['id'] ?>">Ver fotos</a>
            <a class="btn-primary" href="mensajes-anuncio.php?id=<?= (int) $anuncio['id'] ?>">Ver mensajes</a>
            <a class="btn-accent" href="anadir-foto.php?anuncio=<?= (int) $anuncio['id'] ?>">Añadir foto a este anuncio</a>
          </p>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
