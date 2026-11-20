<?php
/**
 * Práctica 7, 8 y 9 - Fragmento reutilizable con el listado de los 5 últimos anuncios publicados
 * (consultados en la tabla Anuncios ordenados por FRegistro DESC) y la búsqueda rápida.
 */
$ultimosAnuncios = obtenerUltimosCincoAnuncios();
?>
      <section>
        <h2>Últimos anuncios</h2>
<?php if (empty($ultimosAnuncios)): ?>
        <p>No se han podido recuperar anuncios de la base de datos en este momento.</p>
<?php else: ?>
<?php foreach ($ultimosAnuncios as $item): ?>
        <article>
          <a href="anuncio.php?id=<?= (int) $item['id'] ?>">
            <img
              src="<?= e($item['imagen']) ?>"
              width="300"
              alt="<?= e($item['alt']) ?>"
            />
            <h3><?= e($item['titulo']) ?></h3>
          </a>

          <p>Fecha: <?= e($item['fecha']) ?></p>
          <p>Ciudad: <?= e($item['ciudad']) ?></p>
          <p>País: <?= e($item['pais']) ?></p>
          <p class="anuncio">Precio: <?= e($item['precio']) ?></p>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>

      <section class="conBorde">
        <h2>Búsqueda rápida</h2>

        <form action="result.php" method="get">
          <p>
            <label for="busqueda"><i class="fa-solid fa-magnifying-glass" aria-label="Buscar" role="img"></i> Buscar:</label>
            <input type="search" id="busqueda" name="busqueda" placeholder="Ej. vivienda alquiler alicante" />
          </p>

          <p>
            <button type="submit">Buscar</button>
          </p>
        </form>
      </section>
