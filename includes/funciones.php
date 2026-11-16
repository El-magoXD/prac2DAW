<?php
/**
 * Práctica 7 y 8 - Funciones auxiliares comunes: seguridad XSS, validación, tarifas,
 * sesiones, cookies HttpOnly, autenticación persistente ("Recordarme"), Flashdata,
 * saludo por franjas horarias y panel de últimos anuncios visitados.
 */

// Configuración de visualización de errores en entorno de desarrollo (apartado 4.10.1)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Zona horaria para que las franjas horarias y fechas de última visita coincidan con la hora local
date_default_timezone_set('Europe/Madrid');

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
 * Envía una cookie con el flag HttpOnly activado para impedir su acceso desde JavaScript.
 *
 * @param string $nombre Nombre de la cookie.
 * @param string $valor  Valor de la cookie.
 * @param int    $expira Marca de tiempo Unix de caducidad.
 * @return void
 */
function enviarCookieSegura($nombre, $valor, $expira)
{
    setcookie($nombre, $valor, [
        'expires'  => (int) $expira,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    $_COOKIE[$nombre] = $valor;
}

/**
 * Borra una cookie asignándole una fecha de caducidad en el pasado (anterior a la actual).
 *
 * @param string $nombre Nombre de la cookie a eliminar.
 * @return void
 */
function borrarCookieSegura($nombre)
{
    if (isset($_COOKIE[$nombre])) {
        setcookie($nombre, '', [
            'expires'  => time() - 42000,
            'path'     => '/',
            'domain'   => '',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        unset($_COOKIE[$nombre]);
    }
}

/**
 * Elimina todas las cookies asociadas a la opción "Recordarme en este equipo".
 *
 * @return void
 */
function borrarCookiesRecordar()
{
    borrarCookieSegura('recordar_token');
    borrarCookieSegura('recordar_ultima_visita');
    borrarCookieSegura('recordar_expira');
}

/**
 * Inicia la sesión PHP si aún no está activa, configurando la cookie de sesión (PHPSESSID)
 * con HttpOnly y SameSite=Lax para proteger el identificador de sesión frente a XSS y CSRF.
 *
 * @return void
 */
function asegurarSesionIniciada()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

/**
 * Inicia la sesión PHP (si aún no está activa) y comprueba si existe la cookie de
 * "Recordarme en este equipo" para autenticar automáticamente al usuario sin pedir credenciales.
 * Cumple la nota 10: no extiende los 90 días de caducidad en sucesivos accesos y actualiza
 * la fecha/hora de la última visita solo cuando se recupera la sesión desde la cookie.
 *
 * @return void
 */
function iniciarSesionYRecordar()
{
    asegurarSesionIniciada();

    // Si el usuario ya tiene sesión activa en el servidor, no es necesario recuperarla de la cookie
    if (!empty($_SESSION['usuario'])) {
        return;
    }

    // Si no hay sesión activa pero existe la cookie de recordar usuario, restaurar sesión
    if (!empty($_COOKIE['recordar_token'])) {
        $rutaFicheroUsuarios = __DIR__ . '/datos_usuarios.php';
        if (file_exists($rutaFicheroUsuarios) && is_readable($rutaFicheroUsuarios)) {
            require $rutaFicheroUsuarios;
            $tokenCookie = (string) $_COOKIE['recordar_token'];

            foreach ($usuariosPermitidos as $nombreUsuario => $datosUsuario) {
                if (isset($datosUsuario['token']) && hash_equals($datosUsuario['token'], $tokenCookie)) {
                    $ahoraTexto = date('d/m/Y \a \l\a\s H:i');
                    $ultimaVisitaAnterior = $_COOKIE['recordar_ultima_visita'] ?? $ahoraTexto;

                    // Mantener la fecha de expiración original de 90 días sin extenderla (sin sliding expiration)
                    $expiraOriginal = isset($_COOKIE['recordar_expira']) && is_numeric($_COOKIE['recordar_expira'])
                        ? (int) $_COOKIE['recordar_expira']
                        : (time() + 90 * 24 * 60 * 60);

                    if ($expiraOriginal <= time()) {
                        borrarCookiesRecordar();
                        return;
                    }

                    $_SESSION['usuario'] = $nombreUsuario;
                    $_SESSION['estilo'] = $datosUsuario['estilo'] ?? 'style.css';
                    $_SESSION['titulo_estilo'] = $datosUsuario['titulo_estilo'] ?? 'Estilo principal';
                    $_SESSION['recordado'] = true;
                    $_SESSION['ultima_visita'] = $ultimaVisitaAnterior;

                    // Guardar la fecha y hora actuales para la próxima vez que se recupere la sesión desde la cookie
                    enviarCookieSegura('recordar_ultima_visita', $ahoraTexto, $expiraOriginal);
                    return;
                }
            }
        }

        // Si el token de la cookie no corresponde a ningún usuario válido, limpiar cookies
        borrarCookiesRecordar();
    }
}

/**
 * Indica si existe un usuario autenticado actualmente en la sesión.
 *
 * @return bool
 */
function usuarioAutenticado()
{
    iniciarSesionYRecordar();
    return !empty($_SESSION['usuario']);
}

/**
 * Devuelve el nombre del usuario autenticado actualmente o cadena vacía si no ha iniciado sesión.
 *
 * @return string
 */
function obtenerUsuarioActual()
{
    iniciarSesionYRecordar();
    return $_SESSION['usuario'] ?? '';
}

/**
 * Protege una página privada o pública restringida: si el usuario no está autenticado,
 * almacena un mensaje en Flashdata y redirige inmediatamente al formulario de autenticación.
 *
 * @return void
 */
function exigirAutenticacion()
{
    iniciarSesionYRecordar();
    if (!usuarioAutenticado()) {
        establecerFlash('error_acceso', 'Para acceder a la página solicitada debes iniciar sesión como usuario registrado.');
        redirigir('aviso.php');
    }
}

/**
 * Calcula el saludo personalizado según la franja horaria del servidor (Práctica 8, pág. 3):
 * - De 06:00 a 11:59: Buenos días, Pepito.
 * - De 12:00 a 15:59: Hola, Pepito.
 * - De 16:00 a 19:59: Buenas tardes, Pepito.
 * - De 20:00 hasta 05:59: Buenas noches, Pepito.
 *
 * @param string $nombreUsuario Nombre del usuario.
 * @return string Saludo formateado.
 */
function obtenerSaludoHorario($nombreUsuario)
{
    $hora = (int) date('G');

    if ($hora >= 6 && $hora <= 11) {
        return "Buenos días, $nombreUsuario.";
    }
    if ($hora >= 12 && $hora <= 15) {
        return "Hola, $nombreUsuario.";
    }
    if ($hora >= 16 && $hora <= 19) {
        return "Buenas tardes, $nombreUsuario.";
    }

    return "Buenas noches, $nombreUsuario.";
}

/**
 * Almacena un valor en la sesión bajo el patrón Flashdata (disponible solo para la siguiente petición).
 *
 * @param string $clave Identificador del dato flash.
 * @param mixed  $valor Valor a conservar temporalmente.
 * @return void
 */
function establecerFlash($clave, $valor)
{
    asegurarSesionIniciada();

    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    $_SESSION['flash'][$clave] = $valor;
}

/**
 * Recupera un valor de Flashdata y lo elimina inmediatamente de la sesión para que sea de un solo uso.
 *
 * @param string $clave   Identificador del dato flash.
 * @param mixed  $defecto Valor por defecto si no existe la clave.
 * @return mixed
 */
function obtenerFlash($clave, $defecto = null)
{
    asegurarSesionIniciada();

    if (isset($_SESSION['flash']) && is_array($_SESSION['flash']) && array_key_exists($clave, $_SESSION['flash'])) {
        $valor = $_SESSION['flash'][$clave];
        unset($_SESSION['flash'][$clave]);
        return $valor;
    }

    return $defecto;
}

/**
 * Obtiene los datos de un anuncio a partir de su identificador numérico.
 * Si el ID es del 1 al 5 devuelve su ficha específica; para cualquier otro ID emplea la regla par/impar.
 *
 * @param int $id Identificador del anuncio.
 * @return array|null Datos del anuncio.
 */
function obtenerDatosAnuncioPorId($id)
{
    $rutaDatosAnuncios = __DIR__ . '/datos_anuncios.php';
    if (!file_exists($rutaDatosAnuncios) || !is_readable($rutaDatosAnuncios)) {
        return null;
    }

    require $rutaDatosAnuncios;
    $idEntero = (int) $id;

    if (isset($anunciosPorId[$idEntero])) {
        return $anunciosPorId[$idEntero];
    }

    $clave = ($idEntero % 2 === 0) ? 'par' : 'impar';
    if (isset($anunciosDetalle[$clave])) {
        $anuncio = $anunciosDetalle[$clave];
        $anuncio['id'] = $idEntero;
        return $anuncio;
    }

    return null;
}

/**
 * Registra la visita a un anuncio en la cookie 'ultimos_anuncios' (duración de 1 semana).
 * Mantiene como máximo los 4 últimos anuncios en orden de visita sin duplicados:
 * - Si ya estaba en la lista, cambia su posición al final para respetar el orden de visita.
 * - Si hay más de 4 anuncios, elimina el primero que se visitó.
 *
 * @param int $idAnuncio Identificador del anuncio visitado.
 * @return void
 */
function registrarVisitaAnuncio($idAnuncio)
{
    $idEntero = (int) $idAnuncio;
    $listaActual = [];

    if (!empty($_COOKIE['ultimos_anuncios'])) {
        $decodificado = json_decode((string) $_COOKIE['ultimos_anuncios'], true);
        if (is_array($decodificado)) {
            foreach ($decodificado as $item) {
                if (is_numeric($item)) {
                    $listaActual[] = (int) $item;
                }
            }
        }
    }

    // Si el anuncio ya está incluido, quitarlo de su posición anterior para moverlo al final
    $listaSinActual = [];
    foreach ($listaActual as $idGuardado) {
        if ($idGuardado !== $idEntero) {
            $listaSinActual[] = $idGuardado;
        }
    }

    $listaSinActual[] = $idEntero;

    // Si supera los 4 anuncios, eliminar el primero que se haya visitado
    while (count($listaSinActual) > 4) {
        array_shift($listaSinActual);
    }

    $expiraUnaSemana = time() + (7 * 24 * 60 * 60);
    enviarCookieSegura('ultimos_anuncios', json_encode($listaSinActual), $expiraUnaSemana);
}

/**
 * Devuelve la lista de hasta 4 anuncios visitados almacenados en la cookie, en el orden en que se visitaron.
 *
 * @return array Lista de anuncios visitados.
 */
function obtenerUltimosAnunciosVisitados()
{
    if (empty($_COOKIE['ultimos_anuncios'])) {
        return [];
    }

    $ids = json_decode((string) $_COOKIE['ultimos_anuncios'], true);
    if (!is_array($ids)) {
        return [];
    }

    $anunciosVisitados = [];
    foreach ($ids as $id) {
        if (is_numeric($id)) {
            $datos = obtenerDatosAnuncioPorId((int) $id);
            if ($datos !== null) {
                $anunciosVisitados[] = $datos;
            }
        }
    }

    return $anunciosVisitados;
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
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
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
