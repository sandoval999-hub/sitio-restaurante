<?php
/**
 * ============================================
 * PUPUSERÍA - GENERADOR DE TICKETS
 * Endpoint para procesar órdenes y generar tickets
 * ============================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/db_config.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Use POST.'
    ]);
    exit;
}

// Leer datos de la orden
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['items']) || empty($data['items'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se recibieron items en la orden.'
    ]);
    exit;
}

// Procesar la orden
$items = $data['items'];
$customerName = isset($data['customerName']) ? htmlspecialchars(trim($data['customerName'])) : '';
$customerPhone = isset($data['customerPhone']) ? htmlspecialchars(trim($data['customerPhone'])) : '';
$subtotal = 0;
$processedItems = [];

foreach ($items as $item) {
    // Validar datos del item
    if (!isset($item['id'], $item['name'], $item['price'], $item['qty'], $item['emoji'])) {
        continue;
    }

    $qty = max(1, intval($item['qty']));
    $price = floatval($item['price']);
    $itemTotal = $price * $qty;
    $subtotal += $itemTotal;

    $processedItems[] = [
        'id' => htmlspecialchars($item['id']),
        'name' => htmlspecialchars($item['name']),
        'emoji' => $item['emoji'],
        'price' => $price,
        'qty' => $qty,
        'total' => $itemTotal,
        'orderType' => isset($item['orderType']) ? htmlspecialchars($item['orderType']) : 'Comer Aquí',
        'masa' => isset($item['masa']) ? htmlspecialchars($item['masa']) : null
    ];
}

if (empty($processedItems)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se encontraron items válidos.'
    ]);
    exit;
}

if (isset($data['orderNumber']) && !empty($data['orderNumber'])) {
    // If frontend explicitly provides an order number (e.g. when editing), use it
    if (is_numeric($data['orderNumber'])) {
        $orderNumber = 'Orden #' . intval($data['orderNumber']);
    } else {
        $orderNumber = htmlspecialchars($data['orderNumber']);
    }
}
else {
    $orderNumber = 'ORD-' . str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);
}

// Fecha y hora
$timezone = new DateTimeZone('America/El_Salvador');
$now = new DateTime('now', $timezone);
$date = $now->format('d/m/Y');
$time = $now->format('h:i A');

// Calcular total
$total = $subtotal;

// Guardar orden en archivo de log (opcional, para registro)
$logDir = __DIR__ . '/ordenes';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$logEntry = [
    'orderNumber' => $orderNumber,
    'date' => $date,
    'time' => $time,
    'customerName' => $customerName,
    'customerPhone' => $customerPhone,
    'items' => $processedItems,
    'subtotal' => $subtotal,
    'total' => $total,
    'timestamp' => $now->format('Y-m-d H:i:s')
];

$logFile = $logDir . '/ordenes_' . $now->format('Y-m-d') . '.json';
$existingOrders = [];

if (file_exists($logFile)) {
    $existingOrders = json_decode(file_get_contents($logFile), true) ?? [];
}

$existingOrders[] = $logEntry;
file_put_contents($logFile, json_encode($existingOrders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// NOTA: La orden se guarda en la BD solo cuando se imprime el ticket (ver guardar_orden.php)
// Aquí solo se genera la respuesta del ticket sin guardar en BD.

// Leer llevarNumber del frontend (si aplica)
$llevarNumber = isset($data['llevarNumber']) ? $data['llevarNumber'] : null;
$customerHora = isset($data['customerHora']) ? $data['customerHora'] : null;

// Respuesta exitosa
echo json_encode([
    'success' => true,
    'orderNumber' => $orderNumber,
    'date' => $date,
    'time' => $time,
    'customerName' => $customerName,
    'customerPhone' => $customerPhone,
    'customerHora' => $customerHora,
    'items' => $processedItems,
    'subtotal' => $subtotal,
    'total' => $total,
    'llevarNumber' => $llevarNumber
], JSON_UNESCAPED_UNICODE);
