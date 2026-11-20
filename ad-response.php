<?php
/**
 * Práctica 7, 8 y 9 - Respuesta "Solicitar folleto" (ad-response.php - parte privada).
 * Protegida mediante sesión PHP (exigirAutenticacion()).
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$tituloPagina = 'Folleto solicitado | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$datos = !empty($_POST) ? $_POST : $_GET;

$codigoAnuncio = (string) ($datos['anuncio'] ?? '');
$textoAdicional = (string) ($datos['texto_adicional'] ?? '');
$colorPortada = (string) ($datos['color_portada'] ?? '#000000');
$copias = isset($datos['copias']) && is_numeric($datos['copias']) ? max(1, min(99, (int) $datos['copias'])) : 1;
$resolucion = isset($datos['resolucion']) && is_numeric($datos['resolucion']) ? max(150, min(900, (int) $datos['resolucion'])) : 150;
$impresion = (string) ($datos['impresion'] ?? 'blanco-negro');
$mostrarPrecio = (string) ($datos['mostrar_precio'] ?? 'si');

$nombre = (string) ($datos['nombre'] ?? '');
$email = (string) ($datos['email'] ?? '');
$calle = (string) ($datos['calle'] ?? '');
$numero = (string) ($datos['numero'] ?? '');
$piso = (string) ($datos['piso'] ?? '');
$puerta = (string) ($datos['puerta'] ?? '');
$codigoPostal = (string) ($datos['codigo_postal'] ?? '');
$localidad = (string) ($datos['localidad'] ?? '');
$provincia = (string) ($datos['provincia'] ?? '');
$pais = obtenerNombrePais($datos['pais'] ?? '');
$telefono = (string) ($datos['telefono'] ?? '');
$fechaRecepcion = (string) ($datos['fecha_recepcion'] ?? '');

$nombreAnuncio = 'Anuncio seleccionado';
$numPaginasFicticio = 5;
$numFotosFicticio = 6;

if (ctype_digit($codigoAnuncio) && (int) $codigoAnuncio > 0) {
    $galeriaAnuncio = obtenerGaleriaAnuncio((int) $codigoAnuncio);
    if ($galeriaAnuncio !== null) {
        $nombreAnuncio = $galeriaAnuncio['anuncio']['titulo'];
        $numFotosFicticio = (int) $galeriaAnuncio['total'];
        $numPaginasFicticio = max(3, $numFotosFicticio + 2);
    }
}

$esColor = ($impresion === 'color');
$esAltaResolucion = ($resolucion > 300);

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
