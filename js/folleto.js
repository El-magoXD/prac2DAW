/**
 * Módulo de generación de la tabla de posibles costes mediante DOM en "Solicitar folleto" (create-ad.html).
 * Importante: La tabla debe crearse nodo a nodo con funciones del DOM (createElement, createTextNode, appendChild)
 * sin utilizar innerHTML, y calculando dinámicamente cada celda según las tarifas.
 * Práctica 6: JavaScript
 */

/**
 * Calcula el coste total de impresión y envío de un folleto según las tarifas:
 * - Coste fijo de procesamiento y envío: 10 €
 * - Coste por página (sistema de bloques acumulativos, como las facturas de agua):
 *   * Primeras 4 páginas (< 5 páginas, es decir, páginas 1 a 4): 2,00 € por página.
 *   * Siguientes 6 páginas (entre 5 y 10 páginas, es decir, páginas 5 a 10): 1,80 € por página.
 *   * Páginas adicionales (> 10 páginas, es decir, de la 11 en adelante): 1,60 € por página.
 * - Coste por color: 0 € en blanco y negro; 0,50 € por foto en color.
 * - Coste por resolución: 0 € si <= 300 dpi; 0,20 € por foto si > 300 dpi.
 *
 * @param {number} paginas - Número de páginas del folleto (1 a 15).
 * @param {number} fotos - Número de fotos del folleto (3 a 45).
 * @param {boolean} enColor - true si es en color, false si es en blanco y negro.
 * @param {boolean} altaResolucion - true si es 450-900 dpi (> 300 dpi), false si es 150-300 dpi.
 * @returns {number} Coste total calculado en euros.
 */
function calcularCosteFolleto(paginas, fotos, enColor, altaResolucion) {
  // TODO: Corresponde a PEDRO
  // Implementar el cálculo aplicando el coste fijo (10€), el cálculo por tramos/bloques de páginas, el color y la resolución.
}

/**
 * Crea y devuelve el elemento <thead> de la tabla de posibles costes nodo a nodo con el DOM,
 * incluyendo las dos filas de cabeceras ("Blanco y negro", "Color", "Número de páginas",
 * "Número de fotos", "150-300 dpi" y "450-900 dpi") tal como aparece en la Figura 1.
 * @returns {HTMLTableSectionElement} Nodo <thead> construido con createElement y appendChild.
 */
function crearEncabezadoTablaCostes() {
  // TODO: Corresponde a SÁNCHEZ
  // Crear <thead>, las dos filas <tr> y las celdas <th> con colSpan/rowSpan y createTextNode sin usar innerHTML.
}

/**
 * Crea y devuelve la tabla completa (<table>) de posibles costes nodo a nodo con el DOM,
 * generando las 15 filas (de 1 a 15 páginas y de 3 a 45 fotos) y calculando los 4 costes
 * de cada fila mediante la función calcularCosteFolleto().
 * @returns {HTMLTableElement} Nodo <table> completo listo para añadir a la página.
 */
function crearTablaCostes() {
  // TODO: Corresponde a PEDRO
  // 1. Crear <table>, <caption> y añadir el <thead> devuelto por crearEncabezadoTablaCostes().
  // 2. Crear <tbody> y recorrer de 1 a 15 páginas (fotos = paginas * 3).
  // 3. En cada fila, calcular los 4 costes con calcularCosteFolleto(), formatearlos con 2 decimales y ' €' y añadirlos con createElement/createTextNode/appendChild.
}

/**
 * Muestra u oculta la tabla de posibles costes al pulsar el botón correspondiente,
 * actualizando también el texto del botón (ej. "Mostrar tabla de posibles costes" / "Ocultar tabla de posibles costes").
 */
function alternarTablaCostes() {
  // TODO: Corresponde a SÁNCHEZ
  // Comprobar si la tabla está oculta o visible (con style.display o classList/className) y alternar su visibilidad.
}

/**
 * Inicializa la sección de posibles costes en la página "Solicitar folleto" (create-ad.html):
 * crea el botón para mostrar/ocultar la tabla, genera la tabla (inicialmente oculta) y asocia el evento click.
 */
function inicializarFolleto() {
  // TODO: Corresponde a SÁNCHEZ
  // 1. Crear o localizar el botón de mostrar/ocultar tabla en create-ad.html.
  // 2. Generar la tabla con crearTablaCostes(), ocultarla inicialmente y añadirla al DOM.
  // 3. Asociar alternarTablaCostes al evento "click" del botón con addEventListener.
}

document.addEventListener("DOMContentLoaded", inicializarFolleto, false);

