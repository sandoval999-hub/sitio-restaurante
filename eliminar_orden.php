<?php
/**
 * ============================================
 * ELIMINAR ORDEN
 * Elimina una orden y sus items de la BD
 * ============================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$orden_id = isset($data['orden_id']) ? intval($data['orden_id']) : 0;

if ($orden_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de orden inválido.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Primero eliminar los items de la orden
    $stmtItems = $pdo->prepare("DELETE FROM orden_items WHERE orden_id = ?");
    $stmtItems->execute([$orden_id]);

    // Luego eliminar la orden
    $stmtOrden = $pdo->prepare("DELETE FROM ordenes WHERE id = ?");
    $stmtOrden->execute([$orden_id]);

    $deleted = $stmtOrden->rowCount();

    if ($deleted > 0) {
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Orden eliminada correctamente.']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'Orden no encontrada.']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al eliminar: ' . $e->getMessage()]);
}
