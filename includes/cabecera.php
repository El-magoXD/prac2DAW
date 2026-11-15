<?php
/**
 * Práctica 7 y 8 - Fragmento de plantilla: cabecera del documento HTML (<doctype>, <head>, hojas de estilo).
 * Inicia la sesión (y restaura al usuario si tiene la cookie "Recordarme") antes de enviar cualquier salida HTML,
 * y aplica el estilo CSS almacenado en la sesión del usuario autenticado.
 */
require_once __DIR__ . '/funciones.php';

iniciarSesionYRecordar();

if (!isset($tituloPagina) || $tituloPagina === '') {
    $tituloPagina = 'PI - Pisos e Inmuebles';
}

$estilosDisponibles = [
    [
        'href'  => 'style.css',
        'title' => 'Estilo principal',
    ],
    [
        'href'  => 'css/noche.css',
        'title' => 'Modo noche',
    ],
    [
        'href'  => 'css/contraste.css',
        'title' => 'Alto contraste',
    ],
    [
        'href'  => 'css/grande.css',
        'title' => 'Texto grande',
    ],
    [
        'href'  => 'css/contrasteygrande.css',
        'title' => 'Alto contraste y texto grande',
    ],
];

$estiloUsuario = $_SESSION['estilo'] ?? 'style.css';
$estilosOrdenados = [];
$estilosAlternativos = [];

foreach ($estilosDisponibles as $itemEstilo) {
    if ($itemEstilo['href'] === $estiloUsuario) {
        $estilosOrdenados[] = $itemEstilo;
    } else {
        $estilosAlternativos[] = $itemEstilo;
    }
}

if (empty($estilosOrdenados)) {
    $estilosOrdenados[] = $estilosDisponibles[0];
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($tituloPagina) ?></title>
    <link rel="icon" type="image/svg+xml" href="icon.svg" />
<?php foreach ($estilosOrdenados as $Principal): ?>
    <link rel="stylesheet" href="<?= e($Principal['href']) ?>" media="screen" title="<?= e($Principal['title']) ?>" />
<?php endforeach; ?>
<?php foreach ($estilosAlternativos as $Alternativo): ?>
    <link rel="alternate stylesheet" href="<?= e($Alternativo['href']) ?>" media="screen" title="<?= e($Alternativo['title']) ?>" />
<?php endforeach; ?>
    <link rel="stylesheet" href="css/impresion.css" media="print" />
  </head>
