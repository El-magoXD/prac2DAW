/**
 * Práctica 6 - Validación algorítmica del formulario de registro de usuario (register.html)
 * Importante: Todas las validaciones se han implementado mediante algoritmos propios
 * sin emplear expresiones regulares.
 */

"use strict";

var CARACTERES_ESPECIALES_PARTE_LOCAL = "!#$%&'*+-/=?^_`{|}~";

/**
 * Valida el nombre de usuario:
 * - No vacío.
 * - Longitud entre 3 y 15 caracteres.
 * - Solo letras del alfabeto inglés (A-Z, a-z) y números (0-9).
 * - No puede comenzar con un número.
 * @param {string} valor Valor introducido.
 * @returns {string} Cadena vacía si es válido, o mensaje de error en caso contrario.
 */
function validarNombreUsuario(valor) {
  if (!tieneContenidoSinEspacios(valor)) {
    return "El nombre de usuario es obligatorio.";
  }

  if (valor.length < 3 || valor.length > 15) {
    return "El nombre de usuario debe tener entre 3 y 15 caracteres.";
  }

  if (esDigito(valor.charAt(0))) {
    return "El nombre de usuario no puede comenzar con un número.";
  }

  for (var i = 0; i < valor.length; i++) {
    var c = valor.charAt(i);
    if (!esAlfanumericoIngles(c)) {
      return "El nombre de usuario solo puede contener letras del alfabeto inglés (A-Z, a-z) y números (0-9), sin espacios ni eñes o tildes.";
    }
  }

  return "";
}

/**
 * Valida la contraseña:
 * - Longitud entre 6 y 15 caracteres.
 * - Solo letras del alfabeto inglés (A-Z, a-z), números (0-9), guion (-) y guion bajo (_).
 * - Al menos una letra mayúscula, una letra minúscula y un número.
 * @param {string} valor Contraseña introducida.
 * @returns {string} Cadena vacía si es válida, o mensaje de error.
 */
function validarContrasena(valor) {
  if (typeof valor !== "string" || valor.length === 0) {
    return "La contraseña es obligatoria.";
  }

  if (valor.length < 6 || valor.length > 15) {
    return "La contraseña debe tener entre 6 y 15 caracteres.";
  }

  var tieneMayuscula = false;
  var tieneMinuscula = false;
  var tieneNumero = false;

  for (var i = 0; i < valor.length; i++) {
    var c = valor.charAt(i);
    if (esLetraMayusculaInglesa(c)) {
      tieneMayuscula = true;
    } else if (esLetraMinusculaInglesa(c)) {
      tieneMinuscula = true;
    } else if (esDigito(c)) {
      tieneNumero = true;
    } else if (c !== "-" && c !== "_") {
      return "La contraseña solo puede contener letras del alfabeto inglés (A-Z, a-z), números (0-9), guion (-) y guion bajo (_).";
    }
  }

  if (!tieneMayuscula || !tieneMinuscula || !tieneNumero) {
    return "La contraseña debe incluir al menos una letra mayúscula, una letra minúscula y un número.";
  }

  return "";
}

/**
 * Valida la repetición de contraseña:
 * - Debe coincidir exactamente con la contraseña principal.
 * @param {string} password Contraseña original.
 * @param {string} password2 Repetición de la contraseña.
 * @returns {string} Cadena vacía si coincide, o mensaje de error.
 */
function validarRepetirContrasena(password, password2) {
  if (typeof password2 !== "string" || password2.length === 0) {
    return "Debes repetir la contraseña.";
  }

  if (password !== password2) {
    return "Las contraseñas introducidas no coinciden.";
  }

  return "";
}

/**
 * Comprueba si un carácter es válido en la parte-local de un email.
 * Permite A-Z, a-z, 0-9, !#$%&'*+-/=?^_`{|}~ y el punto (.).
 * @param {string} c Carácter a evaluar.
 * @returns {boolean}
 */
function esCaracterValidoParteLocal(c) {
  if (esAlfanumericoIngles(c)) {
    return true;
  }
  if (c === ".") {
    return true;
  }
  return CARACTERES_ESPECIALES_PARTE_LOCAL.indexOf(c) !== -1;
}

