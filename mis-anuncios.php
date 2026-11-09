<?php
/**
 * Práctica 7 - Página "Mis anuncios" (mis-anuncios.php).
 * Muestra un listado con todos los anuncios del usuario (foto principal en miniatura, título, ciudad, país y precio),
 * con enlaces para ver el detalle privado del anuncio, añadirle una foto o crear un anuncio nuevo.
 */
$tituloPagina = 'Mis anuncios | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$misAnuncios = [
    [
        'id'     => 1,
        'imagen' => 'img/piso.jpg',
        'alt'    => 'Miniatura del piso luminoso en Alicante',
        'titulo' => 'Piso luminoso en Alicante',
        'ciudad' => 'Alicante',
        'pais'   => 'España',
        'precio' => '185.000 €',
    ],
    [
        'id'     => 2,
        'imagen' => 'img/piso2.jpg',
        'alt'    => 'Miniatura del apartamento moderno en Valencia',
        'titulo' => 'Apartamento moderno en Valencia',
        'ciudad' => 'Valencia',
        'pais'   => 'España',
        'precio' => '950 € / mes',
    ],
    [
        'id'     => 3,
        'imagen' => 'img/piso3.jpg',
        'alt'    => 'Miniatura del piso reformado en Madrid',
        'titulo' => 'Piso reformado en Madrid',
        'ciudad' => 'Madrid',
        'pais'   => 'España',
        'precio' => '275.000 €',
    ],
];
?>
      <section class="conBorde">
        <h2>Gestión de mis anuncios</h2>
        <p>Desde aquí puedes consultar tus inmuebles publicados, añadir nuevas fotografías a un anuncio concreto o publicar un anuncio nuevo.</p>
        <p>
          <a class="btn-primary" href="crear-anuncio.php"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Crear nuevo anuncio</a>
          <a class="btn-accent" href="anadir-foto.php"><i class="fa-solid fa-camera" aria-hidden="true"></i> Añadir foto (elegir anuncio)</a>
        </p>
      </section>

      <section>
        <h2>Mis anuncios</h2>
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
            <a class="btn-accent" href="anadir-foto.php?anuncio=<?= (int) $anuncio['id'] ?>">Añadir foto a este anuncio</a>
          </p>
        </article>
<?php endforeach; ?>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
