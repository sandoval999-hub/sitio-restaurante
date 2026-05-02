<?php
$db_file = __DIR__ . '/pupuseria.db';
try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA journal_mode=WAL');
    $pdo->exec('PRAGMA synchronous=NORMAL');

    // Crear tablas si no existen
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS ordenes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_number TEXT,
        customer_name TEXT,
        customer_phone TEXT,
        subtotal REAL,
        total REAL,
        order_date TEXT,
        order_time TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS orden_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        orden_id INTEGER,
        item_id TEXT,
        item_name TEXT,
        emoji TEXT,
        price REAL,
        qty INTEGER,
        total REAL,
        order_type TEXT,
        masa TEXT,
        FOREIGN KEY(orden_id) REFERENCES ordenes(id)
    );

    CREATE INDEX IF NOT EXISTS idx_ordenes_fecha ON ordenes(order_date);
    CREATE INDEX IF NOT EXISTS idx_orden_items_orden_id ON orden_items(orden_id);
    ");

    // Add new columns if they don't exist yet
    try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN payment_status TEXT DEFAULT 'Pendiente'"); } catch (Exception $e) { /* column already exists */ }
    try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN delivery_fee REAL DEFAULT 0"); } catch (Exception $e) { /* column already exists */ }
} catch (PDOException $e) {
    // Si hay error de conexión a la BD, podemos logearlo o terminar
    // file_put_contents('db_error.log', $e->getMessage() . "\n", FILE_APPEND);
    // echo json_encode(['success' => false, 'error' => 'Error de base de datos']);
    // exit;
}
