<?php
/**
 * Práctica 7, 8 y 9 - Funciones auxiliares comunes: seguridad XSS, validación, tarifas,
 * sesiones, cookies HttpOnly, autenticación persistente ("Recordarme"), Flashdata,
 * saludo por franjas horarias, panel de últimos anuncios visitados y acceso a BD MySQL/MariaDB con mysqli.
 */

// Configuración de visualización de errores en entorno de desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Zona horaria para que las franjas horarias y fechas de última visita coincidan con la hora local
date_default_timezone_set('Europe/Madrid');

define('PI_REMEMBER_SECRET', 'PI_DAW_REMEMBER_KEY_2026');

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
 * Lee los parámetros de conexión a la base de datos desde includes/config.ini.
 *
 * @return array{Server:string, User:string, Password:string, Database:string}
 */
function leerConfiguracionBD()
{
    $rutaIni = __DIR__ . '/config.ini';
    $defecto = [
        'Server'   => 'localhost',
        'User'     => 'wwwdata',
        'Password' => 'abc',
        'Database' => 'pibd',
    ];

    if (!file_exists($rutaIni) || !is_readable($rutaIni)) {
        return $defecto;
    }

    $config = parse_ini_file($rutaIni, true);
    if (!is_array($config) || !isset($config['DB']) || !is_array($config['DB'])) {
        return $defecto;
    }

    return [
        'Server'   => (string) ($config['DB']['Server'] ?? $defecto['Server']),
        'User'     => (string) ($config['DB']['User'] ?? $defecto['User']),
        'Password' => (string) ($config['DB']['Password'] ?? $defecto['Password']),
        'Database' => (string) ($config['DB']['Database'] ?? $defecto['Database']),
    ];
}

/**
 * Establece una conexión orientada a objetos con la base de datos MySQL/MariaDB usando mysqli
 * y fija el juego de caracteres utf8mb4 (Práctica 9, págs. 20-26).
 *
 * @return mysqli|null Instancia mysqli conectada o null si falla la conexión.
 */
function obtenerConexionBD()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $cfg = leerConfiguracionBD();

    try {
        $mysqli = new mysqli($cfg['Server'], $cfg['User'], $cfg['Password'], $cfg['Database']);
        $mysqli->set_charset('utf8mb4');
        return $mysqli;
    } catch (mysqli_sql_exception $e) {
        error_log('Error de conexión a la base de datos: ' . $e->getMessage());
        return null;
    }
}

/**
 * Ejecuta una consulta SELECT mediante sentencia preparada (prepared statement) y devuelve todas las filas.
 *
 * @param string $sql    Consulta SQL con marcadores '?'.
 * @param string $tipos  Cadena de tipos para bind_param ('i', 'd', 's', 'b').
 * @param array  $params Lista de parámetros a vincular.
 * @return array Lista de filas asociativas (vacía si no hay resultados o ocurre un error).
 */
function ejecutarConsultaSelect($sql, $tipos = '', $params = [])
{
    $mysqli = obtenerConexionBD();
    if (!$mysqli instanceof mysqli) {
        return [];
    }

    $stmt = null;
    $resultado = null;

    try {
        $stmt = $mysqli->prepare($sql);
        if ($tipos !== '' && !empty($params)) {
            $stmt->bind_param($tipos, ...$params);
        }
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado instanceof mysqli_result) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    } catch (mysqli_sql_exception $e) {
        error_log('Error en consulta SELECT preparada: ' . $e->getMessage());
        return [];
    } finally {
        if ($resultado instanceof mysqli_result) {
            $resultado->close();
        }
        if ($stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
        $mysqli->close();
    }
}

/**
 * Ejecuta una consulta SELECT mediante sentencia preparada y devuelve la primera fila o null.
 *
 * @param string $sql    Consulta SQL con marcadores '?'.
 * @param string $tipos  Cadena de tipos para bind_param.
 * @param array  $params Parámetros a vincular.
 * @return array|null Fila asociativa o null si no existe.
 */
