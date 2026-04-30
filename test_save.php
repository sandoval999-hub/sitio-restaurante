<?php
$_SESSION["admin_logged_in"] = true;
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST = [
  "id" => "c1",
  "nombre" => "Sopa",
  "categoria" => "comidas",
  "precio" => "5.00",
  "emoji" => "🍲"
];
ob_start();
include("guardar_producto.php");
$out = ob_get_clean();
echo $out;
?>
