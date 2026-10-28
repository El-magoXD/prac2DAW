/**
 * Módulo de validación del formulario de inicio de sesión (Página principal y Aviso).
 * Práctica 6: JavaScript
 */

/**
 * Comprueba que el valor de un campo de inicio de sesión (usuario o contraseña)
 * tenga contenido válido y no esté formado únicamente por espacios o tabuladores.
 * @param {string} valor - Valor actual del cuadro de texto.
 * @returns {boolean} true si el valor es válido; false si está vacío o solo tiene espacios/tabuladores.
 */
function validarCampoLogin(valor) {
  // TODO: Corresponde a PEDRO
  // Comprobar que 'valor' no sea vacío ni contenga únicamente espacios en blanco o tabuladores (apoyándose en esSoloEspacios).
}

/**
 * Manejador del evento submit del formulario de inicio de sesión (#form-login).
 * Obtiene los valores del HTML y los envía a comprobar antes de permitir el envío.
 * @param {Event} event - Objeto del evento submit.
 */
function validarFormularioLogin(event) {
  limpiarErroresFormulario("form-login");

  const usuario = $("usuario").value;
  const password = $("password").value;

  const usuarioValido = validarCampoLogin(usuario);
  const passwordValido = validarCampoLogin(password);

  // TODO: Corresponde a SÁNCHEZ
  // 1. Si !usuarioValido, llamar a mostrarErrorCampo("usuario", "...") con un mensaje claro.
  // 2. Si !passwordValido, llamar a mostrarErrorCampo("password", "...") con un mensaje claro.
  // 3. Si alguno de los dos no es válido, cancelar el envío del formulario con event.preventDefault().
}

/**
 * Conecta el formulario #form-login del HTML con la función validarFormularioLogin al cargar el DOM.
 */
function inicializarLogin() {
  const formLogin = $("form-login");
  if (formLogin) {
    formLogin.addEventListener("submit", validarFormularioLogin, false);
  }
}

document.addEventListener("DOMContentLoaded", inicializarLogin, false);
