<?php
/**
 * Práctica 7 - Respuesta "Solicitar folleto" (ad-response.php).
 * Muestra la confirmación del registro de la solicitud, los datos reales recibidos del formulario
 * y el coste final calculado aplicando las tarifas sobre valores ficticios de páginas y fotos
 * junto con los parámetros elegidos por el usuario (el número de copias multiplica el precio unitario).
 */
$tituloPagina = 'Folleto solicitado | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

// Recogida de parámetros enviados por el usuario (POST o GET)
$datos = !empty($_POST) ? $_POST : $_GET;

$codigoAnuncio = $datos['anuncio'] ?? 'piso-alicante';
$textoAdicional = $datos['texto_adicional'] ?? '';
$colorPortada = $datos['color_portada'] ?? '#000000';
$copias = isset($datos['copias']) && is_numeric($datos['copias']) ? max(1, min(99, (int) $datos['copias'])) : 1;
$resolucion = isset($datos['resolucion']) && is_numeric($datos['resolucion']) ? (int) $datos['resolucion'] : 150;
$impresion = $datos['impresion'] ?? 'blanco-negro';
$mostrarPrecio = $datos['mostrar_precio'] ?? 'si';

$nombre = $datos['nombre'] ?? '';
$email = $datos['email'] ?? '';
$calle = $datos['calle'] ?? '';
$numero = $datos['numero'] ?? '';
$piso = $datos['piso'] ?? '';
$puerta = $datos['puerta'] ?? '';
$codigoPostal = $datos['codigo_postal'] ?? '';
$localidad = $datos['localidad'] ?? '';
$provincia = $datos['provincia'] ?? '';
$pais = obtenerNombrePais($datos['pais'] ?? '');
$telefono = $datos['telefono'] ?? '';
$fechaRecepcion = $datos['fecha_recepcion'] ?? '';

// Valores ficticios de páginas y fotos del folleto según el anuncio seleccionado
if ($codigoAnuncio === 'apartamento-valencia') {
    $nombreAnuncio = 'Apartamento moderno en Valencia';
    $numPaginasFicticio = 8;
    $numFotosFicticio = 15;
} else {
    $nombreAnuncio = 'Piso luminoso en Alicante';
    $numPaginasFicticio = 5;
    $numFotosFicticio = 6;
}

$esColor = ($impresion === 'color');
$esAltaResolucion = ($resolucion > 300);

// Cálculo del precio unitario y precio total (el número de copias multiplica el precio unitario)
$costeUnitario = calcularCosteFolleto($numPaginasFicticio, $numFotosFicticio, $esColor, $esAltaResolucion);
$costeTotal = $costeUnitario * $copias;

$textoImpresion = $esColor ? 'A todo color' : 'Blanco y negro';
$textoMostrarPrecio = ($mostrarPrecio === 'no') ? 'No imprimir el precio' : 'Sí, imprimir el precio';
?>
      <h2>Folleto solicitado</h2>
      <p>
        Se ha registrado correctamente tu solicitud de folleto publicitario. A continuación se detallan los datos recibidos y el coste final calculado:
      </p>

      <fieldset>
        <legend>Resumen de la solicitud</legend>

        <h3><u>Datos del folleto</u></h3>

        <p><b>Anuncio seleccionado:</b> <?= e($nombreAnuncio) ?> (<?= $numPaginasFicticio ?> páginas, <?= $numFotosFicticio ?> fotos)</p>

        <p>
          <b>Texto adicional:</b>
          <?= tieneContenidoSinEspacios($textoAdicional) ? e($textoAdicional) : '<em>(Sin texto adicional)</em>' ?>
        </p>

        <p>
          <b>Color de la portada:</b>
          <input type="color" value="<?= e($colorPortada) ?>" disabled aria-label="Color de portada seleccionado" />
          <code><?= e($colorPortada) ?></code>
        </p>

        <p><b>Número de copias:</b> <?= $copias ?></p>

        <p><b>Resolución de las fotos:</b> <?= $resolucion ?> DPI</p>

        <p><b>Color de la impresión:</b> <?= e($textoImpresion) ?></p>

        <p><b>¿Quieres que aparezca el precio del anuncio?:</b> <?= e($textoMostrarPrecio) ?></p>

        <h3><u>Datos del destinatario</u></h3>

        <p><b>Nombre y apellidos:</b> <?= e($nombre) ?></p>

        <p><b>Correo electrónico:</b> <?= e($email) ?></p>

        <p><b>Calle:</b> <?= e($calle) ?></p>

        <p><b>Número:</b> <?= e($numero) ?></p>

        <p><b>Piso:</b> <?= e($piso) ?></p>

        <p><b>Puerta:</b> <?= e($puerta) ?></p>

        <p><b>Código postal:</b> <?= e($codigoPostal) ?></p>

        <p><b>Localidad:</b> <?= e(ucfirst($localidad)) ?></p>

        <p><b>Provincia:</b> <?= e(ucfirst($provincia)) ?></p>

        <p><b>País:</b> <?= e($pais) ?></p>

        <p><b>Teléfono:</b> <?= tieneContenidoSinEspacios($telefono) ? e($telefono) : '<em>(No especificado)</em>' ?></p>

        <p><b>Fecha aproximada de recepción:</b> <?= tieneContenidoSinEspacios($fechaRecepcion) ? e($fechaRecepcion) : '<em>(No especificada)</em>' ?></p>

        <h3><u>Coste del folleto publicitario</u></h3>
        <p><b>Parámetros de cálculo:</b> <?= $numPaginasFicticio ?> páginas y <?= $numFotosFicticio ?> fotos, impresión en <?= e(strtolower($textoImpresion)) ?>, resolución de <?= $resolucion ?> DPI.</p>
        <p><b>Precio unitario por folleto:</b> <?= e(formatearEuros($costeUnitario)) ?></p>
        <p class="anuncio"><b>Coste final total (<?= $copias ?> <?= $copias === 1 ? 'copia' : 'copias' ?>):</b> <?= e(formatearEuros($costeTotal)) ?></p>
      </fieldset>

      <p>
        <a class="btn-primary" href="create-ad.php">Solicitar otro folleto</a>
        <a class="btn-primary" href="index-reg.php">Volver al menú de usuario</a>
      </p>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
