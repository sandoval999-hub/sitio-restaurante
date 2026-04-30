<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'error' => 'Falta el ID']);
    exit;
}

$db_path = __DIR__ . '/pupuseria.db';

try {
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("DELETE FROM productos WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
