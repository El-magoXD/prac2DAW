/**
 * Práctica 6 - Generación dinámica de la tabla de costes de un folleto publicitario (create-ad.html)
 * Importante: La tabla se construye nodo a nodo empleando exclusivamente métodos estándar del DOM
 * (createElement, createTextNode, appendChild) y calculando cada celda en tiempo de ejecución.
 */

"use strict";

/**
 * Calcula el coste total de impresión y envío de un folleto según las tarifas por bloques:
 * - Coste fijo de procesamiento y envío: 10 €
 * - Primeras 4 páginas (< 5 páginas): 2,00 € por página
 * - Entre 5 y 10 páginas (siguientes 6 páginas): 1,80 € por página
 * - Más de 10 páginas (a partir de la página 11): 1,60 € por página
 * - Color: 0 € en blanco y negro; 0,50 € por foto en color
 * - Resolución: 0 € para <= 300 dpi; 0,20 € por foto para > 300 dpi
 *
 * @param {number} numPaginas Número de páginas del folleto (1..15).
 * @param {number} numFotos Número de fotos del folleto (3..45).
 * @param {boolean} esColor true si la impresión es en color, false si es blanco y negro.
 * @param {boolean} esAltaResolucion true si la resolución es > 300 dpi (450-900 dpi).
 * @returns {number} Coste total en euros.
 */
function calcularCosteFolleto(numPaginas, numFotos, esColor, esAltaResolucion) {
  var costeFijoEnvio = 10.0;

  var paginasTramo1 = Math.min(numPaginas, 4);
  var paginasTramo2 = Math.max(0, Math.min(numPaginas - 4, 6));
  var paginasTramo3 = Math.max(0, numPaginas - 10);

  var costePaginas =
    paginasTramo1 * 2.0 + paginasTramo2 * 1.8 + paginasTramo3 * 1.6;

  var costeImpresionColor = esColor ? numFotos * 0.5 : 0.0;
  var costeResolucionFotos = esAltaResolucion ? numFotos * 0.2 : 0.0;

  return (
    costeFijoEnvio +
    costePaginas +
    costeImpresionColor +
    costeResolucionFotos
  );
}

/**
 * Formatea un importe numérico en euros con dos decimales y coma decimal (ej. "19,80 €").
 * @param {number} importe Valor numérico en euros.
 * @returns {string} Importe formateado.
 */
function formatearEuros(importe) {
  return importe.toFixed(2).replace(".", ",") + " €";
}

/**
 * Crea una celda de tabla (<th> o <td>) con su texto mediante createElement y createTextNode.
 * @param {string} tipoEtiqueta "th" o "td".
 * @param {string} texto Contenido textual de la celda.
 * @param {string} [scope] Atributo scope opcional para celdas <th>.
 * @returns {HTMLTableCellElement}
 */
function crearCeldaTabla(tipoEtiqueta, texto, scope) {
  var celda = document.createElement(tipoEtiqueta);
  if (scope) {
    celda.setAttribute("scope", scope);
  }
  celda.appendChild(document.createTextNode(texto));
  return celda;
}

/**
 * Construye el árbol DOM completo de la tabla de posibles costes de un folleto.
 * @returns {HTMLTableElement} Elemento <table> construido nodo a nodo.
 */
