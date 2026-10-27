/**
 * Módulo de validación del formulario de registro de nuevo usuario (register.html).
 * Importante: Todas las validaciones deben programarse mediante algoritmos en JS
 * SIN emplear expresiones regulares y desactivando las validaciones nativas de HTML.
 * Práctica 6: JavaScript
 */

/**
 * Valida el nombre de usuario según las reglas de la práctica:
 * - Solo puede contener letras del alfabeto inglés (A-Z, a-z) y números (0-9).
 * - No puede comenzar con un número.
 * - Longitud mínima de 3 caracteres y máxima de 15 caracteres.
 * @param {string} usuario - Nombre de usuario a comprobar.
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
 * @param {string} password - Contraseña a comprobar.
 * @returns {string} Cadena vacía "" si es válida, o mensaje descriptivo del error si no lo es.
 */
function validarPassword(password) {
  // TODO: Corresponde a PEDRO
  // Implementar el algoritmo carácter a carácter con contadores/banderas para mayúscula, minúscula y número.
}

/**
 * Comprueba que el campo "repetir contraseña" coincida exactamente con la contraseña.
 * @param {string} password - Valor del campo contraseña.
 * @param {string} repetirPassword - Valor del campo repetir contraseña.
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
 * @param {string} email - Dirección de correo electrónico completa.
 * @returns {string} Cadena vacía "" si el email es válido, o mensaje de error en caso contrario.
 */
function validarEmail(email) {
  // TODO: Corresponde a PEDRO
  // 1. Comprobar que no esté vacío y que su longitud total sea <= 254 caracteres.
  // 2. Separar en parte-local y dominio por una única '@' y llamar a validarParteLocalEmail y validarDominioEmail.
}

/**
 * Comprueba que el usuario haya seleccionado una de las opciones del campo "Sexo".
 * @returns {string} Cadena vacía "" si hay una opción marcada, o mensaje de error si no hay ninguna.
 */
function validarSexo() {
  // TODO: Corresponde a SÁNCHEZ
  // Obtener los radio buttons con document.getElementsByName("sexo") y verificar si alguno tiene .checked === true.
}

/**
 * Comprueba que la fecha de nacimiento sea una fecha real válida en el calendario
 * y que la persona tenga al menos 18 años recién cumplidos el mismo día en que se registra.
 * @param {string} fechaTexto - Fecha introducida por el usuario.
 * @returns {string} Cadena vacía "" si es válida y mayor de 18 años, o mensaje de error en caso contrario.
 */
function validarFechaNacimiento(fechaTexto) {
  // TODO: Corresponde a SÁNCHEZ
  // 1. Parsear día, mes y año de fechaTexto, crear un objeto Date y verificar que getDate(), getMonth() y getFullYear() coincidan.
  // 2. Comparar con la fecha actual (new Date()) para comprobar que ha cumplido al menos 18 años hoy.
}

/**
 * Manejador del evento submit del formulario de registro.
 * Ejecuta todas las validaciones de los campos y evita el envío si existe algún error.
 * @param {Event} event - Objeto del evento submit.
 */
function validarFormularioRegistro(event) {
  // TODO: Corresponde a PEDRO
  // 1. Limpiar errores anteriores con limpiarErroresFormulario().
  // 2. Invocar a cada función de validación y mostrar los errores correspondientes con mostrarErrorCampo().
  // 3. Si hay algún error, cancelar el envío del formulario con event.preventDefault().
}

/**
 * Inicializa el formulario de registro desactivando las validaciones HTML nativas
 * y registrando el evento submit al cargar el DOM.
 */
function inicializarRegistro() {
  // TODO: Corresponde a SÁNCHEZ
  // 1. Quitar atributos HTML de validación (required, pattern, type="email", type="date") o activar noValidate desde JS.
  // 2. Asociar validarFormularioRegistro al evento "submit" del formulario.
}

document.addEventListener("DOMContentLoaded", inicializarRegistro, false);

