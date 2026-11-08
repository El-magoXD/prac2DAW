<?php
/**
 * Práctica 7 - Página con el formulario de registro como nuevo usuario (register.php).
 * Nota: Se han desactivado las validaciones de HTML y JavaScript para poder comprobar
 * las validaciones en el servidor mediante PHP (register-response.php).
 */
$tituloPagina = 'Registrarse | PI - Pisos e Inmuebles';
$esPaginaPrivada = false;

require_once __DIR__ . '/includes/cabecera.php';
require_once __DIR__ . '/includes/inicio.php';

// Si se vuelve desde la página de respuesta con parámetros en la URL, inicializar los campos
$usuarioPrevio = $_GET['usuario'] ?? '';
$passwordPrevio = $_GET['password'] ?? '';
$password2Previo = $_GET['password2'] ?? '';
$emailPrevio = $_GET['email'] ?? '';
$sexoPrevio = $_GET['sexo'] ?? '';
$fechaPrevio = $_GET['fecha-nacimiento'] ?? '';
$ciudadPrevio = $_GET['ciudad'] ?? '';
$paisPrevio = $_GET['pais'] ?? '';

$errUsuario = $_GET['err_usuario'] ?? '';
$errPassword = $_GET['err_password'] ?? '';
$errPassword2 = $_GET['err_password2'] ?? '';

$mensajesErrorUrl = array_filter([$errUsuario, $errPassword, $errPassword2]);
?>
      <h2>Registro de nuevo usuario</h2>

      <form action="register-response.php" method="post" novalidate>
<?php if (!empty($mensajesErrorUrl)): ?>
        <div class="resumen-errores" role="alert">
          <p class="resumen-errores-titulo">Por favor, corrige los siguientes errores detectados:</p>
          <ul>
<?php foreach ($mensajesErrorUrl as $msg): ?>
            <li><?= e($msg) ?></li>
<?php endforeach; ?>
          </ul>
        </div>
<?php endif; ?>

        <fieldset>
          <legend>Datos de acceso</legend>

          <p>
            <label for="usuario" class="<?= $errUsuario !== '' ? 'etiqueta-error' : '' ?>">Nombre de usuario (*):</label>
            <input
              type="text"
              id="usuario"
              name="usuario"
              value="<?= e($usuarioPrevio) ?>"
              class="<?= $errUsuario !== '' ? 'campo-error' : '' ?>"
            />
            <small>De 3 a 15 caracteres (letras sin tildes/ñ y números; no puede empezar por número).</small>
<?php if ($errUsuario !== ''): ?>
            <span class="mensaje-error" role="alert"><?= e($errUsuario) ?></span>
<?php endif; ?>
          </p>

          <p>
            <label for="password" class="<?= $errPassword !== '' ? 'etiqueta-error' : '' ?>">Contraseña (*):</label>
            <input
              type="password"
              id="password"
              name="password"
              value="<?= e($passwordPrevio) ?>"
              class="<?= $errPassword !== '' ? 'campo-error' : '' ?>"
            />
            <small>De 6 a 15 caracteres (letras, números, - y _). Debe incluir mayúscula, minúscula y número.</small>
<?php if ($errPassword !== ''): ?>
            <span class="mensaje-error" role="alert"><?= e($errPassword) ?></span>
<?php endif; ?>
          </p>

          <p>
            <label for="password2" class="<?= $errPassword2 !== '' ? 'etiqueta-error' : '' ?>">Repetir contraseña (*):</label>
            <input
              type="password"
              id="password2"
              name="password2"
              value="<?= e($password2Previo) ?>"
              class="<?= $errPassword2 !== '' ? 'campo-error' : '' ?>"
            />
<?php if ($errPassword2 !== ''): ?>
            <span class="mensaje-error" role="alert"><?= e($errPassword2) ?></span>
<?php endif; ?>
          </p>

          <p>
            <label for="email">Dirección de email:</label>
            <input
              type="text"
              id="email"
              name="email"
              value="<?= e($emailPrevio) ?>"
              placeholder="ejemplo@dominio.com"
            />
          </p>
        </fieldset>

        <fieldset>
          <legend>Datos personales</legend>

          <p><span id="etiqueta-sexo">Sexo:</span></p>

          <p>
            <input
              type="radio"
              id="hombre"
              name="sexo"
              value="hombre"
              <?= $sexoPrevio === 'hombre' ? 'checked' : '' ?>
            />
            <label for="hombre">Hombre</label>
          </p>

          <p>
            <input
              type="radio"
              id="mujer"
              name="sexo"
              value="mujer"
              <?= $sexoPrevio === 'mujer' ? 'checked' : '' ?>
            />
            <label for="mujer">Mujer</label>
          </p>

          <p id="contenedor-error-sexo">
            <input
              type="radio"
              id="otro"
              name="sexo"
              value="otro"
              <?= $sexoPrevio === 'otro' ? 'checked' : '' ?>
            />
            <label for="otro">Otro</label>
          </p>

          <p>
            <label for="fecha-nacimiento">Fecha de nacimiento:</label>
            <input
              type="text"
              id="fecha-nacimiento"
              name="fecha-nacimiento"
              value="<?= e($fechaPrevio) ?>"
              placeholder="DD/MM/AAAA"
            />
            <small>Formato DD/MM/AAAA (debes ser mayor de 18 años).</small>
          </p>

          <p>
            <label for="ciudad">Ciudad de residencia:</label>
            <input
              type="text"
              id="ciudad"
              name="ciudad"
              value="<?= e($ciudadPrevio) ?>"
            />
          </p>

          <p>
            <label for="pais">País de residencia:</label>
            <select id="pais" name="pais">
              <option value="">Selecciona un país</option>
              <option value="espana" <?= $paisPrevio === 'espana' ? 'selected' : '' ?>>España</option>
              <option value="francia" <?= $paisPrevio === 'francia' ? 'selected' : '' ?>>Francia</option>
              <option value="italia" <?= $paisPrevio === 'italia' ? 'selected' : '' ?>>Italia</option>
              <option value="alemania" <?= $paisPrevio === 'alemania' ? 'selected' : '' ?>>Alemania</option>
              <option value="portugal" <?= $paisPrevio === 'portugal' ? 'selected' : '' ?>>Portugal</option>
              <option value="paises-bajos" <?= $paisPrevio === 'paises-bajos' ? 'selected' : '' ?>>Países Bajos</option>
              <option value="belgica" <?= $paisPrevio === 'belgica' ? 'selected' : '' ?>>Bélgica</option>
              <option value="irlanda" <?= $paisPrevio === 'irlanda' ? 'selected' : '' ?>>Irlanda</option>
              <option value="reino-unido" <?= $paisPrevio === 'reino-unido' ? 'selected' : '' ?>>Reino Unido</option>
              <option value="estados-unidos" <?= $paisPrevio === 'estados-unidos' ? 'selected' : '' ?>>Estados Unidos</option>
            </select>
          </p>

          <p>
            <label for="foto">Foto de perfil:</label>
            <input type="file" id="foto" name="foto" accept="image/*" />
          </p>
        </fieldset>

        <p>
          <button type="submit">Registrarse</button>
          <button type="reset">Limpiar formulario</button>
        </p>
      </form>
<?php
require_once __DIR__ . '/includes/pie.php';
?>