function construirTablaCostesDOM() {
  var tabla = document.createElement("table");
  tabla.id = "tabla-costes-calculados";
  tabla.className = "tabla-costes";

  // Título / caption de la tabla
  var caption = document.createElement("caption");
  caption.appendChild(
    document.createTextNode(
      "Tabla de posibles costes de un folleto en función del número de páginas y fotos, tipo de impresión y resolución de las fotos"
    )
  );
  tabla.appendChild(caption);

  // Cabecera (thead) con dos filas de encabezados
  var thead = document.createElement("thead");

  var filaCabecera1 = document.createElement("tr");

  var thPaginas = crearCeldaTabla("th", "Número de páginas", "col");
  thPaginas.setAttribute("rowspan", "2");
  filaCabecera1.appendChild(thPaginas);

  var thFotos = crearCeldaTabla("th", "Número de fotos", "col");
  thFotos.setAttribute("rowspan", "2");
  filaCabecera1.appendChild(thFotos);

  var thBlancoNegro = crearCeldaTabla("th", "Blanco y negro", "colgroup");
  thBlancoNegro.setAttribute("colspan", "2");
  filaCabecera1.appendChild(thBlancoNegro);

  var thColor = crearCeldaTabla("th", "Color", "colgroup");
  thColor.setAttribute("colspan", "2");
  filaCabecera1.appendChild(thColor);

  thead.appendChild(filaCabecera1);

  var filaCabecera2 = document.createElement("tr");
  filaCabecera2.appendChild(crearCeldaTabla("th", "150-300 dpi", "col"));
  filaCabecera2.appendChild(crearCeldaTabla("th", "450-900 dpi", "col"));
  filaCabecera2.appendChild(crearCeldaTabla("th", "150-300 dpi", "col"));
  filaCabecera2.appendChild(crearCeldaTabla("th", "450-900 dpi", "col"));

  thead.appendChild(filaCabecera2);
  tabla.appendChild(thead);

  // Cuerpo de la tabla (tbody): de 1 a 15 páginas (3 fotos por página)
  var tbody = document.createElement("tbody");

  for (var paginas = 1; paginas <= 15; paginas++) {
    var fotos = paginas * 3;
    var fila = document.createElement("tr");

    // Columnas de número de páginas y número de fotos
    fila.appendChild(crearCeldaTabla("td", String(paginas)));
    fila.appendChild(crearCeldaTabla("td", String(fotos)));

    // 1. Blanco y negro, 150-300 dpi
    var costeBN150 = calcularCosteFolleto(paginas, fotos, false, false);
    fila.appendChild(crearCeldaTabla("td", formatearEuros(costeBN150)));

    // 2. Blanco y negro, 450-900 dpi
    var costeBN450 = calcularCosteFolleto(paginas, fotos, false, true);
    fila.appendChild(crearCeldaTabla("td", formatearEuros(costeBN450)));

    // 3. Color, 150-300 dpi
    var costeColor150 = calcularCosteFolleto(paginas, fotos, true, false);
    fila.appendChild(crearCeldaTabla("td", formatearEuros(costeColor150)));

    // 4. Color, 450-900 dpi
    var costeColor450 = calcularCosteFolleto(paginas, fotos, true, true);
    fila.appendChild(crearCeldaTabla("td", formatearEuros(costeColor450)));

    tbody.appendChild(fila);
  }

  tabla.appendChild(tbody);
  return tabla;
}

/**
 * Alterna la visualización de la tabla de posibles costes al pulsar el botón.
 */
function alternarTablaCostes() {
  var boton = $id("btn-mostrar-costes");
  var contenedor = $id("contenedor-tabla-costes");
  if (!boton || !contenedor) {
    return;
  }

  var tablaExistente = $id("tabla-costes-calculados");

  if (!tablaExistente) {
    var nuevaTabla = construirTablaCostesDOM();
    contenedor.appendChild(nuevaTabla);
    contenedor.hidden = false;
    boton.textContent = "Ocultar tabla de posibles costes";
    boton.setAttribute("aria-expanded", "true");
  } else if (contenedor.hidden) {
    contenedor.hidden = false;
    boton.textContent = "Ocultar tabla de posibles costes";
    boton.setAttribute("aria-expanded", "true");
  } else {
    contenedor.hidden = true;
    boton.textContent = "Mostrar tabla de posibles costes";
    boton.setAttribute("aria-expanded", "false");
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var botonCostes = $id("btn-mostrar-costes");
  if (botonCostes) {
    botonCostes.addEventListener("click", alternarTablaCostes);
  }

  // Actualizar salida del control range de resolución desde JavaScript externo
  var inputResolucion = $id("resolucion");
  var salidaResolucion = $id("salida_resolucion");
  if (inputResolucion && salidaResolucion) {
    inputResolucion.addEventListener("input", function () {
      salidaResolucion.value = inputResolucion.value + " DPI";
    });
  }
});

