<?php
/**
 * Práctica 9 - Página "Perfil usuario" (perfil-usuario.php).
 * Muestra el perfil público (nombre de usuario, foto, fecha de incorporación) del usuario seleccionado
 * y un listado simplificado con todos los anuncios publicados por dicho usuario.
 */
require_once __DIR__ . '/includes/funciones.php';

$idRaw = $_GET['id'] ?? '';
$idUsuario = (is_string($idRaw) && ctype_digit($idRaw) && (int) $idRaw > 0) ? (int) $idRaw : 0;

$usuarioPerfil = ($idUsuario > 0) ? obtenerUsuarioPorId($idUsuario) : null;
$anunciosUsuario = ($usuarioPerfil !== null) ? obtenerAnunciosDeUsuario($idUsuario) : [];

$tituloPagina = ($usuarioPerfil !== null)
    ? 'Perfil de ' . $usuarioPerfil['NomUsuario'] . ' | PI - Pisos e Inmuebles'
    : 'Perfil de usuario no encontrado | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';
?>
<?php if ($usuarioPerfil === null): ?>
      <section class="conBorde">
        <h2>Usuario no encontrado</h2>
        <p>El identificador de usuario proporcionado no es válido o no existe en la base de datos.</p>
        <p><a class="btn-primary" href="index.php">Volver a la página principal</a></p>
      </section>
<?php else: ?>
      <section class="conBorde">
        <h2>Perfil público de usuario</h2>
<?php if (!empty($usuarioPerfil['Foto'])): ?>
        <figure>
          <img
            src="<?= e($usuarioPerfil['Foto']) ?>"
            width="200"
            alt="Foto de perfil de <?= e($usuarioPerfil['NomUsuario']) ?>"
          />
          <figcaption><?= e($usuarioPerfil['NomUsuario']) ?></figcaption>
        </figure>
<?php endif; ?>
        <ul>
          <li><strong>Nombre de usuario:</strong> <?= e($usuarioPerfil['NomUsuario']) ?></li>
          <li><strong>Fecha de incorporación:</strong> <?= e(formatearFechaBD($usuarioPerfil['FRegistro'], true)) ?></li>
          <li><strong>Total de anuncios publicados:</strong> <?= count($anunciosUsuario) ?></li>
        </ul>
      </section>

      <section>
        <h2>Anuncios publicados por <?= e($usuarioPerfil['NomUsuario']) ?> (<?= count($anunciosUsuario) ?>)</h2>
<?php if (empty($anunciosUsuario)): ?>
        <p>Este usuario todavía no tiene anuncios publicados.</p>
<?php else: ?>
<?php foreach ($anunciosUsuario as $anuncioItem): ?>
        <article>
          <a href="anuncio.php?id=<?= (int) $anuncioItem['id'] ?>">
            <img
              src="<?= e($anuncioItem['imagen']) ?>"
              width="300"
              alt="<?= e($anuncioItem['alt']) ?>"
            />
            <h3><?= e($anuncioItem['titulo']) ?></h3>
          </a>

          <p><strong>Ciudad:</strong> <?= e($anuncioItem['ciudad']) ?></p>
          <p><strong>País:</strong> <?= e($anuncioItem['pais']) ?></p>
          <p class="anuncio"><strong>Precio:</strong> <?= e($anuncioItem['precio']) ?></p>
          <p>
            <a class="btn-primary" href="anuncio.php?id=<?= (int) $anuncioItem['id'] ?>">Ver detalle</a>
            <a class="btn-accent" href="ver-fotos.php?id=<?= (int) $anuncioItem['id'] ?>">Ver fotos</a>
          </p>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>

      <section>
        <p><a class="btn-primary" href="index.php">Volver a la página principal</a></p>
      </section>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
