<?php
/**
 * Práctica 9 - Fragmento común para mostrar el detalle completo de un anuncio (Nota 44).
 * Compartido entre anuncio.php (pública restringida) y ver-anuncio.php (privada).
 */
$esPrivada = !empty($esVistaPrivadaFicha);
$idAnuncioFicha = (int) $anuncio['id'];
?>
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
<?php if (empty($anuncio['miniaturas'])): ?>
          <p>Este anuncio no tiene fotos adicionales registradas.</p>
<?php else: ?>
<?php foreach ($anuncio['miniaturas'] as $foto): ?>
          <img
            src="<?= e($foto['src']) ?>"
            width="150"
            alt="<?= e($foto['alt']) ?>"
            title="<?= e($foto['titulo']) ?>"
          />
<?php endforeach; ?>
<?php endif; ?>
          <p>
<?php if ($esPrivada): ?>
            <a class="btn-primary" href="ver-fotos-privada.php?id=<?= $idAnuncioFicha ?>">
              <i class="fa-solid fa-images" aria-hidden="true"></i> Ver todas las fotos
            </a>
<?php else: ?>
            <a class="btn-primary" href="ver-fotos.php?id=<?= $idAnuncioFicha ?>">
              <i class="fa-solid fa-images" aria-hidden="true"></i> Ver todas las fotos
            </a>
<?php endif; ?>
          </p>
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
          <p class="anuncio">
            Usuario anunciante:
            <a href="perfil-usuario.php?id=<?= (int) $anuncio['id_usuario'] ?>">
              <strong><?= e($anuncio['usuario']) ?></strong>
            </a>
          </p>
          <p>
<?php if ($esPrivada): ?>
            <a class="btn-accent" href="anadir-foto.php?anuncio=<?= $idAnuncioFicha ?>">
              <i class="fa-solid fa-camera" aria-hidden="true"></i> Añadir foto a anuncio
            </a>
            <a class="btn-primary" href="mensajes-anuncio.php?id=<?= $idAnuncioFicha ?>">
              <i class="fa-solid fa-envelope" aria-hidden="true"></i> Ver mensajes del anuncio
            </a>
            <a class="btn-primary" href="mis-anuncios.php">Volver a Mis anuncios</a>
<?php else: ?>
            <a class="btn-accent" href="message.php?id=<?= $idAnuncioFicha ?>">Contactar al anunciante (Enviar mensaje)</a>
<?php endif; ?>
          </p>
        </footer>
      </article>
