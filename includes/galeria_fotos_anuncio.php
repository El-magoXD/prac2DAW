<?php
/**
 * Práctica 9 - Fragmento común para mostrar todas las fotos de un anuncio (Notas 46 y 47).
 * Compartido entre ver-fotos.php (pública restringida) y ver-fotos-privada.php (privada).
 */
$esPrivada = !empty($esGaleriaPrivada);
$anuncioGaleria = $galeria['anuncio'];
$fotosGaleria = $galeria['fotos'];
$totalFotos = (int) $galeria['total'];
$idAnuncioGaleria = (int) $anuncioGaleria['id'];
?>
      <section class="conBorde">
        <h2>Galería de fotos del anuncio</h2>
        <h3><?= e($anuncioGaleria['titulo']) ?></h3>
        <p>
          <span class="badge"><?= e($anuncioGaleria['tipo_anuncio']) ?></span>
          <span class="badge"><?= e($anuncioGaleria['tipo_vivienda']) ?></span>
        </p>
        <ul>
          <li><strong>Ciudad:</strong> <?= e($anuncioGaleria['ciudad']) ?></li>
          <li><strong>País:</strong> <?= e($anuncioGaleria['pais']) ?></li>
          <li><strong>Precio:</strong> <?= e($anuncioGaleria['precio']) ?></li>
          <li><strong>Número total de fotos:</strong> <?= $totalFotos ?></li>
        </ul>
      </section>

      <section>
        <h2>Todas las fotografías (<?= $totalFotos ?>)</h2>
<?php foreach ($fotosGaleria as $fotoItem): ?>
        <article>
          <h3>
            <?= e($fotoItem['titulo']) ?>
<?php if (!empty($fotoItem['principal'])): ?>
            <span class="badge">Principal</span>
<?php endif; ?>
          </h3>
          <figure>
            <img
              src="<?= e($fotoItem['src']) ?>"
              width="600"
              alt="<?= e($fotoItem['alt']) ?>"
            />
            <figcaption><?= e($fotoItem['alt']) ?></figcaption>
          </figure>
        </article>
<?php endforeach; ?>
      </section>

      <section>
        <p>
<?php if ($esPrivada): ?>
          <a class="btn-accent" href="anadir-foto.php?anuncio=<?= $idAnuncioGaleria ?>">
            <i class="fa-solid fa-camera" aria-hidden="true"></i> Añadir foto a este anuncio
          </a>
          <a class="btn-primary" href="ver-anuncio.php?id=<?= $idAnuncioGaleria ?>">Volver al anuncio (privado)</a>
          <a class="btn-primary" href="mis-anuncios.php">Volver a Mis anuncios</a>
<?php else: ?>
          <a class="btn-primary" href="anuncio.php?id=<?= $idAnuncioGaleria ?>">Volver al detalle del anuncio</a>
          <a class="btn-primary" href="index.php">Volver a la página principal</a>
<?php endif; ?>
        </p>
      </section>
