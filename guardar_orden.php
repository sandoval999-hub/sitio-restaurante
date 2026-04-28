<?php
/**
 * ============================================
 * GUARDAR ORDEN EN BD
 * Solo se llama cuando el ticket se imprime
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

if (!$data || !isset($data['items']) || empty($data['items'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No hay datos de orden.']);
    exit;
}

$orderNumber = $data['orderNumber'] ?? 'ORD-' . mt_rand(10000, 99999);
$customerName = htmlspecialchars(trim($data['customerName'] ?? ''));
$customerPhone = htmlspecialchars(trim($data['customerPhone'] ?? ''));
$subtotal = floatval($data['subtotal'] ?? 0);
$total = floatval($data['total'] ?? 0);
$date = $data['date'] ?? '';
$time = $data['time'] ?? '';

// Convertir fecha de dd/mm/YYYY a YYYY-mm-dd para la BD
$timezone = new DateTimeZone('America/El_Salvador');
$now = new DateTime('now', $timezone);
$orderDate = $now->format('Y-m-d');

try {
    $pdo->beginTransaction();

    if (isset($data['editingOrderId']) && is_numeric($data['editingOrderId'])) {
        $orden_id = intval($data['editingOrderId']);
        // Actualizar Orden
        $stmt = $pdo->prepare("UPDATE ordenes SET customer_name = ?, customer_phone = ?, subtotal = ?, total = ? WHERE id = ?");
        // Nota: Mantenemos el order_number original, order_date y order_time. Si se quisiera actualizar el order_number (ej. si cambió a Para Llevar), se haría aquí. Según requerimiento, se mantiene el número (Para llevar) o nombre original.
        $stmt->execute([$customerName, $customerPhone, $subtotal, $total, $orden_id]);
        
        // Eliminar items anteriores
        $stmtDel = $pdo->prepare("DELETE FROM orden_items WHERE orden_id = ?");
        $stmtDel->execute([$orden_id]);
    } else {
        // Insertar Orden
        $stmt = $pdo->prepare("INSERT INTO ordenes (order_number, customer_name, customer_phone, subtotal, total, order_date, order_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$orderNumber, $customerName, $customerPhone, $subtotal, $total, $orderDate, $time]);
        $orden_id = $pdo->lastInsertId();
    }

    // Insertar Items
    $stmtItem = $pdo->prepare("INSERT INTO orden_items (orden_id, item_id, item_name, emoji, price, qty, total, order_type, masa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($data['items'] as $item) {
        $stmtItem->execute([
            $orden_id,
            htmlspecialchars($item['id'] ?? ''),
            htmlspecialchars($item['name'] ?? ''),
            $item['emoji'] ?? '',
            floatval($item['price'] ?? 0),
            intval($item['qty'] ?? 1),
            floatval($item['total'] ?? 0),
            htmlspecialchars($item['orderType'] ?? 'Comer Aquí'),
            isset($item['masa']) ? htmlspecialchars($item['masa']) : null
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'orden_id' => $orden_id]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al guardar: ' . $e->getMessage()]);
}
