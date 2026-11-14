<?php
/**
 * Práctica 7 y 8 - Fragmento de plantilla: panel de últimos anuncios visitados,
 * cierre de <main>, pie de página (<footer>), scripts opcionales y cierre de </body></html>.
 */
require_once __DIR__ . '/panel_visitados.php';

// Cerrar la escritura de sesión cuanto antes para evitar bloqueos concurrentes (apartado 4.3.3)
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}
?>
    </main>

    <footer>
      <p>Pedro González Sánchez &amp; Alejandro Sánchez Martí</p>
      <p>&copy; 2026 PI - Pisos e Inmuebles. Todos los derechos reservados. | <a href="accesibilidad.php">Declaración de accesibilidad</a></p>
    </footer>
<?php if (!empty($scriptsPagina) && is_array($scriptsPagina)): ?>
<?php foreach ($scriptsPagina as $rutaScript): ?>
    <script src="<?= e($rutaScript) ?>"></script>
<?php endforeach; ?>
<?php endif; ?>
  </body>
</html>
