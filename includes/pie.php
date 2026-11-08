<?php
/**
 * Práctica 7 - Fragmento de plantilla: cierre de <main>, pie de página (<footer>), scripts opcionales y cierre de </body></html>.
 */
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
