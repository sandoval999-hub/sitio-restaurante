<?php
/**
 * ============================================
 * OBTENER ORDEN POR ID
 * Retorna los detalles de la orden y sus items
 * ============================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/db_config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de orden no proporcionado o inválido.']);
    exit;
}

$orden_id = intval($_GET['id']);

try {
    // Obtener la orden
    $stmt = $pdo->prepare("SELECT * FROM ordenes WHERE id = ?");
    $stmt->execute([$orden_id]);
    $orden = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$orden) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Orden no encontrada.']);
        exit;
    }

    // Obtener los items de la orden
    $stmtItems = $pdo->prepare("SELECT * FROM orden_items WHERE orden_id = ?");
    $stmtItems->execute([$orden_id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // Formatear items para que coincidan con la estructura del frontend
    $formattedItems = [];
    foreach ($items as $item) {
        $formattedItems[] = [
            'id' => $item['item_id'],
            'name' => $item['item_name'],
            'emoji' => $item['emoji'],
            'price' => floatval($item['price']),
            'qty' => intval($item['qty']),
            'total' => floatval($item['total']),
            'orderType' => $item['order_type'],
            'masa' => $item['masa']
        ];
    }

    $response = [
        'success' => true,
        'order' => [
            'id' => $orden['id'],
            'orderNumber' => $orden['order_number'],
            'customerName' => $orden['customer_name'],
            'customerPhone' => $orden['customer_phone'],
            'subtotal' => floatval($orden['subtotal']),
            'total' => floatval($orden['total']),
            'date' => $orden['order_date'],
            'time' => $orden['order_time'],
            'items' => $formattedItems
        ]
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
}
