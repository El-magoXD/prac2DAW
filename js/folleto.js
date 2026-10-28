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
  // Crear <thead>, las dos filas <tr> y las celdas <th> con colSpan y createTextNode sin usar innerHTML.
}

/**
 * Crea y devuelve la tabla completa (<table>) de posibles costes nodo a nodo con el DOM,
 * generando las 15 filas (de 1 a 15 páginas y de 3 a 45 fotos) y calculando los 4 costes
 * de cada fila mediante la función calcularCosteFolleto().
 * @returns {HTMLTableElement} Nodo <table> completo listo para añadir a la página.
 */
function crearTablaCostes() {
  // TODO: Corresponde a PEDRO
  // 1. Crear <table id="tabla-costes-folleto">, <caption> y añadir el <thead> devuelto por crearEncabezadoTablaCostes().
  // 2. Crear <tbody> y recorrer de 1 a 15 páginas (fotos = paginas * 3).
  // 3. En cada fila, calcular los 4 costes con calcularCosteFolleto(), formatearlos con 2 decimales y ' €' y añadirlos con createElement/createTextNode/appendChild.
}

/**
 * Manejador del evento click del botón #btn-tabla-costes en create-ad.html.
 * Muestra u oculta la tabla dentro de #contenedor-tabla-costes y actualiza el texto del botón.
 */
function alternarTablaCostes() {
  const boton = $("btn-tabla-costes");
  const contenedor = $("contenedor-tabla-costes");
  if (!boton || !contenedor) {
    return;
  }

  // TODO: Corresponde a SÁNCHEZ
  // 1. Si la tabla aún no se ha creado dentro de 'contenedor', crearla llamando a crearTablaCostes() y añadirla con contenedor.appendChild(...).
  // 2. Alternar la visibilidad de 'contenedor' (con style.display o className/classList) y cambiar el texto de 'boton' entre "Mostrar..." y "Ocultar...".
}

/**
 * Conecta el botón #btn-tabla-costes del HTML con la función alternarTablaCostes al cargar el DOM.
 */
function inicializarFolleto() {
  const botonTabla = $("btn-tabla-costes");
  if (botonTabla) {
    botonTabla.addEventListener("click", alternarTablaCostes, false);
  }
}

document.addEventListener("DOMContentLoaded", inicializarFolleto, false);
