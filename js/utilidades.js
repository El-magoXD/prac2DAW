/**
 * Módulo de utilidades comunes del DOM y gestión visual de errores en formularios.
 * Práctica 6: JavaScript
 */

/**
 * Devuelve el elemento del DOM correspondiente al identificador indicado.
 * Equivalente abreviado de document.getElementById(id).
 * @param {string} id - Identificador único del elemento HTML.
 * @returns {HTMLElement|null} Elemento del DOM o null si no existe.
 */
function $(id) {
  return document.getElementById(id);
}

/**
 * Comprueba (sin usar expresiones regulares) si una cadena está vacía
 * o contiene únicamente espacios en blanco o tabuladores (' ' o '\t').
 * @param {string} cadena - Texto introducido por el usuario.
 * @returns {boolean} true si está vacía o solo tiene espacios/tabuladores; false en caso contrario.
 */
function esSoloEspacios(cadena) {
  // TODO: Corresponde a SÁNCHEZ
  // Recorrer la cadena o usar trim() para comprobar que no esté vacía ni formada solo por espacios/tabuladores.
}

/**
 * Muestra un mensaje de error claro junto al campo erróneo (o al inicio del formulario)
 * creando los nodos necesarios con el DOM, y cambia el estilo visual del campo/etiqueta.
 * @param {string} idCampo - ID del campo de formulario que contiene el error.
 * @param {string} mensaje - Mensaje descriptivo del error para ayudar al usuario.
 */
function mostrarErrorCampo(idCampo, mensaje) {
  const campo = $(idCampo);
  if (!campo) {
    return;
  }

  // TODO: Corresponde a PEDRO
  // 1. Cambiar la presentación visual de 'campo' o su etiqueta (ej. campo.className o campo.style.borderColor).
  // 2. Crear un nodo de elemento (document.createElement) y un nodo de texto (document.createTextNode(mensaje)).
  // 3. Insertarlo en el DOM junto a 'campo' (ej. campo.parentNode.appendChild(...)).
}

/**
 * Elimina todos los mensajes de error mostrados previamente en un formulario
 * y restaura el aspecto visual original de todos sus campos y etiquetas.
 * @param {string} idFormulario - ID del formulario a limpiar.
 */
function limpiarErroresFormulario(idFormulario) {
  const formulario = $(idFormulario);
  if (!formulario) {
    return;
  }

  // TODO: Corresponde a SÁNCHEZ
  // 1. Buscar dentro de 'formulario' los nodos de mensajes de error previos y eliminarlos del DOM.
  // 2. Restaurar las clases o estilos originales de los campos del formulario.
}
