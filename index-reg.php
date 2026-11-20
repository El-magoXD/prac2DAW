<?php
/**
 * Práctica 7 y 8 - Página menú usuario registrado (parte privada: index-reg.php).
 * Protegida mediante sesión PHP: si no hay usuario autenticado, redirige al formulario de acceso.
 * Muestra el saludo según la franja horaria, el estilo CSS aplicado y, cuando se está recordando
 * al usuario mediante cookie, la fecha y hora de su última visita.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$tituloPagina = 'Menú de usuario registrado | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$nombreUsuario = obtenerUsuarioActual();
$estiloActivoTitulo = $_SESSION['titulo_estilo'] ?? 'Estilo principal';
$estaSiendoRecordado = !empty($_SESSION['recordado']) && !empty($_SESSION['ultima_visita']);
?>
      <section class="conBorde">
        <h2>Menú de usuario registrado</h2>
        <p><strong><?= e(obtenerSaludoHorario($nombreUsuario)) ?></strong></p>
<?php if ($estaSiendoRecordado): ?>
        <p>
          Hola <strong><?= e($nombreUsuario) ?></strong>, su última visita fue el
          <strong><?= e($_SESSION['ultima_visita']) ?></strong>.
        </p>
<?php endif; ?>
        <p>Estilo visual asignado en tu sesión: <span class="badge"><?= e($estiloActivoTitulo) ?></span></p>

        <ul>
          <li><a href="mis-datos.php">Mis datos</a></li>
          <li><a href="configurar.php">Configurar</a></li>
          <li><a href="salir.php">Darme de baja</a></li>
          <li><a href="mis-anuncios.php">Mis anuncios</a></li>
          <li><a href="crear-anuncio.php">Crear anuncio</a></li>
          <li><a href="anadir-foto.php">Añadir foto a anuncio</a></li>
          <li><a href="create-ad.php">Solicitar folleto publicitario</a></li>
          <li><a href="my-messages.php">Mis mensajes</a></li>
          <li><a href="salir.php">Salir (Cerrar sesión)</a></li>
        </ul>
      </section>

<?php
require_once __DIR__ . '/includes/ultimos_anuncios.php';
require_once __DIR__ . '/includes/pie.php';
?>