/**
 * Valida la dirección de email algorítmicamente según las reglas del enunciado:
 * - No vacío, longitud máxima total de 254 caracteres.
 * - Formato parte-local@dominio.
 * - parte-local: 1 a 64 caracteres; caracteres permitidos A-Z, a-z, 0-9, !#$%&'*+-/=?^_`{|}~ y punto (.);
 *   el punto no puede estar al principio ni al final, ni haber dos o más puntos seguidos.
 * - dominio: 1 a 255 caracteres; secuencia de uno o más subdominios separados por punto.
 * - subdominio: 1 a 63 caracteres; caracteres permitidos A-Z, a-z, 0-9 y guion (-);
 *   el guion no puede aparecer ni al principio ni al final del subdominio.
 * @param {string} valor Email introducido.
 * @returns {string} Cadena vacía si es válido, o mensaje de error.
 */
function validarDireccionEmail(valor) {
  if (!tieneContenidoSinEspacios(valor)) {
    return "La dirección de email es obligatoria.";
  }

  if (valor.length > 254) {
    return "La dirección de email no puede superar los 254 caracteres en total.";
  }

  var posArroba = valor.indexOf("@");
  if (posArroba === -1) {
    return "La dirección de email debe tener el formato parte-local@dominio (falta el símbolo @).";
  }

  if (valor.indexOf("@", posArroba + 1) !== -1) {
    return "La dirección de email solo puede contener un único símbolo @.";
  }

  var parteLocal = valor.substring(0, posArroba);
  var dominio = valor.substring(posArroba + 1);

  // Validación de parte-local
  if (parteLocal.length < 1 || parteLocal.length > 64) {
    return "La parte local del email (antes de @) debe tener entre 1 y 64 caracteres.";
  }

  if (
    parteLocal.charAt(0) === "." ||
    parteLocal.charAt(parteLocal.length - 1) === "."
  ) {
    return "El punto (.) no puede aparecer ni al principio ni al final de la parte local del email.";
  }

  if (parteLocal.indexOf("..") !== -1) {
    return "La parte local del email no puede contener dos o más puntos seguidos (..).";
  }

  for (var i = 0; i < parteLocal.length; i++) {
    if (!esCaracterValidoParteLocal(parteLocal.charAt(i))) {
      return "La parte local del email contiene caracteres no permitidos.";
    }
  }

  // Validación de dominio
  if (dominio.length < 1 || dominio.length > 255) {
    return "El dominio del email (después de @) debe tener entre 1 y 255 caracteres.";
  }

  var subdominios = dominio.split(".");
  for (var j = 0; j < subdominios.length; j++) {
    var sub = subdominios[j];
    if (sub.length < 1 || sub.length > 63) {
      return "Cada subdominio del email debe tener entre 1 y 63 caracteres (sin puntos vacíos al inicio, final o consecutivos).";
    }

    if (sub.charAt(0) === "-" || sub.charAt(sub.length - 1) === "-") {
      return "El guion (-) no puede estar al principio ni al final de un subdominio del email.";
    }

    for (var k = 0; k < sub.length; k++) {
      var cs = sub.charAt(k);
      if (!esAlfanumericoIngles(cs) && cs !== "-") {
        return "El dominio del email solo puede contener letras del alfabeto inglés, números, guiones (-) y puntos separadores.";
      }
    }
  }

  return "";
}

/**
 * Comprueba que se haya elegido una opción de sexo.
 * @param {HTMLFormElement} formulario Formulario de registro.
 * @returns {string} Cadena vacía si hay una opción marcada, o mensaje de error.
 */
function validarSexoSeleccionado(formulario) {
  var opcionesSexo = formulario.querySelectorAll('input[name="sexo"]');
  for (var i = 0; i < opcionesSexo.length; i++) {
    if (opcionesSexo[i].checked) {
      return "";
    }
  }
  return "Debes seleccionar una opción en el campo Sexo.";
}

/**
 * Extrae día, mes y año de una cadena de fecha sin usar expresiones regulares.
 * Soporta los formatos DD/MM/AAAA, DD-MM-AAAA y AAAA-MM-DD (o AAAA/MM/DD).
 * @param {string} texto Cadena con la fecha.
 * @returns {{dia: number, mes: number, anio: number}|null}
 */
