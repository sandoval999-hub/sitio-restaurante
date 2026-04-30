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
    $id = trim($_POST['id'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
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

    // Validate ID format (alphanumeric + underscores)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $id)) {
        throw new Exception("El ID solo puede contener letras, números y guión bajo");
    }

    // Process image upload if exists (file upload has priority)
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        if (!in_array($ext, $allowed)) {
            throw new Exception("Formato de imagen no permitido. Usa JPG, PNG, WEBP o GIF.");
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception("La imagen es demasiado grande. Máximo 5MB.");
        }

        // Generate unique filename
        $filename = uniqid('prod_') . '.' . $ext;
        $dest_path = __DIR__ . '/img/' . $filename;
        
        // Ensure img directory exists
        if (!is_dir(__DIR__ . '/img/')) {
            mkdir(__DIR__ . '/img/', 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $dest_path)) {
            $imagen_url = 'img/' . $filename;
        } else {
            throw new Exception("Error al subir la imagen");
        }
    } elseif (!empty($_POST['imagen_url_from_field'])) {
        // Use URL provided from the text field
        $imagen_url = trim($_POST['imagen_url_from_field']);
        // Basic URL validation
        if (!filter_var($imagen_url, FILTER_VALIDATE_URL) && !preg_match('/^img\//', $imagen_url)) {
            throw new Exception("La URL de imagen no es válida");
        }
    }

    if ($original_id) {
        // Update existing product
        if ($imagen_url !== null) {
            // New image provided
            $stmt = $db->prepare("UPDATE productos SET id = :id, categoria = :cat, subcategoria = :sub, nombre = :nom, emoji = :emoji, precio = :precio, imagen_url = :img WHERE id = :old_id");
            $stmt->execute([
                ':id' => $id, ':cat' => $categoria, ':sub' => $subcategoria, ':nom' => $nombre, ':emoji' => $emoji, ':precio' => $precio, ':img' => $imagen_url, ':old_id' => $original_id
            ]);
        } else {
            // Keep old image
            $stmt = $db->prepare("UPDATE productos SET id = :id, categoria = :cat, subcategoria = :sub, nombre = :nom, emoji = :emoji, precio = :precio WHERE id = :old_id");
            $stmt->execute([
                ':id' => $id, ':cat' => $categoria, ':sub' => $subcategoria, ':nom' => $nombre, ':emoji' => $emoji, ':precio' => $precio, ':old_id' => $original_id
            ]);
        }
    } else {
        // Insert new product
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
