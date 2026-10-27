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
  // Comprobar que 'valor' no sea vacío ni contenga solo espacios en blanco o tabuladores.
}

/**
 * Manejador del evento submit del formulario de inicio de sesión.
 * Valida los campos de nombre de usuario y contraseña antes de permitir el envío.
 * @param {Event} event - Objeto del evento submit.
 */
function validarFormularioLogin(event) {
  // TODO: Corresponde a SÁNCHEZ
  // 1. Limpiar los errores previos del formulario.
  // 2. Obtener los valores de los campos 'usuario' y 'password'.
  // 3. Comprobar ambos con validarCampoLogin().
  // 4. Si alguno falla, mostrar mensaje de error con mostrarErrorCampo() y cancelar el envío con event.preventDefault().
}

/**
 * Inicializa los eventos de validación del formulario de login cuando el DOM está listo.
 */
function inicializarLogin() {
  // TODO: Corresponde a PEDRO
  // 1. Desactivar la validación nativa de HTML (ej. form.noValidate = true) para que actúe la validación JS.
  // 2. Asociar validarFormularioLogin al evento "submit" del formulario de login con addEventListener.
}

document.addEventListener("DOMContentLoaded", inicializarLogin, false);

