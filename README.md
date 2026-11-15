# Implementaciones Parte 8

### 1. Opción «Recordarme en este equipo» (Cookies de 90 días, `HttpOnly`, mediante Token)
- **Casilla en el formulario de acceso ([index.php](file:///c:/xampp/htdocs/DAW/prac2/index.php#L90-L93) y [aviso.php](file:///c:/xampp/htdocs/DAW/prac2/aviso.php#L79-L82)):** Se ha añadido `<input type="checkbox" id="recordarme" name="recordarme" value="1" />`.
- **Seguridad con token y flag `HttpOnly` (Págs. 3 y 19):**
  - En lugar de guardar el nombre de usuario y contraseña en texto plano dentro de la cookie, en [datos_usuarios.php](file:///c:/xampp/htdocs/DAW/prac2/includes/datos_usuarios.php) cada usuario tiene un `token` único asociado en el servidor y la cookie `recordar_token` almacena únicamente dicho identificador.
  - Todas las cookies se crean mediante [`enviarCookieSegura()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L56-L67) con `'httponly' => true` para impedir que sean accesibles desde JavaScript.
- **Caducidad de 90 días sin *sliding expiration* (Nota 10, pág. 2):**
  - Al autenticarse en [acceso.php](file:///c:/xampp/htdocs/DAW/prac2/acceso.php#L99-L108) con la casilla marcada, las cookies se fijan a `time() + (90 * 24 * 60 * 60)` y en los sucesivos accesos automáticos ([`iniciarSesionYRecordar()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L105-L155)) se conserva la fecha de caducidad original sin extenderla.

---

### 2. Mensaje de fecha y hora de la última visita y cambio del formulario (Págs. 2 y 3, Figura 2)
- **Sustitución del formulario de login en [index.php](file:///c:/xampp/htdocs/DAW/prac2/index.php#L30-L43):**
  - Cuando el usuario está autenticado o siendo recordado, en lugar de pedir usuario y contraseña se muestra el bloque con los botones **Acceder** (a [index-reg.php](file:///c:/xampp/htdocs/DAW/prac2/index-reg.php)) y **Salir** (a [salir.php](file:///c:/xampp/htdocs/DAW/prac2/salir.php)), además de cambiar el menú superior de navegación ([inicio.php](file:///c:/xampp/htdocs/DAW/prac2/includes/inicio.php)).
  - Si se está recordando al usuario (`$_SESSION['recordado'] === true`), tanto en [index.php](file:///c:/xampp/htdocs/DAW/prac2/index.php#L33-L38) como en [index-reg.php](file:///c:/xampp/htdocs/DAW/prac2/index-reg.php#L24-L29) se muestra el mensaje:
    > *«Hola **[Usuario]**, su última visita fue el **DD/MM/AAAA a las HH:MM**.»*
  - Si el usuario inició sesión sin marcar «Recordarme», no se muestra ningún mensaje de última visita (tal como pide la pág. 2).
- **Cálculo exacto de la última visita (Pág. 3):**
  - Cuando la sesión ha desaparecido (p. ej., tras cerrar el navegador) y [`iniciarSesionYRecordar()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L125-L148) recupera al usuario a partir de la cookie `recordar_token`, guarda en `$_SESSION['ultima_visita']` la fecha/hora almacenada en `recordar_ultima_visita` del acceso anterior y actualiza la cookie con la fecha/hora actual para la próxima vez que se recupere la sesión.

---

### 3. Protección de rutas privadas y parte pública restringida con Sesiones (Págs. 3 y 4)
- La función [`exigirAutenticacion()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L179-L186) comprueba si existe sesión activa (o la restaura si hay cookie de recordar). Si alguien intenta entrar directamente escribiendo la URL sin estar identificado, guarda un aviso en Flashdata y redirige inmediatamente a [aviso.php](file:///c:/xampp/htdocs/DAW/prac2/aviso.php).
- **Páginas protegidas:**
  - **Parte privada:** [index-reg.php](file:///c:/xampp/htdocs/DAW/prac2/index-reg.php), [mis-anuncios.php](file:///c:/xampp/htdocs/DAW/prac2/mis-anuncios.php), [ver-anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/ver-anuncio.php), [crear-anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/crear-anuncio.php), [anadir-foto.php](file:///c:/xampp/htdocs/DAW/prac2/anadir-foto.php), [create-ad.php](file:///c:/xampp/htdocs/DAW/prac2/create-ad.php), [ad-response.php](file:///c:/xampp/htdocs/DAW/prac2/ad-response.php) y [my-messages.php](file:///c:/xampp/htdocs/DAW/prac2/my-messages.php).
  - **Parte pública restringida (Pág. 4):** [anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/anuncio.php) (detalle del anuncio), [message.php](file:///c:/xampp/htdocs/DAW/prac2/message.php) (enviar mensaje) y [message-sent.php](file:///c:/xampp/htdocs/DAW/prac2/message-sent.php) (respuesta enviar mensaje).

---

### 4. Saludo según franjas horarias (Pág. 3)
- La función [`obtenerSaludoHorario($nombreUsuario)`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L198-L213) calcula el saludo en el servidor según la hora actual:
  - **06:00 a 11:59:** *«Buenos días, [Usuario].»*
  - **12:00 a 15:59:** *«Hola, [Usuario].»*
  - **16:00 a 19:59:** *«Buenas tardes, [Usuario].»*
  - **20:00 a 05:59:** *«Buenas noches, [Usuario].»*
- Se muestra tanto en [index-reg.php](file:///c:/xampp/htdocs/DAW/prac2/index-reg.php#L23) como en el botón de acceso al menú del usuario en la barra de navegación superior ([inicio.php](file:///c:/xampp/htdocs/DAW/prac2/includes/inicio.php#L18)).

---

### 5. Patrón *Flashdata* para errores en Control de Acceso y Registro (Págs. 4 y 18)
- Se han implementado [`establecerFlash()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L222-L233) y [`obtenerFlash()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L242-L255) usando `$_SESSION['flash']`, eliminando cada mensaje automáticamente tras leerse una sola vez.
- En [acceso.php](file:///c:/xampp/htdocs/DAW/prac2/acceso.php) y en [register-response.php](file:///c:/xampp/htdocs/DAW/prac2/register-response.php#L51-L67), cuando se produce un error de validación ya **no se pasan parámetros por la URL**: se guardan los errores y los valores introducidos en `$_SESSION['flash']` y se redirige limpiamente a [index.php](file:///c:/xampp/htdocs/DAW/prac2/index.php) / [aviso.php](file:///c:/xampp/htdocs/DAW/prac2/aviso.php) o a [register.php](file:///c:/xampp/htdocs/DAW/prac2/register.php).

---

### 6. Persistencia del estilo CSS en sesión por usuario (Pág. 4)
- En [datos_usuarios.php](file:///c:/xampp/htdocs/DAW/prac2/includes/datos_usuarios.php) cada usuario registrado tiene asignado un estilo de los disponibles en el proyecto:
  - `usuario1` (`Pass123`) → **Modo noche** (`css/noche.css`)
  - `juan` (`Juan123`) → **Texto grande** (`css/grande.css`)
  - `maria` (`Maria123`) → **Alto contraste** (`css/contraste.css`)
  - `admin` (`Admin123`) → **Alto contraste y texto grande** (`css/contrasteygrande.css`)
- Al autenticarse o ser recordado, el estilo se guarda en `$_SESSION['estilo']` y [cabecera.php](file:///c:/xampp/htdocs/DAW/prac2/includes/cabecera.php#L38-L65) lo coloca como hoja de estilo principal (`rel="stylesheet"`) en todas las páginas mientras dure la sesión.

---

### 7. Opción Salir ([salir.php](file:///c:/xampp/htdocs/DAW/prac2/salir.php)) y Panel «Últimos anuncios visitados» (Págs. 4, 6 y 10)
- **Panel «Últimos anuncios visitados» ([panel_visitados.php](file:///c:/xampp/htdocs/DAW/prac2/includes/panel_visitados.php)):**
  - Visible en todo momento para cualquier usuario (autenticado o no).
  - Al visitar un anuncio en [anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/anuncio.php#L14) (o [ver-anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/ver-anuncio.php#L13)), [`registrarVisitaAnuncio()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L294-L329) actualiza la cookie `ultimos_anuncios` con duración de **1 semana**: mantiene hasta 4 anuncios en orden de visita, sin duplicar si se vuelve a visitar uno ya presente (cambia su posición al final) y eliminando el más antiguo al superar 4.
  - En cada anuncio del panel muestra **foto principal, título (con enlace a `anuncio.php?id=...`), ciudad, país y precio**.
- **Cerrar sesión ([salir.php](file:///c:/xampp/htdocs/DAW/prac2/salir.php)):**
  - Borra las cookies de «Recordarme en este equipo», borra la cookie de «Últimos anuncios visitados», vacía `$_SESSION = array()`, caduca la cookie `session_name()` (`PHPSESSID`), ejecuta `session_destroy()` y redirige a [index.php](file:///c:/xampp/htdocs/DAW/prac2/index.php).
- [cabecera.php](file:///c:/xampp/htdocs/DAW/prac2/includes/cabecera.php): Contiene la declaración `<!doctype html>`, `<head>`, `<title>` dinámico y los enlaces a las hojas de estilo.
- [inicio.php](file:///c:/xampp/htdocs/DAW/prac2/includes/inicio.php): Incluye el enlace de salto accesible, el `<header>` con el título del sitio, la barra de navegación (`<nav>`) diferenciada para parte pública y parte privada (`$esPaginaPrivada`) y la apertura de `<main>`.
- [pie.php](file:///c:/xampp/htdocs/DAW/prac2/includes/pie.php): Cierra `<main>`, muestra el `<footer>` común y carga los scripts opcionales de cada página.
- [ultimos_anuncios.php](file:///c:/xampp/htdocs/DAW/prac2/includes/ultimos_anuncios.php): Fragmento compartido para el listado de los 5 últimos anuncios (con enlace a `anuncio.php?id=...`) y el formulario de búsqueda rápida.
- [funciones.php](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php): Define [`e()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L13-L20) para escapar salidas HTML (`htmlspecialchars` contra XSS), [`tieneContenidoSinEspacios()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L28-L43), [`calcularCosteFolleto()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L60-L72), [`formatearEuros()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L80-L83) y [`redirigir()`](file:///c:/xampp/htdocs/DAW/prac2/includes/funciones.php#L91-L97) con URL absoluta HTTP 1.1.
- [datos_usuarios.php](file:///c:/xampp/htdocs/DAW/prac2/includes/datos_usuarios.php): Fichero independiente con los 4 usuarios permitidos (`usuario1`/`Pass123`, `juan`/`Juan123`, `maria`/`Maria123`, `admin`/`Admin123`).
- [datos_anuncios.php](file:///c:/xampp/htdocs/DAW/prac2/includes/datos_anuncios.php): Fichero independiente con los datos completos de dos anuncios distintos (para IDs impares y pares).

---

### 2. Páginas modificadas
- **Solicitar folleto ([create-ad.php](file:///c:/xampp/htdocs/DAW/prac2/create-ad.php))**: Genera mediante PHP la tabla completa con los posibles costes de un folleto impreso (de 1 a 15 páginas y de 3 a 45 fotos, en Blanco y negro / Color y resoluciones 150-300 dpi / 450-900 dpi), manteniendo además el botón para mostrar la tabla con JavaScript ([folleto.js](file:///c:/xampp/htdocs/DAW/prac2/js/folleto.js)) y comprobar que ambas coinciden.
- **Respuesta Solicitar folleto ([ad-response.php](file:///c:/xampp/htdocs/DAW/prac2/ad-response.php))**: Muestra la confirmación de registro de la solicitud, todos los datos recibidos del formulario y el coste final calculado en PHP aplicando la tabla de tarifas sobre valores ficticios de páginas y fotos y multiplicando el precio unitario por el número de copias solicitado.
- **Listado resultado de búsqueda ([result.php](file:///c:/xampp/htdocs/DAW/prac2/result.php))**: Muestra los datos reales introducidos por el usuario tanto si llega desde la búsqueda rápida de [index.php](file:///c:/xampp/htdocs/DAW/prac2/index.php) / [index-reg.php](file:///c:/xampp/htdocs/DAW/prac2/index-reg.php) como desde el formulario de búsqueda avanzada ([search.php](file:///c:/xampp/htdocs/DAW/prac2/search.php)), junto con los resultados de ejemplo enlazados a `anuncio.php?id=1` y `anuncio.php?id=2`.
- **Detalle del anuncio ([anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/anuncio.php))**: Comprueba la existencia y permisos de lectura de [datos_anuncios.php](file:///c:/xampp/htdocs/DAW/prac2/includes/datos_anuncios.php) y muestra uno de los dos anuncios según si el parámetro `id` recibido por URL es par o impar, incluyendo todas sus características (superficie, habitaciones, baños, planta y año de construcción) y miniaturas.
- **Respuesta Enviar mensaje ([message-sent.php](file:///c:/xampp/htdocs/DAW/prac2/message-sent.php))**: Valida en PHP que el tipo de mensaje enviado desde [message.php](file:///c:/xampp/htdocs/DAW/prac2/message.php) o [contact.php](file:///c:/xampp/htdocs/DAW/prac2/contact.php) sea válido y que el texto no esté vacío ni formado solo por espacios/tabuladores, mostrando la confirmación y los datos recibidos.

---

### 3. Páginas nuevas creadas
- **Control de acceso ([acceso.php](file:///c:/xampp/htdocs/DAW/prac2/acceso.php))**: Página no visible que valida que usuario y contraseña no estén vacíos ni contengan solo espacios o tabuladores, comprueba las credenciales contra los 4 usuarios de [datos_usuarios.php](file:///c:/xampp/htdocs/DAW/prac2/includes/datos_usuarios.php) y redirige mediante `header("Location: ...")` a [index-reg.php](file:///c:/xampp/htdocs/DAW/prac2/index-reg.php) si es correcto o de vuelta a [index.php](file:///c:/xampp/htdocs/DAW/prac2/index.php) / [aviso.php](file:///c:/xampp/htdocs/DAW/prac2/aviso.php) mostrando el mensaje de error correspondiente.
- **Mis anuncios ([mis-anuncios.php](file:///c:/xampp/htdocs/DAW/prac2/mis-anuncios.php))**: Muestra el listado de anuncios del usuario con foto principal en miniatura, título, ciudad, país, precio y enlaces a ver anuncio, añadir foto o crear anuncio.
- **Ver anuncio ([ver-anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/ver-anuncio.php))**: Muestra toda la información del anuncio en la parte privada e incluye el enlace a `anadir-foto.php?anuncio=...` con el anuncio actual seleccionado.
- **Añadir foto a anuncio ([anadir-foto.php](file:///c:/xampp/htdocs/DAW/prac2/anadir-foto.php))**: Formulario con foto, título, texto alternativo (mínimo 10 caracteres) y desplegable de anuncio (editable si se accede desde el menú privado; preseleccionado y bloqueado si se accede desde [mis-anuncios.php](file:///c:/xampp/htdocs/DAW/prac2/mis-anuncios.php) o [ver-anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/ver-anuncio.php)).
- **Crear anuncio ([crear-anuncio.php](file:///c:/xampp/htdocs/DAW/prac2/crear-anuncio.php))**: Formulario completo con los datos generales y características necesarias para publicar un anuncio.
- **Respuesta Registro de nuevo usuario ([register-response.php](file:///c:/xampp/htdocs/DAW/prac2/register-response.php))**: Recibe los datos de [register.php](file:///c:/xampp/htdocs/DAW/prac2/register.php) (con validaciones HTML/JS desactivadas), comprueba en PHP que se haya escrito algo en usuario, contraseña y repetir contraseña y que ambas contraseñas coincidan, mostrando los errores detectados (con enlace para volver al formulario pasando los datos por URL) o los datos registrados (sin la foto de perfil).
