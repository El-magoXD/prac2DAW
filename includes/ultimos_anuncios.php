<?php
/**
 * Práctica 7 - Fragmento reutilizable con el listado de los 5 últimos anuncios publicados y la búsqueda rápida.
 */
$ultimosAnuncios = [
    [
        'id'     => 1,
        'imagen' => 'img/piso.jpg',
        'alt'    => 'Piso en venta en Alicante',
        'titulo' => 'Piso luminoso en Alicante',
        'fecha'  => '25/09/2026',
        'ciudad' => 'Alicante',
        'pais'   => 'España',
        'precio' => '185.000 €',
    ],
    [
        'id'     => 2,
        'imagen' => 'img/piso2.jpg',
        'alt'    => 'Apartamento en alquiler en Valencia',
        'titulo' => 'Apartamento moderno en Valencia',
        'fecha'  => '24/09/2026',
        'ciudad' => 'Valencia',
        'pais'   => 'España',
        'precio' => '950 € / mes',
    ],
    [
        'id'     => 3,
        'imagen' => 'img/piso3.jpg',
        'alt'    => 'Piso en venta en Madrid',
        'titulo' => 'Piso reformado en Madrid',
        'fecha'  => '23/09/2026',
        'ciudad' => 'Madrid',
        'pais'   => 'España',
        'precio' => '275.000 €',
    ],
    [
        'id'     => 4,
        'imagen' => 'img/piso4.jpg',
        'alt'    => 'Casa en alquiler en Sevilla',
        'titulo' => 'Casa con terraza en Sevilla',
        'fecha'  => '22/09/2026',
        'ciudad' => 'Sevilla',
        'pais'   => 'España',
        'precio' => '1.150 € / mes',
    ],
    [
        'id'     => 5,
        'imagen' => 'img/piso5.jpg',
        'alt'    => 'Piso en venta en Barcelona',
        'titulo' => 'Piso céntrico en Barcelona',
        'fecha'  => '21/09/2026',
        'ciudad' => 'Barcelona',
        'pais'   => 'España',
        'precio' => '320.000 €',
    ],
];
?>
      <section>
        <h2>Últimos anuncios</h2>
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
      </section>

      <section class="conBorde">
        <h2>Búsqueda rápida</h2>

        <form action="result.php" method="get">
          <p>
            <label for="busqueda"><i class="fa-solid fa-magnifying-glass" aria-label="Buscar" role="img"></i> Buscar:</label>
            <input type="search" id="busqueda" name="busqueda" placeholder="Ej. Piso en Alicante" />
          </p>

          <p>
            <button type="submit">Buscar</button>
          </p>
        </form>
      </section>
