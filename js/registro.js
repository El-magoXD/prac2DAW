/**
 * Módulo de validación del formulario de registro de nuevo usuario (register.html).
 * Importante: Todas las validaciones deben programarse mediante algoritmos en JS
 * SIN emplear expresiones regulares.
 * Práctica 6: JavaScript
 */

/**
 * Valida el nombre de usuario según las reglas de la práctica:
 * - Solo puede contener letras del alfabeto inglés (A-Z, a-z) y números (0-9).
 * - No puede comenzar con un número.
 * - Longitud mínima de 3 caracteres y máxima de 15 caracteres.
 * @param {string} usuario - Nombre de usuario obtenido de $("usuario").value.
 * @returns {string} Cadena vacía "" si es válido, o mensaje descriptivo del error si no lo es.
 */
function validarNombreUsuario(usuario) {
  // TODO: Corresponde a SÁNCHEZ
  // Implementar el algoritmo carácter a carácter (ej. con charAt / charCodeAt) sin expresiones regulares.
}

/**
 * Valida la contraseña según las reglas de la práctica:
 * - Solo letras del alfabeto inglés (A-Z, a-z), números (0-9), guion (-) y guion bajo (_).
 * - Debe contener al menos una letra mayúscula, una letra minúscula y un número.
 * - Longitud mínima de 6 caracteres y máxima de 15 caracteres.
 * @param {string} password - Contraseña obtenida de $("password").value.
 * @returns {string} Cadena vacía "" si es válida, o mensaje descriptivo del error si no lo es.
 */
function validarPassword(password) {
  // TODO: Corresponde a PEDRO
  // Implementar el algoritmo carácter a carácter con contadores/banderas para mayúscula, minúscula y número.
}

/**
 * Comprueba que el campo "repetir contraseña" coincida exactamente con la contraseña.
 * @param {string} password - Valor del campo contraseña ($("password").value).
 * @param {string} repetirPassword - Valor del campo repetir contraseña ($("password2").value).
 * @returns {string} Cadena vacía "" si coinciden, o mensaje descriptivo del error si no coinciden.
 */
function validarRepetirPassword(password, repetirPassword) {
  // TODO: Corresponde a PEDRO
  // Comprobar con igualdad estricta (===) que repetirPassword no esté vacío y sea igual a password.
}

/**
 * Valida la parte-local de un correo electrónico (antes de la '@'):
 * - Longitud mínima de 1 carácter y máxima de 64 caracteres.
 * - Combinación de letras inglesas (A-Z, a-z), dígitos (0-9), caracteres !#$%&'*+-/=?^_`{|}~ y el punto (.).
 * - El punto (.) no puede aparecer ni al principio ni al final, ni dos o más puntos seguidos.
 * @param {string} parteLocal - Subcadena anterior al símbolo '@'.
 * @returns {boolean} true si la parte-local cumple todas las reglas; false en caso contrario.
 */
function validarParteLocalEmail(parteLocal) {
  // TODO: Corresponde a SÁNCHEZ
  // Implementar la comprobación de longitud, puntos extremos/consecutivos y caracteres permitidos sin regex.
}

/**
 * Valida el dominio de un correo electrónico (después de la '@'):
 * - Longitud mínima de 1 carácter y máxima de 255 caracteres.
 * - Secuencia de uno o más subdominios separados por un punto (.).
 * - Cada subdominio tiene longitud entre 1 y 63 caracteres.
 * - Cada subdominio solo contiene letras inglesas (A-Z, a-z), dígitos (0-9) y el guion (-).
 * - El guion (-) no puede estar ni al principio ni al final de ningún subdominio.
 * @param {string} dominio - Subcadena posterior al símbolo '@'.
 * @returns {boolean} true si el dominio cumple todas las reglas; false en caso contrario.
 */
function validarDominioEmail(dominio) {
  // TODO: Corresponde a PEDRO
  // Dividir por '.' con split('.'), comprobar longitud de cada subdominio, guiones en extremos y caracteres válidos.
}

/**
 * Valida la dirección de email completa combinando formato parte-local@dominio,
 * longitud máxima total de 254 caracteres, validarParteLocalEmail y validarDominioEmail.
 * @param {string} email - Dirección de correo obtenida de $("email").value.
 * @returns {string} Cadena vacía "" si el email es válido, o mensaje de error en caso contrario.
 */
function validarEmail(email) {
  // TODO: Corresponde a PEDRO
  // 1. Comprobar que no esté vacío y que su longitud total sea <= 254 caracteres.
  // 2. Separar en parte-local y dominio por una única '@' y llamar a validarParteLocalEmail y validarDominioEmail.
}

/**
 * Comprueba que el usuario haya seleccionado una de las opciones del campo "Sexo".
 * @param {NodeListOf<HTMLElement>} opcionesSexo - Lista de radio buttons obtenida con document.getElementsByName("sexo").
 * @returns {string} Cadena vacía "" si hay una opción marcada, o mensaje de error si no hay ninguna.
 */
function validarSexo(opcionesSexo) {
  // TODO: Corresponde a SÁNCHEZ
  // Recorrer 'opcionesSexo' y verificar si alguno tiene la propiedad .checked === true.
}

/**
 * Comprueba que la fecha de nacimiento sea una fecha real válida en el calendario
 * y que la persona tenga al menos 18 años recién cumplidos el mismo día en que se registra.
 * @param {string} fechaTexto - Fecha obtenida de $("fecha-nacimiento").value.
 * @returns {string} Cadena vacía "" si es válida y mayor de 18 años, o mensaje de error en caso contrario.
 */
function validarFechaNacimiento(fechaTexto) {
  // TODO: Corresponde a SÁNCHEZ
  // 1. Parsear día, mes y año de fechaTexto, crear un objeto Date y verificar que getDate(), getMonth() y getFullYear() coincidan.
  // 2. Comparar con la fecha actual (new Date()) para comprobar que ha cumplido al menos 18 años hoy.
}

/**
 * Manejador del evento submit del formulario de registro (#form-registro).
 * Recoge todos los valores de los inputs del HTML y los pasa a cada función de validación.
 * @param {Event} event - Objeto del evento submit.
 */
function validarFormularioRegistro(event) {
  limpiarErroresFormulario("form-registro");

  // Lectura de los valores introducidos en el HTML
  const usuario = $("usuario").value;
  const password = $("password").value;
  const password2 = $("password2").value;
  const email = $("email").value;
  const opcionesSexo = document.getElementsByName("sexo");
  const fechaNacimiento = $("fecha-nacimiento").value;

  // Llamadas a las funciones de validación con los datos del HTML
  const errorUsuario = validarNombreUsuario(usuario);
  const errorPassword = validarPassword(password);
  const errorPassword2 = validarRepetirPassword(password, password2);
  const errorEmail = validarEmail(email);
  const errorSexo = validarSexo(opcionesSexo);
  const errorFecha = validarFechaNacimiento(fechaNacimiento);

  // TODO: Corresponde a PEDRO
  // 1. Comprobar cuáles de las variables error* contienen texto de error (distinto de "").
  // 2. Para cada campo con error, llamar a mostrarErrorCampo(idCampo, mensajeError).
  // 3. Si existe al menos un error, cancelar el envío del formulario con event.preventDefault().
}

/**
 * Conecta el formulario #form-registro del HTML con validarFormularioRegistro al cargar el DOM.
 */
function inicializarRegistro() {
  const formRegistro = $("form-registro");
  if (formRegistro) {
    formRegistro.addEventListener("submit", validarFormularioRegistro, false);
  }
}

document.addEventListener("DOMContentLoaded", inicializarRegistro, false);
