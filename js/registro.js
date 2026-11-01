/**
 * Práctica 6 - Validación algorítmica del formulario de registro de usuario (register.html)
 * Importante: Todas las validaciones se han implementado mediante algoritmos propios
 * sin emplear expresiones regulares.
 */

"use strict";

const CARACTERES_ESPECIALES_PARTE_LOCAL = "!#$%&'*+-/=?^_`{|}~";

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

  for (let i = 0; i < valor.length; i++) {
    const c = valor.charAt(i);
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

  let tieneMayuscula = false;
  let tieneMinuscula = false;
  let tieneNumero = false;

  for (let i = 0; i < valor.length; i++) {
    const c = valor.charAt(i);
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

  const posArroba = valor.indexOf("@");
  if (posArroba === -1) {
    return "La dirección de email debe tener el formato parte-local@dominio (falta el símbolo @).";
  }

  if (valor.indexOf("@", posArroba + 1) !== -1) {
    return "La dirección de email solo puede contener un único símbolo @.";
  }

  const parteLocal = valor.substring(0, posArroba);
  const dominio = valor.substring(posArroba + 1);

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

  for (let i = 0; i < parteLocal.length; i++) {
    if (!esCaracterValidoParteLocal(parteLocal.charAt(i))) {
      return "La parte local del email contiene caracteres no permitidos.";
    }
  }

  // Validación de dominio
  if (dominio.length < 1 || dominio.length > 255) {
    return "El dominio del email (después de @) debe tener entre 1 y 255 caracteres.";
  }

  const subdominios = dominio.split(".");
  for (let j = 0; j < subdominios.length; j++) {
    const sub = subdominios[j];
    if (sub.length < 1 || sub.length > 63) {
      return "Cada subdominio del email debe tener entre 1 y 63 caracteres (sin puntos vacíos al inicio, final o consecutivos).";
    }

    if (sub.charAt(0) === "-" || sub.charAt(sub.length - 1) === "-") {
      return "El guion (-) no puede estar al principio ni al final de un subdominio del email.";
    }

    for (let k = 0; k < sub.length; k++) {
      const cs = sub.charAt(k);
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
  const opcionesSexo = formulario.querySelectorAll('input[name="sexo"]');
  for (let i = 0; i < opcionesSexo.length; i++) {
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
  const limpio = texto.trim();
  let separador = "";

  if (limpio.indexOf("/") !== -1 && limpio.indexOf("-") === -1) {
    separador = "/";
  } else if (limpio.indexOf("-") !== -1 && limpio.indexOf("/") === -1) {
    separador = "-";
  } else {
    return null;
  }

  const partes = limpio.split(separador);
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

  let dia = 0;
  let mes = 0;
  let anio = 0;

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

  const datosFecha = descomponerFecha(valor);
  if (!datosFecha) {
    return "Introduce una fecha de nacimiento con formato válido (por ejemplo, DD/MM/AAAA o AAAA-MM-DD).";
  }

  const dia = datosFecha.dia;
  const mes = datosFecha.mes;
  const anio = datosFecha.anio;

  if (anio < 1900 || mes < 1 || mes > 12 || dia < 1 || dia > 31) {
    return "La fecha de nacimiento introducida no es una fecha real válida.";
  }

  // Comprobación con el objeto Date para detectar fechas inexistentes como 30/02 o 31/04
  const fechaNacimiento = new Date(anio, mes - 1, dia);
  fechaNacimiento.setFullYear(anio);

  if (
    fechaNacimiento.getFullYear() !== anio ||
    fechaNacimiento.getMonth() !== mes - 1 ||
    fechaNacimiento.getDate() !== dia
  ) {
    return "La fecha introducida no existe en el calendario (revisa el número de días del mes).";
  }

  // Comprobación de mayoría de edad (>= 18 años en el día exacto actual)
  const hoy = new Date();
  const anioActual = hoy.getFullYear();
  const mesActual = hoy.getMonth() + 1;
  const diaActual = hoy.getDate();

  const fechaHoySinHora = new Date(anioActual, mesActual - 1, diaActual);
  if (fechaNacimiento > fechaHoySinHora) {
    return "La fecha de nacimiento no puede ser posterior a la fecha actual.";
  }

  let edad = anioActual - anio;
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
  const formulario = evento.currentTarget;
  limpiarErroresFormulario(formulario);

  const campoUsuario = $id("usuario");
  const campoPassword = $id("password");
  const campoPassword2 = $id("password2");
  const campoEmail = $id("email");
  const campoFecha = $id("fecha-nacimiento");
  const primerRadioSexo = $id("hombre");
  const etiquetaGrupoSexo = $id("etiqueta-sexo");

  const errores = [];

  // 1. Nombre de usuario
  const errorUsuario = validarNombreUsuario(campoUsuario ? campoUsuario.value : "");
  if (errorUsuario !== "") {
    mostrarErrorCampo(campoUsuario, errorUsuario);
    errores.push({ campo: campoUsuario, mensaje: errorUsuario });
  }

  // 2. Contraseña
  const errorPass = validarContrasena(campoPassword ? campoPassword.value : "");
  if (errorPass !== "") {
    mostrarErrorCampo(campoPassword, errorPass);
    errores.push({ campo: campoPassword, mensaje: errorPass });
  }

  // 3. Repetir contraseña
  const errorPass2 = validarRepetirContrasena(
    campoPassword ? campoPassword.value : "",
    campoPassword2 ? campoPassword2.value : ""
  );
  if (errorPass2 !== "") {
    mostrarErrorCampo(campoPassword2, errorPass2);
    errores.push({ campo: campoPassword2, mensaje: errorPass2 });
  }

  // 4. Email
  const errorEmail = validarDireccionEmail(campoEmail ? campoEmail.value : "");
  if (errorEmail !== "") {
    mostrarErrorCampo(campoEmail, errorEmail);
    errores.push({ campo: campoEmail, mensaje: errorEmail });
  }

  // 5. Sexo
  const errorSexo = validarSexoSeleccionado(formulario);
  if (errorSexo !== "") {
    if (etiquetaGrupoSexo) {
      etiquetaGrupoSexo.classList.add("etiqueta-error");
    }
    const contenedorSexo = $id("contenedor-error-sexo") || primerRadioSexo;
    mostrarErrorCampo(contenedorSexo, errorSexo);
    errores.push({ campo: primerRadioSexo, mensaje: errorSexo });
  }

  // 6. Fecha de nacimiento
  const errorFecha = validarFechaNacimiento(campoFecha ? campoFecha.value : "");
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
  const campoUsuario = $id("usuario");
  const campoPassword2 = $id("password2");

  if (campoUsuario && campoPassword2) {
    const formulario = campoUsuario.form;
    if (formulario) {
      formulario.addEventListener("submit", validarFormularioRegistro);

      formulario.addEventListener("reset", function () {
        limpiarErroresFormulario(formulario);
      });

      const idsCampos = [
        "usuario",
        "password",
        "password2",
        "email",
        "fecha-nacimiento"
      ];
      for (let i = 0; i < idsCampos.length; i++) {
        const el = $id(idsCampos[i]);
        if (el) {
          el.addEventListener("input", function () {
            limpiarErrorCampo(el);
          });
        }
      }

      const radiosSexo = formulario.querySelectorAll('input[name="sexo"]');
      for (let j = 0; j < radiosSexo.length; j++) {
        radiosSexo[j].addEventListener("change", function () {
          const etiquetaGrupoSexo = $id("etiqueta-sexo");
          if (etiquetaGrupoSexo) {
            etiquetaGrupoSexo.classList.remove("etiqueta-error");
          }
          const contenedorSexo = $id("contenedor-error-sexo") || $id("hombre");
          limpiarErrorCampo(contenedorSexo);
        });
      }
    }
  }
});

