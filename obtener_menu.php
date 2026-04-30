<?php
header('Content-Type: application/json; charset=utf-8');
$db_path = __DIR__ . '/pupuseria.db';

try {
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $db->query("SELECT * FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $menu = [
        "tradicionales" => [],
        "especiales" => [],
        "tamalesYMas" => [],
        "bebidasFrias" => [],
        "bebidasCalientes" => []
    ];
    
    foreach ($productos as $p) {
        $cat = $p['categoria'];
        if (!isset($menu[$cat])) {
            $menu[$cat] = [];
        }
        
        $item = [
            "id" => $p['id'],
            "name" => $p['nombre'],
            "price" => floatval($p['precio'])
        ];
        
        if (!empty($p['emoji'])) {
            $item["emoji"] = $p['emoji'];
        }
        if (!empty($p['subcategoria'])) {
            $item["sub"] = $p['subcategoria'];
        }
        if (!empty($p['imagen_url'])) {
            $item["img"] = $p['imagen_url'];
        }
        
        $menu[$cat][] = $item;
    }
    
    echo json_encode(["success" => true, "menu" => $menu]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
