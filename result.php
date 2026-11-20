<?php
/**
 * Práctica 7 y 9 - Página con el listado resultado de una búsqueda (result.php).
 * Ejecuta consultas SELECT preparadas contra la tabla Anuncios tanto desde la búsqueda rápida
 * (index.php) como desde el formulario avanzado (search.php), de forma insensible a mayúsculas/minúsculas
 * y validando todos los parámetros numéricos y de fecha para evitar errores.
 */
$tituloPagina = 'Resultados de Búsqueda | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$datosBusqueda = !empty($_GET) ? $_GET : $_POST;
$esBusquedaRapida = array_key_exists('busqueda', $datosBusqueda);

$erroresValidacion = [];
$filtros = [];
$resultados = [];

if ($esBusquedaRapida) {
    $terminoRapido = trim((string) ($datosBusqueda['busqueda'] ?? ''));
    $partesRapidas = parsearBusquedaRapida($terminoRapido);

    if ($partesRapidas['tipo_vivienda'] !== '') {
        $filtros['nombre_tipo_vivienda'] = $partesRapidas['tipo_vivienda'];
    }
    if ($partesRapidas['tipo_anuncio'] !== '') {
        $filtros['nombre_tipo_anuncio'] = $partesRapidas['tipo_anuncio'];
    }
    if ($partesRapidas['ciudad'] !== '') {
        $filtros['ciudad'] = $partesRapidas['ciudad'];
    }

    $resultados = buscarAnuncios($filtros);
} else {
    $tipoAnuncioRaw = trim((string) ($datosBusqueda['tipo_anuncio'] ?? ''));
    $tipoViviendaRaw = trim((string) ($datosBusqueda['tipo_vivienda'] ?? ''));
    $ciudad = trim((string) ($datosBusqueda['ciudad'] ?? ''));
    $paisRaw = trim((string) ($datosBusqueda['pais'] ?? ''));
    $precioMinRaw = trim((string) ($datosBusqueda['precio_min'] ?? ''));
    $precioMaxRaw = trim((string) ($datosBusqueda['precio_max'] ?? ''));
    $fechaMinRaw = trim((string) ($datosBusqueda['fecha_min'] ?? ''));
    $fechaMaxRaw = trim((string) ($datosBusqueda['fecha_max'] ?? ''));

    if ($tipoAnuncioRaw !== '') {
        if (ctype_digit($tipoAnuncioRaw) && (int) $tipoAnuncioRaw > 0) {
            $filtros['id_tipo_anuncio'] = (int) $tipoAnuncioRaw;
        } else {
            $erroresValidacion[] = 'El identificador de tipo de anuncio recibido no es válido.';
        }
    }

    if ($tipoViviendaRaw !== '') {
        if (ctype_digit($tipoViviendaRaw) && (int) $tipoViviendaRaw > 0) {
            $filtros['id_tipo_vivienda'] = (int) $tipoViviendaRaw;
        } else {
            $erroresValidacion[] = 'El identificador de tipo de vivienda recibido no es válido.';
        }
    }

    if ($ciudad !== '') {
        $filtros['ciudad'] = $ciudad;
    }

    if ($paisRaw !== '') {
        if (ctype_digit($paisRaw) && (int) $paisRaw > 0) {
            $filtros['id_pais'] = (int) $paisRaw;
        } else {
            $erroresValidacion[] = 'El identificador de país recibido no es válido.';
        }
    }

    if ($precioMinRaw !== '') {
        $precioMinNorm = str_replace(',', '.', $precioMinRaw);
        if (is_numeric($precioMinNorm) && (float) $precioMinNorm >= 0) {
            $filtros['precio_min'] = (float) $precioMinNorm;
        } else {
            $erroresValidacion[] = 'El precio mínimo introducido ("' . $precioMinRaw . '") no es un número válido y no se ha podido aplicar.';
        }
    }

    if ($precioMaxRaw !== '') {
        $precioMaxNorm = str_replace(',', '.', $precioMaxRaw);
        if (is_numeric($precioMaxNorm) && (float) $precioMaxNorm >= 0) {
            $filtros['precio_max'] = (float) $precioMaxNorm;
        } else {
            $erroresValidacion[] = 'El precio máximo introducido ("' . $precioMaxRaw . '") no es un número válido y no se ha podido aplicar.';
        }
    }

    if (
        isset($filtros['precio_min'], $filtros['precio_max']) &&
        $filtros['precio_min'] > $filtros['precio_max']
    ) {
        $erroresValidacion[] = 'El precio mínimo no puede ser superior al precio máximo.';
    }

    if ($fechaMinRaw !== '') {
        if (esFechaSqlValida($fechaMinRaw)) {
            $filtros['fecha_min'] = $fechaMinRaw;
        } else {
            $erroresValidacion[] = 'La fecha mínima introducida ("' . $fechaMinRaw . '") no tiene un formato de fecha válido (AAAA-MM-DD).';
        }
    }

    if ($fechaMaxRaw !== '') {
        if (esFechaSqlValida($fechaMaxRaw)) {
            $filtros['fecha_max'] = $fechaMaxRaw;
        } else {
            $erroresValidacion[] = 'La fecha máxima introducida ("' . $fechaMaxRaw . '") no tiene un formato de fecha válido (AAAA-MM-DD).';
        }
    }

    if (
        isset($filtros['fecha_min'], $filtros['fecha_max']) &&
        $filtros['fecha_min'] > $filtros['fecha_max']
    ) {
        $erroresValidacion[] = 'La fecha inicial no puede ser posterior a la fecha final.';
    }

    if (empty($erroresValidacion)) {
        $resultados = buscarAnuncios($filtros);
    }
}

