<?php
$app_path = __DIR__ . '/js/app.js';
$content = file_get_contents($app_path);

// 1. Replace const MENU = { ... }; with let MENU = {};
$content = preg_replace('/const MENU = \{[\s\S]*?\};\n/', "let MENU = {};\n", $content);

// 2. Replace DOMContentLoaded block
$target = "document.addEventListener('DOMContentLoaded', () => {
  renderMenu();
  updateSectionHeader();
  updateOrderUI();
  updateDateTime();
  setInterval(updateDateTime, 1000);";

$replacement = "document.addEventListener('DOMContentLoaded', async () => {
  try {
      const res = await fetch('obtener_menu.php');
      const data = await res.json();
      if (data.success) {
          MENU = data.menu;
      } else {
          console.error('Error al obtener menú:', data.error);
      }
  } catch (e) {
      console.error('Error en fetch menú:', e);
  }

  renderMenu();
  updateSectionHeader();
  updateOrderUI();
  updateDateTime();
  setInterval(updateDateTime, 1000);";

$content = str_replace($target, $replacement, $content);

file_put_contents($app_path, $content);
echo "app.js updated successfully.\n";
?>
