<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$db_path = __DIR__ . '/pupuseria.db';

try {
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $original_id = $_POST['original_id'] ?? '';
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $subcategoria = $_POST['subcategoria'] ?? null;
    $precio = floatval($_POST['precio'] ?? 0);
    $emoji = $_POST['emoji'] ?? null;
    $imagen_url = null;

    if (empty($subcategoria) || $categoria !== 'bebidasFrias') {
        $subcategoria = null;
    }

    if (empty($id) || empty($nombre) || $precio <= 0 || empty($categoria)) {
        throw new Exception("Faltan campos obligatorios");
    }

    // Process image upload if exists
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (!in_array($ext, $allowed)) {
            throw new Exception("Formato de imagen no permitido. Usa JPG, PNG o WEBP.");
        }

        // Generate unique filename
        $filename = uniqid('prod_') . '.' . $ext;
        $dest_path = __DIR__ . '/img/' . $filename;
        
        // Simple move (no GD cropping to avoid missing GD extension issues on XAMPP by default, but standard img tag handles it with object-fit: cover)
        if (move_uploaded_file($file['tmp_name'], $dest_path)) {
            $imagen_url = 'img/' . $filename;
        } else {
            throw new Exception("Error al subir la imagen");
        }
    }

    if ($original_id) {
        // Update
        // If no new image was uploaded, keep the old one
        if ($imagen_url === null) {
            $stmt = $db->prepare("UPDATE productos SET id = :id, categoria = :cat, subcategoria = :sub, nombre = :nom, emoji = :emoji, precio = :precio WHERE id = :old_id");
            $stmt->execute([
                ':id' => $id, ':cat' => $categoria, ':sub' => $subcategoria, ':nom' => $nombre, ':emoji' => $emoji, ':precio' => $precio, ':old_id' => $original_id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE productos SET id = :id, categoria = :cat, subcategoria = :sub, nombre = :nom, emoji = :emoji, precio = :precio, imagen_url = :img WHERE id = :old_id");
            $stmt->execute([
                ':id' => $id, ':cat' => $categoria, ':sub' => $subcategoria, ':nom' => $nombre, ':emoji' => $emoji, ':precio' => $precio, ':img' => $imagen_url, ':old_id' => $original_id
            ]);
        }
    } else {
        // Insert
        // Check if ID already exists
        $check = $db->prepare("SELECT id FROM productos WHERE id = :id");
        $check->execute([':id' => $id]);
        if ($check->fetch()) {
            throw new Exception("El ID '$id' ya existe");
        }
        
        $stmt = $db->prepare("INSERT INTO productos (id, categoria, subcategoria, nombre, emoji, precio, imagen_url) VALUES (:id, :cat, :sub, :nom, :emoji, :precio, :img)");
        $stmt->execute([
            ':id' => $id, ':cat' => $categoria, ':sub' => $subcategoria, ':nom' => $nombre, ':emoji' => $emoji, ':precio' => $precio, ':img' => $imagen_url
        ]);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
