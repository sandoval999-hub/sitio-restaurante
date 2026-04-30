<?php
session_start();

$PASSWORD_CORRECTA = "Comedor1";
$error_login = "";

if (isset($_POST['inventario_pass'])) {
    if ($_POST['inventario_pass'] === $PASSWORD_CORRECTA) {
        $_SESSION['auth_inventario'] = true;
        header("Location: inventario.php");
        exit;
    } else {
        $error_login = "Contraseña incorrecta. Inténtelo de nuevo.";
    }
}

// LOGOUT LOGIC
if (isset($_GET['logout'])) {
    unset($_SESSION['auth_inventario']);
    header("Location: inventario.php");
    exit;
}

// SI NO ESTA AUTORIZADO, MOSTRAR LOGIN
if (!isset($_SESSION['auth_inventario']) || $_SESSION['auth_inventario'] !== true) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Restringido - Inventario</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: #f7f3ed;
            background-image: radial-gradient(#d4c8bc 1px, transparent 1px);
            background-size: 20px 20px;
            font-family: 'Outfit', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(74, 62, 53, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }
        .login-box h2 {
            color: #1a1218;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            font-weight: 800;
        }
        .login-box p {
            color: #7a6e65;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .login-box input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 2px solid #e2d9cd;
            border-radius: 0.5rem;
            font-family: inherit;
            font-size: 1rem;
            margin-bottom: 1rem;
            outline: none;
            transition: all 0.3s;
        }
        .login-box input:focus {
            border-color: #d6368b;
            box-shadow: 0 0 0 3px rgba(214, 54, 139, 0.1);
        }
        .login-box button {
            width: 100%;
            padding: 0.85rem;
            background: #d6368b;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .login-box button:hover {
            background: #b52c74;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(214, 54, 139, 0.3);
        }
        .error-msg {
            color: #e05555;
            font-size: 0.85rem;
            margin-top: 1rem;
            font-weight: 600;
        }
        .btn-back {
            display: inline-block;
            margin-top: 1.5rem;
            color: #7a6e65;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .btn-back:hover {
            color: #1a1218;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔒 Inventario</h2>
        <p>Ingrese la contraseña para acceder</p>
        <form method="POST">
            <input type="password" name="inventario_pass" placeholder="Contraseña..." required autofocus>
            <button type="submit">Entrar</button>
        </form>
        <?php if ($error_login): ?>
            <div class="error-msg"><?= $error_login ?></div>
        <?php endif; ?>
        <a href="index.php" class="btn-back">← Volver al inicio</a>
    </div>
</body>
</html>
<?php
    exit; // Detener la ejecución si no está logeado
}

// SI ESTA AUTORIZADO, CARGA EL RESTO DE LA PAGINA
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

    <!-- Órdenes del Día con opción de Eliminar -->
    <?php
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    
    if ($buscar !== '') {
        $stmtOrdenes = $pdo->prepare("SELECT * FROM ordenes WHERE customer_name LIKE ? OR order_number LIKE ? ORDER BY id DESC");
        $stmtOrdenes->execute(['%' . $buscar . '%', '%' . $buscar . '%']);
        $titulo_ordenes = "Resultados de Búsqueda para '$buscar'";
    } else {
        $stmtOrdenes = $pdo->prepare("SELECT * FROM ordenes WHERE order_date = ? ORDER BY id DESC");
        $stmtOrdenes->execute([$filtro_fecha]);
        $titulo_ordenes = "Órdenes del Día";
    }
    $ordenes = $stmtOrdenes->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="data-section">
        <div class="data-section__header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <span>📋 <?= $titulo_ordenes ?> (<?= count($ordenes) ?>)</span>
            <form method="GET" action="inventario.php" class="bebida-search-wrapper" style="margin-left: 0; min-width: 250px;">
                <input type="hidden" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>">
                <span class="bebida-search-icon">🔍</span>
                <input type="text" name="buscar" class="bebida-search-input" placeholder="Buscar por nombre u orden..." value="<?= htmlspecialchars($buscar) ?>" autocomplete="off" style="padding-right: 30px;">
                <?php if ($buscar !== ''): ?>
                    <a href="inventario.php?fecha=<?= htmlspecialchars($filtro_fecha) ?>" class="bebida-search-clear" style="text-decoration:none; display:flex;">✕</a>
                <?php else: ?>
                    <button type="submit" style="display: none;"></button>
                <?php endif; ?>
            </form>
        </div>
        <div style="padding: 1rem;">
            <?php if (empty($ordenes)): ?>
                <div class="no-data">No hay órdenes registradas para esta fecha.</div>
            <?php else: ?>
                <?php foreach ($ordenes as $orden): ?>
                    <div class="order-card" id="orden-<?= $orden['id'] ?>">
                        <div class="order-card__header">
                            <span>
                                <span class="order-card__num"><?= htmlspecialchars($orden['order_number']) ?></span>
                                <span class="order-card__time"> — <?= htmlspecialchars($orden['order_date'] ?? '') ?> <?= htmlspecialchars($orden['order_time']) ?></span>
                            </span>
                        <span style="display:flex; align-items:center; gap:0.75rem;">
                            <span class="order-card__total">$<?= number_format($orden['total'], 2) ?></span>
                            <button class="btn-details-order" onclick="verDetallesOrden(<?= $orden['id'] ?>)" title="Ver detalles">📋</button>
                            <button class="btn-delete-order" onclick="eliminarOrden(<?= $orden['id'] ?>, '<?= htmlspecialchars($orden['order_number']) ?>', <?= $orden['total'] ?>)" title="Eliminar orden">🗑️</button>
                        </span>
                    </div>
                    <div class="order-card__body">
                        <div class="order-card__customer">
                            👤 <?= htmlspecialchars($orden['customer_name']) ?>
                            <?= $orden['customer_phone'] ? ' &nbsp;📱 ' . htmlspecialchars($orden['customer_phone']) : '' ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

  </main>

  <!-- Delete Confirmation Modal -->
  <div class="modal-overlay" id="deleteConfirmModal">
      <div class="admin-modal-box" style="text-align: center; max-width: 400px;">
          <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
          <h2 style="color: var(--text-primary); margin-bottom: 1rem;">¿Estás seguro?</h2>
          <p id="deleteConfirmText" style="color: var(--text-muted); margin-bottom: 2rem;"></p>
          <div class="modal-actions" style="justify-content: center; gap: 1rem;">
              <button class="btn btn-secondary" onclick="closeDeleteModal()" style="background: var(--bg-secondary); color: var(--text-primary); padding: 0.8rem 2rem;">No, cancelar</button>
              <button class="btn btn-danger" id="btnConfirmDelete" style="background: #e05555; padding: 0.8rem 2rem;">Sí, eliminar</button>
          </div>
      </div>
  </div>

  <style>
    .btn-details-order {
        background: rgba(91, 143, 212, 0.15);
        border: 1px solid rgba(91, 143, 212, 0.3);
        color: var(--accent-blue);
        padding: 0.3rem 0.6rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .btn-details-order:hover {
        background: var(--accent-blue);
        color: white;
        transform: scale(1.05);
    }
    .btn-delete-order {
        background: rgba(224, 85, 85, 0.15);
        border: 1px solid rgba(224, 85, 85, 0.3);
        color: #e05555;
        padding: 0.3rem 0.6rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .btn-delete-order:hover {
        background: #e05555;
        color: white;
        transform: scale(1.05);
    }
    .order-card.deleting {
        opacity: 0.4;
        transform: scale(0.98);
        transition: all 0.3s;
    }
  </style>

  <script>
  let ordenActualEditId = null;

  let orderToDeleteId = null;

  function eliminarOrden(id, orderNum, total) {
      orderToDeleteId = id;
      document.getElementById('deleteConfirmText').innerHTML = `¿Deseas eliminar la orden <strong>${orderNum}</strong>?<br><br>Monto: $${total.toFixed(2)}<br><small style="color:#e05555;">Esta acción restará el monto de los ingresos del día.</small>`;
      document.getElementById('deleteConfirmModal').classList.add('visible');
  }

  function closeDeleteModal() {
      document.getElementById('deleteConfirmModal').classList.remove('visible');
      orderToDeleteId = null;
  }

  document.getElementById('btnConfirmDelete').addEventListener('click', async () => {
      if (!orderToDeleteId) return;
      const id = orderToDeleteId;
      closeDeleteModal();

      const card = document.getElementById('orden-' + id);
      if (card) card.classList.add('deleting');

      try {
          const response = await fetch('eliminar_orden.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ orden_id: id })
          });

          const data = await response.json();

          if (data.success) {
              window.location.reload();
          } else {
              alert('Error: ' + (data.error || 'No se pudo eliminar'));
              if (card) card.classList.remove('deleting');
          }
      } catch (err) {
          alert('Error de conexión al eliminar la orden.');
          if (card) card.classList.remove('deleting');
      }
  });

  async function verDetallesOrden(id) {
      ordenActualEditId = id;
      try {
          const res = await fetch(`obtener_orden.php?id=${id}`);
          const data = await res.json();
          if (data.success) {
              renderTicketEnInventario(data.order);
              document.getElementById('modalDetalles').classList.add('visible');
              document.body.style.overflow = 'hidden';
          } else {
              alert('Error al obtener detalles: ' + data.error);
          }
      } catch (e) {
          alert('Error de conexión');
      }
  }

  function cerrarModalDetalles() {
      document.getElementById('modalDetalles').classList.remove('visible');
      document.body.style.overflow = '';
      ordenActualEditId = null;
  }

  function editarOrden() {
      if (ordenActualEditId) {
          window.location.href = `index.php?edit_order=${ordenActualEditId}`;
      }
  }

  function imprimirTicketInventario() {
      window.print();
  }

  function renderTicketEnInventario(data) {
      const typeGroups = {};
      for (const item of data.items) {
          const type = item.orderType || 'Comer Aquí';
          if (!typeGroups[type]) typeGroups[type] = [];
          typeGroups[type].push(item);
      }
      
      const usedTypes = Object.keys(typeGroups);
      const hasMultipleTypes = usedTypes.length > 1;

      let html = `
        <div class="ticket">
          <div class="ticket__header">
            <img class="ticket__logo-img" src="img/logo.png" alt="Comedor Señorial" />
            <div class="ticket__title">Comedor Señorial</div>
            <div class="ticket__info">
              Pupusería — Sistema de Órdenes<br>
              ${data.date} — ${data.time}
            </div>
          </div>
          <div class="ticket__customer">
            <span class="ticket__customer-name">👤 ${data.customerName}</span>
            ${data.customerPhone ? `<span class="ticket__customer-phone">📱 ${data.customerPhone}</span>` : ''}
          </div>
          ${data.orderNumber && data.orderNumber.startsWith('ORD') ? '' : `
          <div class="ticket__llevar-badge">
            Número de Orden: <strong>#${data.orderNumber.replace('Orden #', '')}</strong>
          </div>
          `}
      `;

      if (!hasMultipleTypes) {
          html += `<div class="ticket__order-type">"${usedTypes[0]}"</div>`;
      }

      for (const type of usedTypes) {
          const groupItems = typeGroups[type];
          if (hasMultipleTypes) {
              html += `<div class="ticket__type-header">"${type.toUpperCase()}"</div>`;
          }

          html += `<div class="ticket__items">`;
          groupItems.forEach(item => {
              html += `
                <div class="ticket__item">
                  <span class="ticket__item-qty" style="font-size: 1.4em; font-weight: 900; min-width: 42px; display: inline-block;">x${item.qty}</span>
                  <span class="ticket__item-name" style="font-size: 1.15em; font-weight: bold;">${item.name}${item.masa ? ` (${item.masa === 'maiz' ? 'Maíz' : 'Arroz'})` : ''}</span>
                  <span class="ticket__item-price" style="font-size: 1.15em;">$${(item.price * item.qty).toFixed(2)}</span>
                </div>
              `;
          });
          html += `</div>`;

          if (hasMultipleTypes) {
              html += `<hr class="ticket__divider ticket__divider--light">`;
          }
      }

      html += `
          <hr class="ticket__divider">
          <div class="ticket__totals">
            <div class="ticket__total-row">
              <span>Subtotal</span>
              <span>$${data.subtotal.toFixed(2)}</span>
            </div>
            <div class="ticket__total-row grand">
              <span>TOTAL</span>
              <span>$${data.total.toFixed(2)}</span>
            </div>
          </div>
          <div class="ticket__footer">
            ¡Gracias por su compra en el<br>
            <strong>Comedor Señorial</strong>
          </div>
        </div>
      `;

      document.getElementById('ticketDetalleBody').innerHTML = html;
  }
  </script>

  <!-- Modal Detalles -->
  <div class="modal-overlay" id="modalDetalles">
    <div class="modal-content" id="modalContentDetalles">
      <div id="ticketDetalleBody"></div>
      <div class="modal-actions" style="margin-top: 1rem; display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
        <button onclick="imprimirTicketInventario()" style="background: var(--accent-primary); color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight: bold; cursor: pointer;">🖨️ Imprimir</button>
        <button onclick="editarOrden()" style="background: #e6a822; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight: bold; cursor: pointer;">✏️ Editar</button>
        <button onclick="cerrarModalDetalles()" style="background: #ccc; color: #333; border: none; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight: bold; cursor: pointer;">↩ Volver</button>
      </div>
    </div>
  </div>

  <style>
    @media print {
        body * {
            visibility: hidden;
        }
        #modalDetalles, #modalDetalles * {
            visibility: visible;
        }
        #modalDetalles {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: transparent;
        }
        #modalContentDetalles {
            box-shadow: none;
            transform: none !important;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100%;
        }
        .modal-actions {
            display: none !important;
        }
    }
  </style>

</body>
</html>
