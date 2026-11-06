<?php
/**
 * Práctica 7 - Fragmento de plantilla: inicio de página (<body>, enlace de salto, <header>, logotipo, <nav> y apertura de <main>).
 * Si $esPaginaPrivada es true, muestra la barra de navegación completa de usuario registrado;
 * en caso contrario, muestra la barra de navegación pública.
 */
$mostrarMenuPrivado = isset($esPaginaPrivada) && $esPaginaPrivada === true;
?>
  <body>
    <a class="skip-link" href="#main-content">Saltar al contenido principal</a>
    <header>
      <h1><img class="brand-mark" src="icon.svg" alt="" aria-hidden="true" />PI - Pisos e Inmuebles</h1>

      <nav aria-label="Navegación principal">
        <ul>
<?php if ($mostrarMenuPrivado): ?>
          <li><a href="index-reg.php"><i class="fa-solid fa-house" aria-label="Inicio" role="img"></i> Inicio</a></li>
          <li><a href="search.php"><i class="fa-solid fa-magnifying-glass" aria-label="Buscar anuncios" role="img"></i> Buscar anuncios</a></li>
          <li><a href="mis-anuncios.php"><i class="fa-solid fa-bullhorn" aria-label="Mis anuncios" role="img"></i> Mis anuncios</a></li>
          <li><a href="crear-anuncio.php"><i class="fa-solid fa-circle-plus" aria-label="Crear anuncio" role="img"></i> Crear anuncio</a></li>
          <li><a href="anadir-foto.php"><i class="fa-solid fa-camera" aria-label="Añadir foto" role="img"></i> Añadir foto</a></li>
          <li><a href="create-ad.php"><i class="fa-solid fa-file-lines" aria-label="Solicitar folleto" role="img"></i> Solicitar folleto</a></li>
          <li><a href="my-messages.php"><i class="fa-solid fa-envelope" aria-label="Mis mensajes" role="img"></i> Mis mensajes</a></li>
          <li><a href="index.php"><i class="fa-solid fa-right-from-bracket" aria-label="Cerrar sesión" role="img"></i> Salir</a></li>
          <li><a href="accesibilidad.php"><i class="fa-solid fa-universal-access" aria-label="Accesibilidad" role="img"></i> Accesibilidad</a></li>
          <li><a href="contact.php"><i class="fa-solid fa-envelope" aria-label="Contactar" role="img"></i> Contactar</a></li>
<?php else: ?>
          <li><a href="index.php"><i class="fa-solid fa-house" aria-label="Página principal" role="img"></i> Página principal</a></li>
          <li><a href="search.php"><i class="fa-solid fa-magnifying-glass" aria-label="Buscar" role="img"></i> Buscar</a></li>
          <li><a href="register.php"><i class="fa-solid fa-user-plus" aria-label="Registrarse" role="img"></i> Registrarse</a></li>
          <li><a href="accesibilidad.php"><i class="fa-solid fa-universal-access" aria-label="Accesibilidad" role="img"></i> Accesibilidad</a></li>
          <li><a href="contact.php"><i class="fa-solid fa-envelope" aria-label="Contactar" role="img"></i> Contactar</a></li>
<?php endif; ?>
        </ul>
      </nav>
    </header>

    <main id="main-content">
