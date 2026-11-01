/**
 * Práctica 6 - Validación del formulario de inicio de sesión (index.html y aviso.html)
 * Comprueba que el usuario haya escrito algo en ambos campos, evitando valores
 * que consistan únicamente en espacios en blanco o tabuladores.
 */

"use strict";

/**
 * Valida el formulario de login antes de enviarlo.
 * @param {Event} evento Evento submit del formulario.
 */
function validarFormularioLogin(evento) {
  var formulario = evento.currentTarget;
  var campoUsuario = $id("usuario");
  var campoPassword = $id("password");

  limpiarErroresFormulario(formulario);

  var errores = [];

  if (campoUsuario && !tieneContenidoSinEspacios(campoUsuario.value)) {
    var msgUsuario =
      "El nombre de usuario no puede estar vacío ni contener solo espacios o tabuladores.";
    mostrarErrorCampo(campoUsuario, msgUsuario);
    errores.push({ campo: campoUsuario, mensaje: msgUsuario });
  }

  if (campoPassword && !tieneContenidoSinEspacios(campoPassword.value)) {
    var msgPassword =
      "La contraseña no puede estar vacía ni contener solo espacios o tabuladores.";
    mostrarErrorCampo(campoPassword, msgPassword);
    errores.push({ campo: campoPassword, mensaje: msgPassword });
  }

  if (errores.length > 0) {
    evento.preventDefault();
    mostrarResumenErrores(formulario, errores);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var campoUsuario = $id("usuario");
  var campoPassword = $id("password");
  var campoPassword2 = $id("password2");

  // Solo inicializar en formularios de login (donde no existe password2)
  if (campoUsuario && campoPassword && !campoPassword2) {
    var formulario = campoUsuario.form;
    if (formulario) {
      formulario.addEventListener("submit", validarFormularioLogin);

      campoUsuario.addEventListener("input", function () {
        limpiarErrorCampo(campoUsuario);
      });
      campoPassword.addEventListener("input", function () {
        limpiarErrorCampo(campoPassword);
      });
    }
  }
});

