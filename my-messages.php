<?php
/**
 * Práctica 7, 8 y 9 - Página "Mis mensajes" (my-messages.php - parte privada).
 * Consulta los mensajes enviados (UsuOrigen) y recibidos (UsuDestino) del usuario en la BD
 * y muestra el número total de mensajes enviados y recibidos.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$tituloPagina = 'Mis mensajes | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$idUsuario = obtenerIdUsuarioActual();
$mensajesEnviados = obtenerMensajesEnviadosUsuario($idUsuario);
$mensajesRecibidos = obtenerMensajesRecibidosUsuario($idUsuario);
?>
      <p><strong><?= e(obtenerSaludoHorario(obtenerUsuarioActual())) ?></strong></p>

      <section class="conBorde">
        <h2>Resumen de mensajería</h2>
        <ul>
          <li><strong>Total de mensajes enviados:</strong> <?= count($mensajesEnviados) ?></li>
          <li><strong>Total de mensajes recibidos:</strong> <?= count($mensajesRecibidos) ?></li>
        </ul>
      </section>

      <section>
        <h2><u>Mensajes enviados (<?= count($mensajesEnviados) ?>)</u></h2>
<?php if (empty($mensajesEnviados)): ?>
        <p>Todavía no has enviado ningún mensaje.</p>
<?php else: ?>
<?php foreach ($mensajesEnviados as $msg): ?>
        <article>
          <fieldset>
            <p>
              <strong>Para:</strong>
              <a href="perfil-usuario.php?id=<?= (int) $msg['IdDestinatario'] ?>">
                <?= e($msg['NomDestinatario']) ?>
              </a>
            </p>
            <p>
              <strong>Anuncio:</strong>
              <a href="anuncio.php?id=<?= (int) $msg['Anuncio'] ?>">
                <?= e($msg['TituloAnuncio']) ?>
              </a>
            </p>
            <p><strong>Tipo de mensaje:</strong> <?= e($msg['NomTMensaje']) ?></p>
            <p><strong>Texto:</strong> <?= e($msg['Texto']) ?></p>
            <p><strong>Fecha:</strong> <?= e(formatearFechaBD($msg['FRegistro'], true)) ?></p>
          </fieldset>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>

      <section>
        <h2><u>Mensajes recibidos (<?= count($mensajesRecibidos) ?>)</u></h2>
<?php if (empty($mensajesRecibidos)): ?>
        <p>Todavía no has recibido ningún mensaje.</p>
<?php else: ?>
<?php foreach ($mensajesRecibidos as $msg): ?>
        <article>
          <fieldset>
            <p>
              <strong>De:</strong>
              <a href="perfil-usuario.php?id=<?= (int) $msg['IdRemitente'] ?>">
                <?= e($msg['NomRemitente']) ?>
              </a>
            </p>
            <p>
              <strong>Anuncio:</strong>
              <a href="ver-anuncio.php?id=<?= (int) $msg['Anuncio'] ?>">
                <?= e($msg['TituloAnuncio']) ?>
              </a>
            </p>
            <p><strong>Tipo de mensaje:</strong> <?= e($msg['NomTMensaje']) ?></p>
            <p><strong>Texto:</strong> <?= e($msg['Texto']) ?></p>
            <p><strong>Fecha:</strong> <?= e(formatearFechaBD($msg['FRegistro'], true)) ?></p>
          </fieldset>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
