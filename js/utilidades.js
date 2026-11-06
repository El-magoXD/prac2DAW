/**
 * Práctica 6 - Utilidades generales de validación y manipulación del DOM
 * Nota: No se utilizan expresiones regulares en ninguna función de validación.
 */

"use strict";

/**
 * Devuelve el elemento del DOM con el identificador indicado.
 * @param {string} id Identificador del elemento.
 * @returns {HTMLElement|null}
 */
function $id(id) {
  return document.getElementById(id);
}

/**
 * Comprueba si un carácter pertenece a las letras mayúsculas del alfabeto inglés (A-Z).
 * @param {string} c Carácter de longitud 1.
 * @returns {boolean}
 */
function esLetraMayusculaInglesa(c) {
  if (typeof c !== "string" || c.length !== 1) {
    return false;
  }
  const codigo = c.charCodeAt(0);
  return codigo >= 65 && codigo <= 90; // 'A' (65) a 'Z' (90)
}

/**
 * Comprueba si un carácter pertenece a las letras minúsculas del alfabeto inglés (a-z).
 * @param {string} c Carácter de longitud 1.
 * @returns {boolean}
 */
function esLetraMinusculaInglesa(c) {
  if (typeof c !== "string" || c.length !== 1) {
    return false;
  }
  const codigo = c.charCodeAt(0);
  return codigo >= 97 && codigo <= 122; // 'a' (97) a 'z' (122)
}

/**
 * Comprueba si un carácter pertenece al alfabeto inglés (A-Z o a-z).
 * @param {string} c Carácter de longitud 1.
 * @returns {boolean}
 */
function esLetraInglesa(c) {
  return esLetraMayusculaInglesa(c) || esLetraMinusculaInglesa(c);
}

/**
 * Comprueba si un carácter es un dígito numérico (0-9).
 * @param {string} c Carácter de longitud 1.
 * @returns {boolean}
 */
function esDigito(c) {
  if (typeof c !== "string" || c.length !== 1) {
    return false;
  }
  const codigo = c.charCodeAt(0);
  return codigo >= 48 && codigo <= 57; // '0' (48) a '9' (57)
}

/**
 * Comprueba si un carácter es alfanumérico del alfabeto inglés (A-Z, a-z, 0-9).
 * @param {string} c Carácter de longitud 1.
 * @returns {boolean}
 */
function esAlfanumericoIngles(c) {
  return esLetraInglesa(c) || esDigito(c);
}

/**
 * Comprueba que una cadena no esté vacía ni formada únicamente por espacios o tabuladores.
 * @param {string} texto Cadena a comprobar.
 * @returns {boolean} true si contiene al menos un carácter distinto de espacio/tabulador.
 */
function tieneContenidoSinEspacios(texto) {
  if (typeof texto !== "string" || texto.length === 0) {
    return false;
  }
  for (let i = 0; i < texto.length; i++) {
    const c = texto.charAt(i);
    if (c !== " " && c !== "\t" && c !== "\n" && c !== "\r") {
      return true;
    }
  }
  return false;
}

/**
 * Comprueba si todos los caracteres de una cadena son dígitos numéricos (0-9).
 * @param {string} cadena Cadena a evaluar.
 * @returns {boolean}
 */
function esCadenaSoloDigitos(cadena) {
  if (typeof cadena !== "string" || cadena.length === 0) {
    return false;
  }
  for (let i = 0; i < cadena.length; i++) {
    if (!esDigito(cadena.charAt(i))) {
      return false;
    }
  }
  return true;
}

/**
 * Muestra un mensaje de error junto a un campo del formulario y resalta su etiqueta y borde.
 * Construye los nodos mediante métodos estándar del DOM (createElement / createTextNode).
 * @param {HTMLElement} campo Elemento input/select/contenedor que presenta el error.
 * @param {string} mensaje Texto explicativo del error.
 */
