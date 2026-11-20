<?php
/**
 * Práctica 9 - Página "Mis datos" (mis-datos.php - parte privada).
 * Muestra los datos actuales del usuario obtenidos de la tabla Usuarios utilizando
 * el mismo formulario común y las mismas validaciones que el registro de nuevo usuario.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$tituloPagina = 'Mis datos | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$idUsuario = obtenerIdUsuarioActual();
$datosBD = obtenerUsuarioPorId($idUsuario);
$flashMisDatos = obtenerFlash('mis_datos_error', []);

$fechaFormateada = '';
if (!empty($datosBD['FNacimiento'])) {
    $fechaFormateada = formatearFechaBD((string) $datosBD['FNacimiento']);
}

if (!empty($flashMisDatos)) {
    $valoresUsuario = $flashMisDatos;
    $valoresUsuario['foto_actual'] = $datosBD['Foto'] ?? '';
    $erroresUsuario = $flashMisDatos;
} else {
    $valoresUsuario = [
        'usuario'          => $datosBD['NomUsuario'] ?? obtenerUsuarioActual(),
        'password'         => '',
        'password2'        => '',
        'email'            => $datosBD['Email'] ?? '',
        'sexo'             => (string) ($datosBD['Sexo'] ?? ''),
        'fecha-nacimiento' => $fechaFormateada,
        'ciudad'           => $datosBD['Ciudad'] ?? '',
        'pais'             => (string) ($datosBD['Pais'] ?? ''),
        'foto_actual'      => $datosBD['Foto'] ?? '',
    ];
    $erroresUsuario = [];
}

$modoFormularioUsuario = 'mis_datos';
?>
      <section class="conBorde">
        <h2>Mis datos de usuario</h2>
        <p>
          Consulta tus datos actuales registrados en el sistema.
          El formulario comparte la misma estructura y validaciones que el formulario de registro.
        </p>
        <ul>
          <li><strong>Fecha de incorporación:</strong> <?= e(formatearFechaBD($datosBD['FRegistro'] ?? '', true)) ?></li>
          <li><strong>Estilo asignado:</strong> <span class="badge"><?= e($datosBD['NombreEstilo'] ?? 'Estilo principal') ?></span></li>
        </ul>
      </section>

<?php
require __DIR__ . '/includes/formulario_usuario.php';
?>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
