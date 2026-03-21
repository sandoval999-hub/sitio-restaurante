<?php
require_once __DIR__ . '/db_config.php';

// Definir zona horaria
$timezone = new DateTimeZone('America/El_Salvador');
$now = new DateTime('now', $timezone);

// Fecha a consultar, por defecto hoy
$filtro_fecha = isset($_GET['fecha']) ? $_GET['fecha'] : $now->format('Y-m-d');

// Formatear fecha para mostrar
$fechaObj = DateTime::createFromFormat('Y-m-d', $filtro_fecha);
$fecha_display = $fechaObj ? $fechaObj->format('d/m/Y') : $filtro_fecha;

// 1. Obtener totales del día
$stmtSum = $pdo->prepare("SELECT COUNT(*) as num_orders, COALESCE(SUM(subtotal),0) as total_subtotal, COALESCE(SUM(total),0) as total_revenue FROM ordenes WHERE order_date = ?");
$stmtSum->execute([$filtro_fecha]);
$resumen = $stmtSum->fetch(PDO::FETCH_ASSOC);

// 2. Obtener productos vendidos agrupados por nombre y masa
$stmtItems = $pdo->prepare("
    SELECT item_name, emoji, masa, SUM(qty) as total_qty, SUM(orden_items.total) as revenues
    FROM orden_items 
    JOIN ordenes ON orden_items.orden_id = ordenes.id
    WHERE ordenes.order_date = ?
    GROUP BY item_name, masa
    ORDER BY total_qty DESC
");
$stmtItems->execute([$filtro_fecha]);
$items_vendidos = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

// 3. Resumen por tipo de orden
$stmtTypesItems = $pdo->prepare("
    SELECT order_type, SUM(qty) as total_qty, SUM(orden_items.total) as total_revenue
    FROM orden_items 
    JOIN ordenes ON orden_items.orden_id = ordenes.id
    WHERE ordenes.order_date = ?
    GROUP BY order_type
");
$stmtTypesItems->execute([$filtro_fecha]);
$ventas_por_tipo = $stmtTypesItems->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario Diario - Comedor Señorial</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📊</text></svg>">
  <style>
    .inventory-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .inventory-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
        background: var(--bg-glass);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-subtle);
    }
    .inventory-header h2 {
        margin: 0;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .header-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .date-filter {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .date-filter input[type="date"] {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-sm);
        font-family: inherit;
        font-size: 0.9rem;
        background: var(--bg-glass);
        color: var(--text-primary);
    }
    .btn-filter {
        background: var(--accent-primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .btn-filter:hover { background: var(--accent-secondary); }
    
    .btn-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        background: var(--bg-glass);
        border: 1px solid var(--border-subtle);
        color: var(--text-primary);
        text-decoration: none;
        font-size: 1rem;
        transition: all 0.2s;
    }
    .btn-nav:hover {
        border-color: var(--accent-primary);
        background: rgba(214,54,139,0.1);
    }
    
    .btn-pdf {
        background: linear-gradient(135deg, #e05555, #c44040);
        color: white;
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(224,85,85,0.3);
    }
    .btn-pdf:hover {
        background: linear-gradient(135deg, #c44040, #a53030);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(224,85,85,0.4);
    }
    
    .btn-back {
        background: var(--bg-glass);
        color: var(--text-primary);
        border: 1px solid var(--border-subtle);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-back:hover { border-color: var(--accent-primary); color: var(--accent-primary); }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: var(--bg-glass);
        border: 1px solid var(--border-subtle);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        text-align: center;
        transition: all 0.2s;
    }
    .stat-card:hover {
        border-color: var(--border-accent);
        box-shadow: 0 0 20px rgba(214,54,139,0.1);
    }
    .stat-card__title {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .stat-card__value {
        font-family: 'Outfit', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .stat-card__icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .data-section {
        background: var(--bg-glass);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .data-section__header {
        background: var(--bg-glass-hover);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-subtle);
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .inventory-table {
        width: 100%;
        border-collapse: collapse;
    }
    .inventory-table th, .inventory-table td {
        padding: 0.85rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid var(--border-subtle);
    }
    .inventory-table th {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .inventory-table td {
        vertical-align: middle;
    }
    .item-name-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }
    .item-emoji {
        font-size: 1.4rem;
    }
    .masa-badge {
        font-size: 0.7rem;
        padding: 0.15rem 0.4rem;
        border-radius: 1rem;
        background: var(--bg-glass-hover);
        border: 1px solid var(--border-subtle);
        color: var(--text-secondary);
    }
    
    /* Order Cards */
    .order-card {
        background: var(--bg-glass);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        margin-bottom: 0.75rem;
        overflow: hidden;
        transition: all 0.2s;
    }
    .order-card:hover { border-color: var(--border-accent); }
    .order-card__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: rgba(214,54,139,0.08);
        border-bottom: 1px solid var(--border-subtle);
    }
    .order-card__num {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: var(--accent-primary);
    }
    .order-card__time { color: var(--text-secondary); font-size: 0.85rem; }
    .order-card__total {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: var(--accent-green);
        font-size: 1.1rem;
    }
    .order-card__body { padding: 0.75rem 1rem; }
    .order-card__customer { color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem; }
    .order-card__items { font-size: 0.85rem; }
    .order-card__items table { width: 100%; }
    .order-card__items td { padding: 0.3rem 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.03); }
    .badge-type {
        display: inline-block;
        padding: 0.1rem 0.4rem;
        border-radius: 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(214,54,139,0.1);
        color: var(--accent-gold);
        border: 1px solid rgba(214,54,139,0.2);
    }
    
    .no-data {
        text-align: center;
        padding: 2rem;
        color: var(--text-muted);
        font-size: 0.95rem;
    }
  </style>
</head>

<body>

  <header class="header">
    <div class="header__brand">
      <img src="img/logo.png" alt="Comedor Señorial" class="header__logo">
      <div>
        <h1 class="header__title">Comedor Señorial</h1>
        <span class="header__subtitle">Inventario de Ventas</span>
      </div>
    </div>
    <div class="header__datetime">
      <a href="index.php" class="btn-back">⬅️ Volver a Órdenes</a>
    </div>
  </header>

  <main class="inventory-container">
    
    <div class="inventory-header">
        <h2>📊 Resumen del Día — <?= $fecha_display ?></h2>
        <div class="header-controls">
            <div class="date-filter">
                <?php
                    $prevDay = (new DateTime($filtro_fecha))->modify('-1 day')->format('Y-m-d');
                    $nextDay = (new DateTime($filtro_fecha))->modify('+1 day')->format('Y-m-d');
                    $isToday = $filtro_fecha === $now->format('Y-m-d');
                ?>
                <a href="?fecha=<?= $prevDay ?>" class="btn-nav" title="Día anterior">◀️</a>
                <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>" max="<?= $now->format('Y-m-d') ?>" onchange="window.location.href='?fecha='+this.value">
                <?php if (!$isToday): ?>
                    <a href="?fecha=<?= $nextDay ?>" class="btn-nav" title="Día siguiente">▶️</a>
                <?php endif; ?>
                <?php if (!$isToday): ?>
                    <a href="?fecha=<?= $now->format('Y-m-d') ?>" class="btn-filter">📅 Hoy</a>
                <?php endif; ?>
            </div>
            <a href="reporte_pdf.php?fecha=<?= urlencode($filtro_fecha) ?>" class="btn-pdf" target="_blank">
                📄 Generar PDF del Día
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__icon">🧾</div>
            <div class="stat-card__title">Órdenes Generadas</div>
            <div class="stat-card__value"><?= $resumen['num_orders'] ?: '0' ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon">🍽️</div>
            <div class="stat-card__title">Items Vendidos</div>
            <div class="stat-card__value">
                <?php
                $totItems = 0;
                foreach($items_vendidos as $it) $totItems += $it['total_qty'];
                echo $totItems;
                ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon">💵</div>
            <div class="stat-card__title">Ingresos del Día</div>
            <div class="stat-card__value">$<?= number_format($resumen['total_revenue'] ?? 0, 2) ?></div>
        </div>
    </div>

    <!-- Desglose por Producto -->
    <div class="data-section">
        <div class="data-section__header">
            🛒 Productos Vendidos
        </div>
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                    <th>Ingreso Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items_vendidos)): ?>
                <tr><td colspan="3" class="no-data">No hay ventas registradas para esta fecha.</td></tr>
                <?php else: ?>
                    <?php foreach ($items_vendidos as $item): ?>
                    <tr>
                        <td>
                            <div class="item-name-cell">
                                <span class="item-emoji"><?= htmlspecialchars($item['emoji']) ?></span>
                                <span>
                                    <?= htmlspecialchars($item['item_name']) ?>
                                    <?php if ($item['masa']): ?>
                                        <span class="masa-badge"><?= $item['masa'] === 'maiz' ? '🌽 Maíz' : '🍚 Arroz' ?></span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </td>
                        <td style="font-weight: 700;">x<?= htmlspecialchars($item['total_qty']) ?></td>
                        <td>$<?= number_format($item['revenues'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Desglose por Tipo de Orden -->
    <div class="data-section">
        <div class="data-section__header">🏷️ Ventas por Tipo de Orden</div>
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Tipo de Orden</th>
                    <th>Cantidad de Items</th>
                    <th>Ingreso Generado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ventas_por_tipo)): ?>
                <tr><td colspan="3" class="no-data">No hay datos.</td></tr>
                <?php else: ?>
                    <?php foreach ($ventas_por_tipo as $tipo): ?>
                    <tr>
                        <td style="font-weight: 500; font-size:1.05rem;"><?= htmlspecialchars($tipo['order_type']) ?></td>
                        <td style="font-weight: 700;">x<?= htmlspecialchars($tipo['total_qty']) ?></td>
                        <td>$<?= number_format($tipo['total_revenue'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

  </main>
</body>
</html>
