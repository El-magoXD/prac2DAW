<?php
/**
 * Práctica 8 - Panel "Últimos anuncios visitados" (includes/panel_visitados.php).
 * Muestra hasta los cuatro últimos anuncios visitados por el usuario en el orden en que se han visitado.
 * En cada anuncio muestra foto principal, título (con enlace a anuncio.php), ciudad, país y precio.
 */
$listaVisitados = obtenerUltimosAnunciosVisitados();
?>
      <section class="conBorde" aria-label="Últimos anuncios visitados">
        <h2>Últimos anuncios visitados</h2>
<?php if (empty($listaVisitados)): ?>
        <p>Todavía no has visitado ningún anuncio recientemente.</p>
<?php else: ?>
<?php foreach ($listaVisitados as $visitado): ?>
        <article>
          <a href="anuncio.php?id=<?= (int) $visitado['id'] ?>">
            <img
              src="<?= e($visitado['foto_principal']) ?>"
              width="300"
              alt="<?= e($visitado['alt_foto_principal']) ?>"
            />
            <h3><?= e($visitado['titulo']) ?></h3>
          </a>

          <p>Ciudad: <?= e($visitado['ciudad']) ?></p>
          <p>País: <?= e($visitado['pais']) ?></p>
          <p class="anuncio">Precio: <?= e($visitado['precio']) ?></p>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>
