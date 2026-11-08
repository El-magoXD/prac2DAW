<?php
/**
 * Práctica 7 - Funciones auxiliares comunes (seguridad, validación, tarifas y redirecciones).
 */

// Configuración de visualización de errores en entorno de desarrollo (apartado 4.10.1)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Escapa caracteres especiales en HTML para prevenir ataques XSS (Cross-Site Scripting).
 *
 * @param mixed $value Valor a escapar.
 * @return string Cadena segura para mostrar en HTML.
 */
function e($value)
{
    if ($value === null) {
        return '';
    }

    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Comprueba que una cadena no esté vacía ni formada únicamente por espacios en blanco o tabuladores.
 *
 * @param mixed $texto Cadena a comprobar.
 * @return bool true si contiene al menos un carácter distinto de espacio, tabulador o salto de línea.
 */
function tieneContenidoSinEspacios($texto)
{
    if (!is_string($texto) || $texto === '') {
        return false;
    }

    $longitud = strlen($texto);
    for ($i = 0; $i < $longitud; $i++) {
        $c = $texto[$i];
        if ($c !== ' ' && $c !== "\t" && $c !== "\n" && $c !== "\r") {
            return true;
        }
    }

    return false;
}

/**
 * Calcula el coste unitario de un folleto publicitario según las tarifas por tramos:
 * - Coste fijo de procesamiento y envío: 10 €
 * - Primeras 4 páginas (< 5 páginas): 2,00 € por página
 * - Entre 5 y 10 páginas (siguientes 6 páginas): 1,80 € por página
 * - Más de 10 páginas (a partir de la página 11): 1,60 € por página
 * - Color: 0 € en blanco y negro; 0,50 € por foto en color
 * - Resolución: 0 € para <= 300 dpi; 0,20 € por foto para > 300 dpi
 *
 * @param int  $numPaginas       Número de páginas del folleto.
 * @param int  $numFotos         Número de fotos del folleto.
 * @param bool $esColor          true si la impresión es en color, false si es blanco y negro.
 * @param bool $esAltaResolucion true si la resolución es > 300 dpi (450-900 dpi).
 * @return float Coste unitario en euros.
 */
function calcularCosteFolleto($numPaginas, $numFotos, $esColor, $esAltaResolucion)
{
    $costeFijoEnvio = 10.0;

    $paginasTramo1 = min($numPaginas, 4);
    $paginasTramo2 = max(0, min($numPaginas - 4, 6));
    $paginasTramo3 = max(0, $numPaginas - 10);

    $costePaginas = ($paginasTramo1 * 2.0) + ($paginasTramo2 * 1.8) + ($paginasTramo3 * 1.6);
    $costeColor = $esColor ? ($numFotos * 0.5) : 0.0;
    $costeResolucion = $esAltaResolucion ? ($numFotos * 0.2) : 0.0;

    return $costeFijoEnvio + $costePaginas + $costeColor + $costeResolucion;
}

/**
 * Formatea un importe numérico en euros con dos decimales y coma decimal (ej. "12,00 €").
 *
 * @param float $importe Importe numérico.
 * @return string Importe formateado.
 */
function formatearEuros($importe)
{
    return number_format((float) $importe, 2, ',', '.') . ' €';
}

/**
 * Realiza una redirección HTTP 1.1 con URL absoluta a otra página del directorio actual y finaliza la ejecución.
 *
 * @param string $extra Ruta relativa del fichero destino (con su cadena de consulta opcional).
 * @return void
 */
function redirigir($extra)
{
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\');
    header("Location: http://$host$uri/$extra");
    exit;
}

/**
 * Devuelve el nombre legible de un país a partir de su código de formulario.
 *
 * @param string $codigo Código del país.
 * @return string Nombre legible del país.
 */
function obtenerNombrePais($codigo)
{
    $paises = [
        'espana'         => 'España',
        'francia'        => 'Francia',
        'italia'         => 'Italia',
        'alemania'       => 'Alemania',
        'portugal'       => 'Portugal',
        'paises-bajos'   => 'Países Bajos',
        'belgica'        => 'Bélgica',
        'irlanda'        => 'Irlanda',
        'reino-unido'    => 'Reino Unido',
        'estados-unidos' => 'Estados Unidos',
    ];

    return $paises[$codigo] ?? $codigo;
}