function descomponerFecha(texto) {
  var limpio = texto.trim();
  var separador = "";

  if (limpio.indexOf("/") !== -1 && limpio.indexOf("-") === -1) {
    separador = "/";
  } else if (limpio.indexOf("-") !== -1 && limpio.indexOf("/") === -1) {
    separador = "-";
  } else {
    return null;
  }

  var partes = limpio.split(separador);
  if (partes.length !== 3) {
    return null;
  }

  if (
    !esCadenaSoloDigitos(partes[0]) ||
    !esCadenaSoloDigitos(partes[1]) ||
    !esCadenaSoloDigitos(partes[2])
  ) {
    return null;
  }

  var dia = 0;
  var mes = 0;
  var anio = 0;

  if (partes[0].length === 4) {
    // Formato AAAA-MM-DD o AAAA/MM/DD
    if (
      partes[1].length < 1 ||
      partes[1].length > 2 ||
      partes[2].length < 1 ||
      partes[2].length > 2
    ) {
      return null;
    }
    anio = parseInt(partes[0], 10);
    mes = parseInt(partes[1], 10);
    dia = parseInt(partes[2], 10);
  } else if (partes[2].length === 4) {
    // Formato DD/MM/AAAA o DD-MM-AAAA
    if (
      partes[0].length < 1 ||
      partes[0].length > 2 ||
      partes[1].length < 1 ||
      partes[1].length > 2
    ) {
      return null;
    }
    dia = parseInt(partes[0], 10);
    mes = parseInt(partes[1], 10);
    anio = parseInt(partes[2], 10);
  } else {
    return null;
  }

  return { dia: dia, mes: mes, anio: anio };
}

/**
 * Valida la fecha de nacimiento empleando el objeto Date:
 * - Debe ser una fecha válida del calendario (ej. rechaza 31/02/2000).
 * - La persona debe tener al menos 18 años cumplidos en el día exacto del registro.
 * @param {string} valor Cadena introducida en el campo de fecha de nacimiento.
 * @returns {string} Cadena vacía si es válida y mayor de edad, o mensaje de error.
 */
function validarFechaNacimiento(valor) {
  if (!tieneContenidoSinEspacios(valor)) {
    return "La fecha de nacimiento es obligatoria.";
  }

  var datosFecha = descomponerFecha(valor);
  if (!datosFecha) {
    return "Introduce una fecha de nacimiento con formato válido (por ejemplo, DD/MM/AAAA o AAAA-MM-DD).";
  }

  var dia = datosFecha.dia;
  var mes = datosFecha.mes;
  var anio = datosFecha.anio;

  if (anio < 1900 || mes < 1 || mes > 12 || dia < 1 || dia > 31) {
    return "La fecha de nacimiento introducida no es una fecha real válida.";
  }

  // Comprobación con el objeto Date para detectar fechas inexistentes como 30/02 o 31/04
  var fechaNacimiento = new Date(anio, mes - 1, dia);
  fechaNacimiento.setFullYear(anio);

  if (
    fechaNacimiento.getFullYear() !== anio ||
    fechaNacimiento.getMonth() !== mes - 1 ||
    fechaNacimiento.getDate() !== dia
  ) {
    return "La fecha introducida no existe en el calendario (revisa el número de días del mes).";
  }

  // Comprobación de mayoría de edad (>= 18 años en el día exacto actual)
  var hoy = new Date();
  var anioActual = hoy.getFullYear();
  var mesActual = hoy.getMonth() + 1;
  var diaActual = hoy.getDate();

  var fechaHoySinHora = new Date(anioActual, mesActual - 1, diaActual);
  if (fechaNacimiento > fechaHoySinHora) {
    return "La fecha de nacimiento no puede ser posterior a la fecha actual.";
  }

  var edad = anioActual - anio;
  if (mesActual < mes || (mesActual === mes && diaActual < dia)) {
    edad--;
  }

  if (edad < 18) {
    return "Debes tener al menos 18 años cumplidos para poder registrarte.";
  }

  return "";
}

