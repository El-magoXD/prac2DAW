<?php
/**
 * Práctica 7 - Fragmento de plantilla: cabecera del documento HTML (<doctype>, <head>, hojas de estilo).
 * Espera la variable $tituloPagina con el título específico de la página actual.
 */
require_once __DIR__ . '/funciones.php';

if (!isset($tituloPagina) || $tituloPagina === '') {
    $tituloPagina = 'PI - Pisos e Inmuebles';
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($tituloPagina) ?></title>
    <link rel="icon" type="image/svg+xml" href="icon.svg" />
    <link rel="stylesheet" href="style.css" media="screen" title="Estilo principal" />
    <link rel="alternate stylesheet" href="css/noche.css" media="screen" title="Modo noche" />
    <link rel="alternate stylesheet" href="css/contraste.css" media="screen" title="Alto contraste" />
    <link rel="alternate stylesheet" href="css/grande.css" media="screen" title="Texto grande" />
    <link rel="alternate stylesheet" href="css/contrasteygrande.css" media="screen" title="Alto contraste y texto grande" />
    <link rel="stylesheet" href="css/impresion.css" media="print" />
  </head>
