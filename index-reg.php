<?php
/**
 * Práctica 7 - Página menú usuario registrado (parte privada).
 */
$tituloPagina = 'Menú de usuario registrado | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$nombreUsuario = $_GET['usuario'] ?? 'usuario registrado';
?>
      <section class="conBorde">
        <h2>Menú de usuario registrado</h2>
        <p>Bienvenido/a, <strong><?= e($nombreUsuario) ?></strong>.</p>

        <ul>
          <li><a href="register.php">Mis datos</a></li>
          <li><a href="index.php">Darme de baja</a></li>
          <li><a href="mis-anuncios.php">Mis anuncios</a></li>
          <li><a href="crear-anuncio.php">Crear anuncio</a></li>
          <li><a href="anadir-foto.php">Añadir foto a anuncio</a></li>
          <li><a href="create-ad.php">Solicitar folleto publicitario</a></li>
          <li><a href="my-messages.php">Mis mensajes</a></li>
          <li><a href="index.php">Salir (Cerrar sesión)</a></li>
        </ul>
      </section>

<?php
require_once __DIR__ . '/includes/ultimos_anuncios.php';
require_once __DIR__ . '/includes/pie.php';
?>