/**
 * Ejecuta la validación completa del formulario de registro.
 * @param {Event} evento Evento submit del formulario.
 */
function validarFormularioRegistro(evento) {
  var formulario = evento.currentTarget;
  limpiarErroresFormulario(formulario);

  var campoUsuario = $id("usuario");
  var campoPassword = $id("password");
  var campoPassword2 = $id("password2");
  var campoEmail = $id("email");
  var campoFecha = $id("fecha-nacimiento");
  var primerRadioSexo = $id("hombre");
  var etiquetaGrupoSexo = $id("etiqueta-sexo");

  var errores = [];

  // 1. Nombre de usuario
  var errorUsuario = validarNombreUsuario(campoUsuario ? campoUsuario.value : "");
  if (errorUsuario !== "") {
    mostrarErrorCampo(campoUsuario, errorUsuario);
    errores.push({ campo: campoUsuario, mensaje: errorUsuario });
  }

  // 2. Contraseña
  var errorPass = validarContrasena(campoPassword ? campoPassword.value : "");
  if (errorPass !== "") {
    mostrarErrorCampo(campoPassword, errorPass);
    errores.push({ campo: campoPassword, mensaje: errorPass });
  }

  // 3. Repetir contraseña
  var errorPass2 = validarRepetirContrasena(
    campoPassword ? campoPassword.value : "",
    campoPassword2 ? campoPassword2.value : ""
  );
  if (errorPass2 !== "") {
    mostrarErrorCampo(campoPassword2, errorPass2);
    errores.push({ campo: campoPassword2, mensaje: errorPass2 });
  }

  // 4. Email
  var errorEmail = validarDireccionEmail(campoEmail ? campoEmail.value : "");
  if (errorEmail !== "") {
    mostrarErrorCampo(campoEmail, errorEmail);
    errores.push({ campo: campoEmail, mensaje: errorEmail });
  }

  // 5. Sexo
  var errorSexo = validarSexoSeleccionado(formulario);
  if (errorSexo !== "") {
    if (etiquetaGrupoSexo) {
      etiquetaGrupoSexo.classList.add("etiqueta-error");
    }
    var contenedorSexo = $id("contenedor-error-sexo") || primerRadioSexo;
    mostrarErrorCampo(contenedorSexo, errorSexo);
    errores.push({ campo: primerRadioSexo, mensaje: errorSexo });
  }

  // 6. Fecha de nacimiento
  var errorFecha = validarFechaNacimiento(campoFecha ? campoFecha.value : "");
  if (errorFecha !== "") {
    mostrarErrorCampo(campoFecha, errorFecha);
    errores.push({ campo: campoFecha, mensaje: errorFecha });
  }

  if (errores.length > 0) {
    evento.preventDefault();
    mostrarResumenErrores(formulario, errores);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var campoUsuario = $id("usuario");
  var campoPassword2 = $id("password2");

  if (campoUsuario && campoPassword2) {
    var formulario = campoUsuario.form;
    if (formulario) {
      formulario.addEventListener("submit", validarFormularioRegistro);

      formulario.addEventListener("reset", function () {
        limpiarErroresFormulario(formulario);
      });

      var idsCampos = [
        "usuario",
        "password",
        "password2",
        "email",
        "fecha-nacimiento"
      ];
      for (var i = 0; i < idsCampos.length; i++) {
        (function (idCampo) {
          var el = $id(idCampo);
          if (el) {
            el.addEventListener("input", function () {
              limpiarErrorCampo(el);
            });
          }
        })(idsCampos[i]);
      }

      var radiosSexo = formulario.querySelectorAll('input[name="sexo"]');
      for (var j = 0; j < radiosSexo.length; j++) {
        radiosSexo[j].addEventListener("change", function () {
          var etiquetaGrupoSexo = $id("etiqueta-sexo");
          if (etiquetaGrupoSexo) {
            etiquetaGrupoSexo.classList.remove("etiqueta-error");
          }
          var contenedorSexo = $id("contenedor-error-sexo") || $id("hombre");
          limpiarErrorCampo(contenedorSexo);
        });
      }
    }
  }
});

