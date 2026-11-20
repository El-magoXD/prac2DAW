<?php
/**
 * Práctica 9 - Página "Configurar" (configurar.php - parte privada).
 * Muestra el listado con todos los estilos alternativos disponibles en la tabla Estilos
 * de la base de datos y señala el estilo asignado al usuario en su sesión.
 */
require_once __DIR__ . '/includes/funciones.php';
exigirAutenticacion();

$tituloPagina = 'Configurar estilo | PI - Pisos e Inmuebles';
$esPaginaPrivada = true;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

$idUsuario = obtenerIdUsuarioActual();
$usuarioActualBD = obtenerUsuarioPorId($idUsuario);
$idEstiloActual = isset($usuarioActualBD['Estilo']) ? (int) $usuarioActualBD['Estilo'] : 0;
$estilosDisponiblesBD = obtenerEstilos();
?>
      <section class="conBorde">
        <h2>Configurar estilo visual</h2>
        <p>
          A continuación se muestran todos los estilos visuales disponibles en la base de datos que puede seleccionar el usuario.
          Actualmente tienes asignado en tu perfil el estilo
          <span class="badge"><?= e($usuarioActualBD['NombreEstilo'] ?? ($_SESSION['titulo_estilo'] ?? 'Estilo principal')) ?></span>.
        </p>
        <p>
          <small>Nota: la modificación interactiva del estilo en la base de datos se implementará en la siguiente práctica.</small>
        </p>
      </section>

      <section>
        <h2>Estilos disponibles (<?= count($estilosDisponiblesBD) ?>)</h2>
<?php if (empty($estilosDisponiblesBD)): ?>
        <p>No se han podido recuperar estilos desde la base de datos.</p>
<?php else: ?>
        <table>
          <caption>Listado de estilos disponibles en la tabla Estilos</caption>
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nombre</th>
              <th scope="col">Descripción</th>
              <th scope="col">Hoja de estilos</th>
              <th scope="col">Estado</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($estilosDisponiblesBD as $estiloItem): ?>
<?php $esActivo = ((int) $estiloItem['IdEstilo'] === $idEstiloActual); ?>
            <tr>
              <td><?= (int) $estiloItem['IdEstilo'] ?></td>
              <td><strong><?= e($estiloItem['Nombre']) ?></strong></td>
              <td><?= e($estiloItem['Descripcion']) ?></td>
              <td><code><?= e($estiloItem['Fichero']) ?></code></td>
              <td>
<?php if ($esActivo): ?>
                <span class="badge">Estilo actual</span>
<?php else: ?>
                Disponible
<?php endif; ?>
              </td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
<?php endif; ?>
        <p>
          <a class="btn-primary" href="index-reg.php">Volver al menú de usuario</a>
        </p>
      </section>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