function mostrarErrorCampo(campo, mensaje) {
  if (!campo) {
    return;
  }

  limpiarErrorCampo(campo);

  campo.classList.add("campo-error");
  campo.setAttribute("aria-invalid", "true");

  if (campo.id) {
    const etiqueta = document.querySelector(`label[for="${campo.id}"]`);
    if (etiqueta) {
      etiqueta.classList.add("etiqueta-error");
    }
  }

  const contenedor = campo.closest("p") || campo.parentElement;
  if (contenedor) {
    const spanError = document.createElement("span");
    spanError.className = "mensaje-error";
    spanError.setAttribute("role", "alert");
    if (campo.id) {
      const idError = `error-${campo.id}`;
      spanError.id = idError;
      campo.setAttribute("aria-describedby", idError);
    }
    const nodoTexto = document.createTextNode(mensaje);
    spanError.appendChild(nodoTexto);
    contenedor.appendChild(spanError);
  }
}

/**
 * Elimina el estado y mensaje de error asociados a un campo del formulario.
 * @param {HTMLElement} campo Elemento del formulario.
 */
function limpiarErrorCampo(campo) {
  if (!campo) {
    return;
  }

  campo.classList.remove("campo-error");
  campo.removeAttribute("aria-invalid");
  campo.removeAttribute("aria-describedby");

  if (campo.id) {
    const etiqueta = document.querySelector(`label[for="${campo.id}"]`);
    if (etiqueta) {
      etiqueta.classList.remove("etiqueta-error");
    }
  }

  const contenedor = campo.closest("p") || campo.parentElement;
  if (contenedor) {
    const erroresPrevios = contenedor.querySelectorAll(".mensaje-error");
    for (let i = 0; i < erroresPrevios.length; i++) {
      erroresPrevios[i].parentNode.removeChild(erroresPrevios[i]);
    }
  }
}

/**
 * Muestra un bloque resumen al principio del formulario con todos los errores detectados.
 * @param {HTMLFormElement} formulario Formulario validado.
 * @param {Array<{campo: HTMLElement, mensaje: string}>} errores Lista de errores.
 */
function mostrarResumenErrores(formulario, errores) {
  if (!formulario || !errores || errores.length === 0) {
    return;
  }

  const resumenPrevio = formulario.querySelector(".resumen-errores");
  if (resumenPrevio && resumenPrevio.parentNode) {
    resumenPrevio.parentNode.removeChild(resumenPrevio);
  }

  const cajaResumen = document.createElement("div");
  cajaResumen.className = "resumen-errores";
  cajaResumen.setAttribute("role", "alert");
  cajaResumen.setAttribute("tabindex", "-1");

  const titulo = document.createElement("p");
  titulo.className = "resumen-errores-titulo";
  titulo.appendChild(
    document.createTextNode(
      "Por favor, corrige los siguientes errores antes de continuar:"
    )
  );
  cajaResumen.appendChild(titulo);

  const lista = document.createElement("ul");
  for (let i = 0; i < errores.length; i++) {
    const item = document.createElement("li");
    item.appendChild(document.createTextNode(errores[i].mensaje));
    lista.appendChild(item);
  }
  cajaResumen.appendChild(lista);

  formulario.insertBefore(cajaResumen, formulario.firstChild);

  if (errores[0] && errores[0].campo && typeof errores[0].campo.focus === "function") {
    errores[0].campo.focus();
  }
}

/**
 * Limpia todos los mensajes de error y estilos de error de un formulario completo.
 * @param {HTMLFormElement} formulario Formulario a limpiar.
 */
function limpiarErroresFormulario(formulario) {
  if (!formulario) {
    return;
  }

  const resumenPrevio = formulario.querySelector(".resumen-errores");
  if (resumenPrevio && resumenPrevio.parentNode) {
    resumenPrevio.parentNode.removeChild(resumenPrevio);
  }

  const camposConError = formulario.querySelectorAll(".campo-error");
  for (let i = 0; i < camposConError.length; i++) {
    camposConError[i].classList.remove("campo-error");
    camposConError[i].removeAttribute("aria-invalid");
    camposConError[i].removeAttribute("aria-describedby");
  }

  const etiquetasConError = formulario.querySelectorAll(".etiqueta-error");
  for (let j = 0; j < etiquetasConError.length; j++) {
    etiquetasConError[j].classList.remove("etiqueta-error");
  }

  const mensajesError = formulario.querySelectorAll(".mensaje-error");
  for (let k = 0; k < mensajesError.length; k++) {
    if (mensajesError[k].parentNode) {
      mensajesError[k].parentNode.removeChild(mensajesError[k]);
    }
  }
}