function ejecutarConsultaSelectUno($sql, $tipos = '', $params = [])
{
    $filas = ejecutarConsultaSelect($sql, $tipos, $params);
    return $filas[0] ?? null;
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
 * Genera el token seguro asociado a un usuario en el servidor para la cookie "Recordarme en este equipo".
 *
 * @param int    $idUsuario  ID del usuario en la base de datos.
 * @param string $nomUsuario Nombre del usuario.
 * @param string $claveHash  Hash de la contraseña del usuario.
 * @return string Token HMAC-SHA256.
 */
function generarTokenRecordar($idUsuario, $nomUsuario, $claveHash)
{
    return hash_hmac('sha256', $idUsuario . '|' . $nomUsuario . '|' . $claveHash, PI_REMEMBER_SECRET);
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
 * "Recordarme en este equipo" para autenticar automáticamente al usuario desde la BD Usuarios.
 *
 * @return void
 */
function iniciarSesionYRecordar()
{
    asegurarSesionIniciada();

    // Si el usuario ya tiene sesión activa en el servidor, no es necesario recuperarla de la cookie
    if (!empty($_SESSION['usuario']) && !empty($_SESSION['id_usuario'])) {
        return;
    }

    // Si no hay sesión activa pero existe la cookie de recordar usuario, restaurar sesión desde BD
    if (!empty($_COOKIE['recordar_token'])) {
        $tokenCookie = (string) $_COOKIE['recordar_token'];

        $usuarios = ejecutarConsultaSelect(
            "SELECT u.IdUsuario, u.NomUsuario, u.Clave,
                    COALESCE(e.Fichero, 'style.css') AS EstiloFichero,
                    COALESCE(e.Nombre, 'Estilo principal') AS EstiloNombre
             FROM Usuarios u
             LEFT JOIN Estilos e ON u.Estilo = e.IdEstilo"
        );

        foreach ($usuarios as $datosUsuario) {
            $tokenEsperado = generarTokenRecordar(
                (int) $datosUsuario['IdUsuario'],
                (string) $datosUsuario['NomUsuario'],
                (string) $datosUsuario['Clave']
            );

            if (hash_equals($tokenEsperado, $tokenCookie)) {
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

                $_SESSION['id_usuario'] = (int) $datosUsuario['IdUsuario'];
                $_SESSION['usuario'] = (string) $datosUsuario['NomUsuario'];
                $_SESSION['estilo'] = (string) $datosUsuario['EstiloFichero'];
                $_SESSION['titulo_estilo'] = (string) $datosUsuario['EstiloNombre'];
                $_SESSION['recordado'] = true;
                $_SESSION['ultima_visita'] = $ultimaVisitaAnterior;

                // Guardar la fecha y hora actuales para la próxima vez que se recupere la sesión desde la cookie
                enviarCookieSegura('recordar_ultima_visita', $ahoraTexto, $expiraOriginal);
                return;
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
    return !empty($_SESSION['usuario']) && !empty($_SESSION['id_usuario']);
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
 * Devuelve el ID numérico del usuario autenticado actualmente o 0 si no ha iniciado sesión.
 *
 * @return int
 */
function obtenerIdUsuarioActual()
{
    iniciarSesionYRecordar();
    return isset($_SESSION['id_usuario']) ? (int) $_SESSION['id_usuario'] : 0;
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
 * Calcula el saludo personalizado según la franja horaria del servidor (Práctica 8, pág. 3).
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

// ============================================================================
// Funciones de consulta a la base de datos pibd (Práctica 9)
// ============================================================================

/**
 * Formatea una fecha SQL (YYYY-MM-DD o YYYY-MM-DD HH:MM:SS) en formato español DD/MM/AAAA.
 *
 * @param string|null $fechaSql    Fecha procedente de MySQL/MariaDB.
 * @param bool        $incluirHora true para añadir " a las HH:MM".
 * @return string
 */
function formatearFechaBD($fechaSql, $incluirHora = false)
{
    if (!is_string($fechaSql) || trim($fechaSql) === '') {
        return '';
    }

    $marca = strtotime($fechaSql);
    if ($marca === false) {
        return $fechaSql;
    }

    return $incluirHora ? date('d/m/Y \a \l\a\s H:i', $marca) : date('d/m/Y', $marca);
}

/**
 * Formatea el precio de un anuncio en euros (añadiendo " / mes" si es alquiler).
 *
 * @param float|string $precio            Importe numérico.
 * @param string       $nombreTipoAnuncio Nombre del tipo de anuncio ("Venta", "Alquiler").
 * @return string
 */
function formatearPrecioAnuncio($precio, $nombreTipoAnuncio = '')
{
    $numero = (float) $precio;
    $decimales = (abs($numero - round($numero)) > 0.001) ? 2 : 0;
    $texto = number_format($numero, $decimales, ',', '.') . ' €';

    if (mb_strtolower(trim($nombreTipoAnuncio), 'UTF-8') === 'alquiler') {
        $texto .= ' / mes';
    }

    return $texto;
}

/**
 * Obtiene todos los países de la tabla Paises ordenados alfabéticamente.
 *
 * @return array
 */
function obtenerPaises()
{
    return ejecutarConsultaSelect('SELECT IdPais, NomPais FROM Paises ORDER BY NomPais ASC');
}

/**
 * Obtiene el nombre de un país por su ID numérico (o por su código heredado si no es numérico).
 *
 * @param int|string $idOCodigo IdPais o código antiguo.
 * @return string
 */
function obtenerNombrePais($idOCodigo)
{
    if (is_numeric($idOCodigo) && (int) $idOCodigo > 0) {
        $fila = ejecutarConsultaSelectUno('SELECT NomPais FROM Paises WHERE IdPais = ?', 'i', [(int) $idOCodigo]);
        if ($fila !== null) {
            return (string) $fila['NomPais'];
        }
    }

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

    return $paises[(string) $idOCodigo] ?? (string) $idOCodigo;
}

/**
 * Obtiene todos los tipos de anuncio de la tabla TiposAnuncios.
 *
 * @return array
 */
function obtenerTiposAnuncios()
{
    return ejecutarConsultaSelect('SELECT IdTAnuncio, NomTAnuncio FROM TiposAnuncios ORDER BY IdTAnuncio ASC');
}

/**
 * Obtiene todos los tipos de vivienda de la tabla TiposViviendas.
 *
 * @return array
 */
function obtenerTiposViviendas()
{
    return ejecutarConsultaSelect('SELECT IdTVivienda, NomTVivienda FROM TiposViviendas ORDER BY IdTVivienda ASC');
}

/**
 * Obtiene todos los tipos de mensajes de la tabla TiposMensajes.
 *
 * @return array
 */
function obtenerTiposMensajes()
{
    return ejecutarConsultaSelect('SELECT IdTMensaje, NomTMensaje FROM TiposMensajes ORDER BY IdTMensaje ASC');
}

/**
 * Obtiene todos los estilos disponibles en la tabla Estilos.
 *
 * @return array
 */
function obtenerEstilos()
{
    return ejecutarConsultaSelect('SELECT IdEstilo, Nombre, Descripcion, Fichero FROM Estilos ORDER BY IdEstilo ASC');
}

/**
 * Obtiene los datos completos de un usuario por su IdUsuario junto con su país y estilo.
 *
 * @param int $idUsuario Identificador del usuario.
 * @return array|null
 */
function obtenerUsuarioPorId($idUsuario)
{
    if (!is_numeric($idUsuario) || (int) $idUsuario <= 0) {
        return null;
    }

    return ejecutarConsultaSelectUno(
        "SELECT u.IdUsuario, u.NomUsuario, u.Clave, u.Email, u.Sexo, u.FNacimiento,
                u.Ciudad, u.Pais, u.Foto, u.FRegistro, u.Estilo,
                p.NomPais, e.Nombre AS NombreEstilo, e.Fichero AS FicheroEstilo
         FROM Usuarios u
         LEFT JOIN Paises p ON u.Pais = p.IdPais
         LEFT JOIN Estilos e ON u.Estilo = e.IdEstilo
         WHERE u.IdUsuario = ?",
        'i',
        [(int) $idUsuario]
    );
}

/**
 * Obtiene los datos de un usuario por su nombre exacto de usuario (NomUsuario) usando BINARY
 * para distinguir mayúsculas/minúsculas si fuera necesario, o consulta estándar.
 *
 * @param string $nomUsuario Nombre de usuario.
 * @return array|null
 */
function obtenerUsuarioPorNombre($nomUsuario)
{
    return ejecutarConsultaSelectUno(
        "SELECT u.IdUsuario, u.NomUsuario, u.Clave, u.Email, u.Sexo, u.FNacimiento,
                u.Ciudad, u.Pais, u.Foto, u.FRegistro, u.Estilo,
                COALESCE(e.Fichero, 'style.css') AS EstiloFichero,
                COALESCE(e.Nombre, 'Estilo principal') AS EstiloNombre
         FROM Usuarios u
         LEFT JOIN Estilos e ON u.Estilo = e.IdEstilo
         WHERE BINARY u.NomUsuario = ?",
        's',
        [(string) $nomUsuario]
    );
}

/**
 * Obtiene los últimos 5 anuncios publicados ordenados por su fecha de registro (FRegistro DESC).
 *
 * @return array
 */
function obtenerUltimosCincoAnuncios()
{
    $filas = ejecutarConsultaSelect(
        "SELECT a.IdAnuncio, a.Titulo, a.FPrincipal, a.Alternativo, a.Precio, a.Ciudad, a.FRegistro,
                ta.NomTAnuncio, tv.NomTVivienda, p.NomPais
         FROM Anuncios a
         INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
         INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
         INNER JOIN Paises p ON a.Pais = p.IdPais
         ORDER BY a.FRegistro DESC, a.IdAnuncio DESC
         LIMIT 5"
    );

    $anuncios = [];
    foreach ($filas as $fila) {
        $anuncios[] = [
            'id'           => (int) $fila['IdAnuncio'],
            'imagen'       => (string) $fila['FPrincipal'],
            'alt'          => (string) $fila['Alternativo'],
            'titulo'       => (string) $fila['Titulo'],
            'fecha'        => formatearFechaBD($fila['FRegistro']),
            'ciudad'       => (string) $fila['Ciudad'],
            'pais'         => (string) $fila['NomPais'],
            'precio'       => formatearPrecioAnuncio($fila['Precio'], $fila['NomTAnuncio']),
            'tipo_anuncio' => (string) $fila['NomTAnuncio'],
            'tipo_vivienda'=> (string) $fila['NomTVivienda'],
        ];
    }

    return $anuncios;
}

/**
 * Obtiene todas las fotos secundarias de un anuncio desde la tabla Fotos.
 *
 * @param int $idAnuncio Identificador del anuncio.
 * @return array
 */
function obtenerFotosSecundariasAnuncio($idAnuncio)
{
    $filas = ejecutarConsultaSelect(
        'SELECT IdFoto, Titulo, Foto, Alternativo FROM Fotos WHERE Anuncio = ? ORDER BY IdFoto ASC',
        'i',
        [(int) $idAnuncio]
    );

    $fotos = [];
    foreach ($filas as $fila) {
        $fotos[] = [
            'id'     => (int) $fila['IdFoto'],
            'src'    => (string) $fila['Foto'],
            'alt'    => (string) $fila['Alternativo'],
            'titulo' => (string) $fila['Titulo'],
        ];
    }

    return $fotos;
}

/**
 * Obtiene los datos completos de un anuncio a partir de su identificador numérico desde la BD.
 *
 * @param int $id Identificador del anuncio.
 * @return array|null Datos completos del anuncio o null si no existe.
 */
function obtenerDatosAnuncioPorId($id)
{
    $idEntero = (int) $id;
    if ($idEntero <= 0) {
        return null;
    }

    $fila = ejecutarConsultaSelectUno(
        "SELECT a.*, ta.NomTAnuncio, tv.NomTVivienda, p.NomPais, u.NomUsuario
         FROM Anuncios a
         INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
         INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
         INNER JOIN Paises p ON a.Pais = p.IdPais
         INNER JOIN Usuarios u ON a.Usuario = u.IdUsuario
         WHERE a.IdAnuncio = ?",
        'i',
        [$idEntero]
    );

    if ($fila === null) {
        return null;
    }

    $plantaNum = (int) $fila['Planta'];
    if ($plantaNum === 0) {
        $plantaTexto = 'Bajo';
    } elseif ($plantaNum < 0) {
        $plantaTexto = 'Sótano ' . abs($plantaNum);
    } else {
        $plantaTexto = $plantaNum . 'ª planta';
    }

    return [
        'id'                 => (int) $fila['IdAnuncio'],
        'tipo_anuncio'       => (string) $fila['NomTAnuncio'],
        'id_tipo_anuncio'    => (int) $fila['TAnuncio'],
        'tipo_vivienda'      => (string) $fila['NomTVivienda'],
        'id_tipo_vivienda'   => (int) $fila['TVivienda'],
        'foto_principal'     => (string) $fila['FPrincipal'],
        'alt_foto_principal' => (string) $fila['Alternativo'],
        'titulo'             => (string) $fila['Titulo'],
        'precio'             => formatearPrecioAnuncio($fila['Precio'], $fila['NomTAnuncio']),
        'precio_numero'      => (float) $fila['Precio'],
        'texto'              => (string) $fila['Texto'],
        'fecha'              => formatearFechaBD($fila['FRegistro']),
        'ciudad'             => (string) $fila['Ciudad'],
        'pais'               => (string) $fila['NomPais'],
        'id_pais'            => (int) $fila['Pais'],
        'caracteristicas'    => [
            'superficie'        => rtrim(rtrim(number_format((float) $fila['Superficie'], 2, ',', '.'), '0'), ',') . ' m²',
            'habitaciones'      => (int) $fila['NHabitaciones'],
            'banos'             => (int) $fila['NBanyos'],
            'planta'            => $plantaTexto,
            'anio_construccion' => (int) $fila['Anyo'],
        ],
        'miniaturas'         => obtenerFotosSecundariasAnuncio($idEntero),
        'usuario'            => (string) $fila['NomUsuario'],
        'id_usuario'         => (int) $fila['Usuario'],
    ];
}

/**
 * Obtiene la información básica de un anuncio y todas sus fotos (foto principal + tabla Fotos).
 *
 * @param int $idAnuncio Identificador del anuncio.
 * @return array|null
 */
function obtenerGaleriaAnuncio($idAnuncio)
{
    $anuncio = obtenerDatosAnuncioPorId($idAnuncio);
    if ($anuncio === null) {
        return null;
    }

    $todasLasFotos = [
        [
            'titulo'    => $anuncio['titulo'] . ' (Foto principal)',
            'src'       => $anuncio['foto_principal'],
            'alt'       => $anuncio['alt_foto_principal'],
            'principal' => true,
        ],
    ];

    foreach ($anuncio['miniaturas'] as $foto) {
        $todasLasFotos[] = [
            'titulo'    => $foto['titulo'],
            'src'       => $foto['src'],
            'alt'       => $foto['alt'],
            'principal' => false,
        ];
    }

    return [
        'anuncio' => $anuncio,
        'fotos'   => $todasLasFotos,
        'total'   => count($todasLasFotos),
    ];
}

/**
 * Obtiene todos los anuncios publicados por un usuario concreto.
 *
 * @param int $idUsuario Identificador del usuario.
 * @return array
 */
function obtenerAnunciosDeUsuario($idUsuario)
{
    $filas = ejecutarConsultaSelect(
        "SELECT a.IdAnuncio, a.Titulo, a.FPrincipal, a.Alternativo, a.Precio, a.Ciudad, a.FRegistro,
                ta.NomTAnuncio, tv.NomTVivienda, p.NomPais
         FROM Anuncios a
         INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
         INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
         INNER JOIN Paises p ON a.Pais = p.IdPais
         WHERE a.Usuario = ?
         ORDER BY a.FRegistro DESC, a.IdAnuncio DESC",
        'i',
        [(int) $idUsuario]
    );

    $anuncios = [];
    foreach ($filas as $fila) {
        $anuncios[] = [
            'id'            => (int) $fila['IdAnuncio'],
            'imagen'        => (string) $fila['FPrincipal'],
            'alt'           => (string) $fila['Alternativo'],
            'titulo'        => (string) $fila['Titulo'],
            'fecha'         => formatearFechaBD($fila['FRegistro']),
            'ciudad'        => (string) $fila['Ciudad'],
            'pais'          => (string) $fila['NomPais'],
            'precio'        => formatearPrecioAnuncio($fila['Precio'], $fila['NomTAnuncio']),
            'tipo_anuncio'  => (string) $fila['NomTAnuncio'],
            'tipo_vivienda' => (string) $fila['NomTVivienda'],
        ];
    }

    return $anuncios;
}

/**
 * Obtiene los mensajes enviados por un usuario (UsuOrigen).
 *
 * @param int $idUsuario Identificador del usuario.
 * @return array
 */
function obtenerMensajesEnviadosUsuario($idUsuario)
{
    return ejecutarConsultaSelect(
        "SELECT m.IdMensaje, m.Texto, m.FRegistro, m.Anuncio,
                tm.NomTMensaje, a.Titulo AS TituloAnuncio,
                ud.IdUsuario AS IdDestinatario, ud.NomUsuario AS NomDestinatario
         FROM Mensajes m
         INNER JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje
         INNER JOIN Anuncios a ON m.Anuncio = a.IdAnuncio
         INNER JOIN Usuarios ud ON m.UsuDestino = ud.IdUsuario
         WHERE m.UsuOrigen = ?
         ORDER BY m.FRegistro DESC, m.IdMensaje DESC",
        'i',
        [(int) $idUsuario]
    );
}

/**
 * Obtiene los mensajes recibidos por un usuario (UsuDestino).
 *
 * @param int $idUsuario Identificador del usuario.
 * @return array
 */
function obtenerMensajesRecibidosUsuario($idUsuario)
{
    return ejecutarConsultaSelect(
        "SELECT m.IdMensaje, m.Texto, m.FRegistro, m.Anuncio,
                tm.NomTMensaje, a.Titulo AS TituloAnuncio,
                uo.IdUsuario AS IdRemitente, uo.NomUsuario AS NomRemitente
         FROM Mensajes m
         INNER JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje
         INNER JOIN Anuncios a ON m.Anuncio = a.IdAnuncio
         INNER JOIN Usuarios uo ON m.UsuOrigen = uo.IdUsuario
         WHERE m.UsuDestino = ?
         ORDER BY m.FRegistro DESC, m.IdMensaje DESC",
        'i',
        [(int) $idUsuario]
    );
}

/**
 * Obtiene todos los mensajes recibidos en un anuncio concreto.
 *
 * @param int $idAnuncio Identificador del anuncio.
 * @return array
 */
function obtenerMensajesPorAnuncio($idAnuncio)
{
    return ejecutarConsultaSelect(
        "SELECT m.IdMensaje, m.Texto, m.FRegistro,
                tm.NomTMensaje,
                uo.IdUsuario AS IdRemitente, uo.NomUsuario AS NomRemitente
         FROM Mensajes m
         INNER JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje
         INNER JOIN Usuarios uo ON m.UsuOrigen = uo.IdUsuario
         WHERE m.Anuncio = ?
         ORDER BY m.FRegistro DESC, m.IdMensaje DESC",
        'i',
        [(int) $idAnuncio]
    );
}

/**
 * Procesa la cadena de búsqueda rápida (Práctica 9, págs. 7 y 8):
 * Elimina las palabras vacías permitidas ("un", "una", "en", "de") y extrae en orden:
 *   1) Tipo de vivienda (incluyendo compuesto "obra nueva")
 *   2) Tipo de anuncio
 *   3) Ciudad (el resto de términos)
 *
 * @param string $consultaTexto Texto introducido en el buscador rápido.
 * @return array{tipo_vivienda:string, tipo_anuncio:string, ciudad:string}
 */
function parsearBusquedaRapida($consultaTexto)
{
    $limpio = trim((string) preg_replace('/\s+/u', ' ', (string) $consultaTexto));
    if ($limpio === '') {
        return [
            'tipo_vivienda' => '',
            'tipo_anuncio'  => '',
            'ciudad'        => '',
        ];
    }

    $palabras = explode(' ', $limpio);
    $vacias = ['un', 'una', 'en', 'de'];
    $tokens = [];

    foreach ($palabras as $palabra) {
        $minus = mb_strtolower($palabra, 'UTF-8');
        if (!in_array($minus, $vacias, true)) {
            $tokens[] = $palabra;
        }
    }

    $tipoVivienda = '';
    $tipoAnuncio = '';
    $ciudad = '';

    if (count($tokens) >= 2 && mb_strtolower($tokens[0] . ' ' . $tokens[1], 'UTF-8') === 'obra nueva') {
        $tipoVivienda = 'Obra nueva';
        $indiceSiguiente = 2;
    } elseif (count($tokens) >= 1) {
        $tipoVivienda = $tokens[0];
        $indiceSiguiente = 1;
    } else {
        $indiceSiguiente = 0;
    }

    if (isset($tokens[$indiceSiguiente])) {
        $tipoAnuncio = $tokens[$indiceSiguiente];
        $indiceSiguiente++;
    }

    if ($indiceSiguiente < count($tokens)) {
        $ciudad = implode(' ', array_slice($tokens, $indiceSiguiente));
    }

    return [
        'tipo_vivienda' => $tipoVivienda,
        'tipo_anuncio'  => $tipoAnuncio,
        'ciudad'        => $ciudad,
    ];
}

/**
 * Valida si una fecha en formato YYYY-MM-DD es válida.
 *
 * @param string $fecha Cadena de fecha.
 * @return bool
 */
function esFechaSqlValida($fecha)
{
    if (!is_string($fecha) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        return false;
    }

    $partes = explode('-', $fecha);
    return checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0]);
}

/**
 * Ejecuta una búsqueda de anuncios mediante sentencia preparada sobre la tabla Anuncios.
 * Todas las comparaciones de texto son insensibles a mayúsculas y minúsculas.
 *
 * @param array $filtros Criterios de búsqueda (id_tipo_anuncio, id_tipo_vivienda, nombre_tipo_anuncio,
 *                       nombre_tipo_vivienda, ciudad, id_pais, precio_min, precio_max, fecha_min, fecha_max).
 * @return array Lista de anuncios encontrados.
 */
function buscarAnuncios($filtros)
{
    $condiciones = ['1 = 1'];
    $tipos = '';
    $parametros = [];

    if (!empty($filtros['id_tipo_anuncio']) && is_numeric($filtros['id_tipo_anuncio'])) {
        $condiciones[] = 'a.TAnuncio = ?';
        $tipos .= 'i';
        $parametros[] = (int) $filtros['id_tipo_anuncio'];
    }

    if (!empty($filtros['id_tipo_vivienda']) && is_numeric($filtros['id_tipo_vivienda'])) {
        $condiciones[] = 'a.TVivienda = ?';
        $tipos .= 'i';
        $parametros[] = (int) $filtros['id_tipo_vivienda'];
    }

    if (!empty($filtros['nombre_tipo_vivienda'])) {
        $condiciones[] = 'LOWER(tv.NomTVivienda) = LOWER(?)';
        $tipos .= 's';
        $parametros[] = trim((string) $filtros['nombre_tipo_vivienda']);
    }

    if (!empty($filtros['nombre_tipo_anuncio'])) {
        $condiciones[] = 'LOWER(ta.NomTAnuncio) = LOWER(?)';
        $tipos .= 's';
        $parametros[] = trim((string) $filtros['nombre_tipo_anuncio']);
    }

    if (!empty($filtros['ciudad']) && tieneContenidoSinEspacios($filtros['ciudad'])) {
        $condiciones[] = "LOWER(a.Ciudad) LIKE LOWER(CONCAT('%', ?, '%'))";
        $tipos .= 's';
        $parametros[] = trim((string) $filtros['ciudad']);
    }

    if (!empty($filtros['id_pais']) && is_numeric($filtros['id_pais'])) {
        $condiciones[] = 'a.Pais = ?';
        $tipos .= 'i';
        $parametros[] = (int) $filtros['id_pais'];
    }

    if (isset($filtros['precio_min']) && $filtros['precio_min'] !== '' && is_numeric($filtros['precio_min'])) {
        $condiciones[] = 'a.Precio >= ?';
        $tipos .= 'd';
        $parametros[] = (float) $filtros['precio_min'];
    }

    if (isset($filtros['precio_max']) && $filtros['precio_max'] !== '' && is_numeric($filtros['precio_max'])) {
        $condiciones[] = 'a.Precio <= ?';
        $tipos .= 'd';
        $parametros[] = (float) $filtros['precio_max'];
    }

    if (!empty($filtros['fecha_min']) && esFechaSqlValida($filtros['fecha_min'])) {
        $condiciones[] = 'DATE(a.FRegistro) >= ?';
        $tipos .= 's';
        $parametros[] = $filtros['fecha_min'];
    }

    if (!empty($filtros['fecha_max']) && esFechaSqlValida($filtros['fecha_max'])) {
        $condiciones[] = 'DATE(a.FRegistro) <= ?';
        $tipos .= 's';
        $parametros[] = $filtros['fecha_max'];
    }

    $sql = "SELECT a.IdAnuncio, a.Titulo, a.FPrincipal, a.Alternativo, a.Precio, a.Ciudad, a.FRegistro,
                   ta.NomTAnuncio, tv.NomTVivienda, p.NomPais
            FROM Anuncios a
            INNER JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio
            INNER JOIN TiposViviendas tv ON a.TVivienda = tv.IdTVivienda
            INNER JOIN Paises p ON a.Pais = p.IdPais
            WHERE " . implode(' AND ', $condiciones) . "
            ORDER BY a.FRegistro DESC, a.IdAnuncio DESC";

    $filas = ejecutarConsultaSelect($sql, $tipos, $parametros);
    $anuncios = [];

    foreach ($filas as $fila) {
        $anuncios[] = [
            'id'            => (int) $fila['IdAnuncio'],
            'imagen'        => (string) $fila['FPrincipal'],
            'alt'           => (string) $fila['Alternativo'],
            'titulo'        => (string) $fila['Titulo'],
            'fecha'         => formatearFechaBD($fila['FRegistro']),
            'ciudad'        => (string) $fila['Ciudad'],
            'pais'          => (string) $fila['NomPais'],
            'precio'        => formatearPrecioAnuncio($fila['Precio'], $fila['NomTAnuncio']),
            'tipo_anuncio'  => (string) $fila['NomTAnuncio'],
            'tipo_vivienda' => (string) $fila['NomTVivienda'],
        ];
    }

    return $anuncios;
}

// ============================================================================
// Panel de últimos anuncios visitados (Práctica 8)
// ============================================================================

/**
 * Registra la visita a un anuncio en la cookie 'ultimos_anuncios' (duración de 1 semana).
 * Mantiene como máximo los 4 últimos anuncios en orden de visita sin duplicados.
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
 * Calcula el coste unitario de un folleto publicitario según las tarifas por tramos.
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