$tiposAnunciosMapa = [];
foreach (obtenerTiposAnuncios() as $ta) {
    $tiposAnunciosMapa[(string) $ta['IdTAnuncio']] = (string) $ta['NomTAnuncio'];
}

$tiposViviendasMapa = [];
foreach (obtenerTiposViviendas() as $tv) {
    $tiposViviendasMapa[(string) $tv['IdTVivienda']] = (string) $tv['NomTVivienda'];
}
?>
      <section class="conBorde">
        <h2>Criterios de búsqueda recibidos</h2>
<?php if ($esBusquedaRapida): ?>
        <p><strong>Origen:</strong> Búsqueda rápida (Página principal)</p>
        <p>
          <strong>Texto buscado:</strong>
          <?= tieneContenidoSinEspacios($terminoRapido) ? e($terminoRapido) : '<em>(Cualquier anuncio - sin texto especificado)</em>' ?>
        </p>
        <ul>
          <li><strong>Tipo de vivienda interpretado:</strong> <?= $partesRapidas['tipo_vivienda'] !== '' ? e($partesRapidas['tipo_vivienda']) : '<em>Cualquiera</em>' ?></li>
          <li><strong>Tipo de anuncio interpretado:</strong> <?= $partesRapidas['tipo_anuncio'] !== '' ? e($partesRapidas['tipo_anuncio']) : '<em>Cualquiera</em>' ?></li>
          <li><strong>Ciudad interpretada:</strong> <?= $partesRapidas['ciudad'] !== '' ? e($partesRapidas['ciudad']) : '<em>Cualquiera</em>' ?></li>
        </ul>
<?php else: ?>
        <p><strong>Origen:</strong> Formulario de búsqueda avanzada</p>
        <ul>
          <li>
            <strong>Tipo de anuncio:</strong>
            <?= ($tipoAnuncioRaw !== '' && isset($tiposAnunciosMapa[$tipoAnuncioRaw])) ? e($tiposAnunciosMapa[$tipoAnuncioRaw]) : 'Cualquiera' ?>
          </li>
          <li>
            <strong>Tipo de vivienda:</strong>
            <?= ($tipoViviendaRaw !== '' && isset($tiposViviendasMapa[$tipoViviendaRaw])) ? e($tiposViviendasMapa[$tipoViviendaRaw]) : 'Cualquiera' ?>
          </li>
          <li>
            <strong>Ciudad:</strong>
            <?= tieneContenidoSinEspacios($ciudad) ? e($ciudad) : '<em>(No especificada)</em>' ?>
          </li>
          <li>
            <strong>País:</strong>
            <?= ($paisRaw !== '' && ctype_digit($paisRaw)) ? e(obtenerNombrePais($paisRaw)) : 'Cualquiera' ?>
          </li>
          <li>
            <strong>Precio mínimo:</strong>
            <?= isset($filtros['precio_min']) ? e(formatearEuros($filtros['precio_min'])) : '<em>(Sin límite mínimo)</em>' ?>
          </li>
          <li>
            <strong>Precio máximo:</strong>
            <?= isset($filtros['precio_max']) ? e(formatearEuros($filtros['precio_max'])) : '<em>(Sin límite máximo)</em>' ?>
          </li>
          <li>
            <strong>Fecha desde:</strong>
            <?= isset($filtros['fecha_min']) ? e(formatearFechaBD($filtros['fecha_min'])) : '<em>(No especificada)</em>' ?>
          </li>
          <li>
            <strong>Fecha hasta:</strong>
            <?= isset($filtros['fecha_max']) ? e(formatearFechaBD($filtros['fecha_max'])) : '<em>(No especificada)</em>' ?>
          </li>
        </ul>
<?php endif; ?>
        <p>
          <a class="btn-primary" href="search.php">Nueva búsqueda avanzada</a>
        </p>
      </section>

<?php if (!empty($erroresValidacion)): ?>
      <section class="conBorde">
        <h2>Errores en los criterios de búsqueda</h2>
        <div class="resumen-errores" role="alert">
          <p class="resumen-errores-titulo">No se ha podido realizar la búsqueda por los siguientes motivos:</p>
          <ul>
<?php foreach ($erroresValidacion as $errorMsg): ?>
            <li><?= e($errorMsg) ?></li>
<?php endforeach; ?>
          </ul>
        </div>
      </section>
<?php else: ?>
      <section>
        <h2>Resultados de la búsqueda (<?= count($resultados) ?>)</h2>
<?php if (empty($resultados)): ?>
        <p>Sin resultados que coincidan con los criterios de búsqueda.</p>
<?php else: ?>
        <p>Mostrando anuncios encontrados en la base de datos:</p>
<?php foreach ($resultados as $item): ?>
        <article>
          <a href="anuncio.php?id=<?= (int) $item['id'] ?>">
            <img
              src="<?= e($item['imagen']) ?>"
              width="300"
              alt="<?= e($item['alt']) ?>"
            />
            <h3><?= e($item['titulo']) ?></h3>
          </a>

          <p><strong>Tipo:</strong> <?= e($item['tipo_anuncio']) ?> — <?= e($item['tipo_vivienda']) ?></p>
          <p><strong>Fecha:</strong> <?= e($item['fecha']) ?></p>
          <p><strong>Ciudad:</strong> <?= e($item['ciudad']) ?></p>
          <p><strong>País:</strong> <?= e($item['pais']) ?></p>
          <p class="anuncio"><strong>Precio:</strong> <?= e($item['precio']) ?></p>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </section>
<?php endif; ?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
