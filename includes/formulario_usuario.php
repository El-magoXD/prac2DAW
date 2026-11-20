<?php
/**
 * Práctica 9 - Formulario común para registro de nuevo usuario y visualización/modificación de "Mis datos"
 * (Pág. 9 del enunciado: aislar ambos formularios en un único fichero para compartir validaciones).
 */
$modoActual = $modoFormularioUsuario ?? 'registro';
$esMisDatos = ($modoActual === 'mis_datos');

$usuarioPrevio = $valoresUsuario['usuario'] ?? '';
$passwordPrevio = $valoresUsuario['password'] ?? '';
$password2Previo = $valoresUsuario['password2'] ?? '';
$emailPrevio = $valoresUsuario['email'] ?? '';
$sexoPrevio = (string) ($valoresUsuario['sexo'] ?? '');
$fechaPrevio = $valoresUsuario['fecha-nacimiento'] ?? '';
$ciudadPrevio = $valoresUsuario['ciudad'] ?? '';
$paisSeleccionado = (string) ($valoresUsuario['pais'] ?? '');
$fotoActualUsuario = $valoresUsuario['foto_actual'] ?? '';

$errUsuario = $erroresUsuario['err_usuario'] ?? '';
$errPassword = $erroresUsuario['err_password'] ?? '';
$errPassword2 = $erroresUsuario['err_password2'] ?? '';
$mensajesError = $erroresUsuario['mensajes'] ?? array_values(array_filter([$errUsuario, $errPassword, $errPassword2]));
?>
      <form action="register-response.php" method="post" novalidate>
        <input type="hidden" name="modo_formulario" value="<?= e($modoActual) ?>" />
<?php if (!empty($mensajesError)): ?>
        <div class="resumen-errores" role="alert">
          <p class="resumen-errores-titulo">Por favor, corrige los siguientes errores detectados:</p>
          <ul>
<?php foreach ($mensajesError as $msg): ?>
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
              value="1"
              <?= ($sexoPrevio === '1' || $sexoPrevio === 'hombre') ? 'checked' : '' ?>
            />
            <label for="hombre">Hombre</label>
          </p>

          <p>
            <input
              type="radio"
              id="mujer"
              name="sexo"
              value="2"
              <?= ($sexoPrevio === '2' || $sexoPrevio === 'mujer') ? 'checked' : '' ?>
            />
            <label for="mujer">Mujer</label>
          </p>

          <p id="contenedor-error-sexo">
            <input
              type="radio"
              id="otro"
              name="sexo"
              value="3"
              <?= ($sexoPrevio === '3' || $sexoPrevio === 'otro') ? 'checked' : '' ?>
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
<?php
$paisObligatorio = false;
$textoOpcionVaciaPais = 'Selecciona un país';
require __DIR__ . '/select_paises.php';
?>
          </p>

<?php if ($esMisDatos && $fotoActualUsuario !== ''): ?>
          <p>
            <strong>Foto de perfil actual:</strong><br />
            <img src="<?= e($fotoActualUsuario) ?>" width="160" alt="Foto de perfil de <?= e($usuarioPrevio) ?>" />
          </p>
<?php endif; ?>

          <p>
            <label for="foto">Foto de perfil:</label>
            <input type="file" id="foto" name="foto" accept="image/*" />
          </p>
        </fieldset>

        <p>
          <button type="submit"><?= $esMisDatos ? 'Guardar cambios' : 'Registrarse' ?></button>
          <button type="reset">Limpiar formulario</button>
<?php if ($esMisDatos): ?>
          <a class="btn-primary" href="index-reg.php">Volver al menú de usuario</a>
<?php endif; ?>
        </p>
      </form>
