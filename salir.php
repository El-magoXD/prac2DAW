<?php
/**
 * Práctica 8 - Cerrar sesión / Salir (salir.php).
 * Finaliza la sesión del usuario, borra los datos recordados ("Recordarme en este equipo")
 * y borra también la cookie de los últimos anuncios visitados (páginas 4, 6 y 10 del PDF).
 */
require_once __DIR__ . '/includes/funciones.php';

asegurarSesionIniciada();

// 1. Borrar cookies de "Recordarme en este equipo" (si existían)
borrarCookiesRecordar();

// 2. Borrar cookie del panel "Últimos anuncios visitados"
borrarCookieSegura('ultimos_anuncios');

// 3. Borrar todas las variables de sesión
$_SESSION = array();

// 4. Borrar la cookie que almacena el identificador de la sesión (PHPSESSID)
borrarCookieSegura(session_name());

// 5. Destruir finalmente la sesión en el servidor
session_destroy();

// 6. Redirigir a la página principal
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$uri = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\');
header("Location: http://$host$uri/index.php");
exit;
