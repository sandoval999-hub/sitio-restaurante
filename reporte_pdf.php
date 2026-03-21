<?php
/**
 * ============================================
 * GENERADOR DE PDF - REPORTE DIARIO DE ÓRDENES
 * Genera un PDF con todas las órdenes del día
 * ============================================
 */
require_once __DIR__ . '/db_config.php';

$timezone = new DateTimeZone('America/El_Salvador');
$now = new DateTime('now', $timezone);
$filtro_fecha = isset($_GET['fecha']) ? $_GET['fecha'] : $now->format('Y-m-d');

// Formatear fecha para mostrar
$fechaObj = DateTime::createFromFormat('Y-m-d', $filtro_fecha);
$fecha_display = $fechaObj ? $fechaObj->format('d/m/Y') : $filtro_fecha;

// 1. Resumen general
$stmtSum = $pdo->prepare("SELECT COUNT(*) as num_orders, COALESCE(SUM(subtotal),0) as total_subtotal, COALESCE(SUM(total),0) as total_revenue FROM ordenes WHERE order_date = ?");
$stmtSum->execute([$filtro_fecha]);
$resumen = $stmtSum->fetch(PDO::FETCH_ASSOC);

// 2. Todas las órdenes del día con sus items
$stmtOrdenes = $pdo->prepare("SELECT * FROM ordenes WHERE order_date = ? ORDER BY id ASC");
$stmtOrdenes->execute([$filtro_fecha]);
$ordenes = $stmtOrdenes->fetchAll(PDO::FETCH_ASSOC);

$stmtAllItems = $pdo->prepare("SELECT * FROM orden_items WHERE orden_id IN (SELECT id FROM ordenes WHERE order_date = ?) ORDER BY orden_id ASC");
$stmtAllItems->execute([$filtro_fecha]);
$allItems = $stmtAllItems->fetchAll(PDO::FETCH_ASSOC);

// Agrupar items por orden
$itemsByOrden = [];
foreach ($allItems as $item) {
    $itemsByOrden[$item['orden_id']][] = $item;
}

// 3. Resumen por producto
$stmtProd = $pdo->prepare("
    SELECT item_name, masa, SUM(qty) as total_qty, SUM(orden_items.total) as revenues
    FROM orden_items JOIN ordenes ON orden_items.orden_id = ordenes.id
    WHERE ordenes.order_date = ?
    GROUP BY item_name, masa ORDER BY total_qty DESC
");
$stmtProd->execute([$filtro_fecha]);
$productos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

// 4. Resumen por tipo de orden
$stmtTipos = $pdo->prepare("
    SELECT order_type, SUM(qty) as total_qty, SUM(orden_items.total) as total_revenue
    FROM orden_items JOIN ordenes ON orden_items.orden_id = ordenes.id
    WHERE ordenes.order_date = ?
    GROUP BY order_type
");
$stmtTipos->execute([$filtro_fecha]);
$tipos = $stmtTipos->fetchAll(PDO::FETCH_ASSOC);

// ============================================
// GENERAR HTML PARA PDF (se usa window.print)
// ============================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario - <?= $fecha_display ?> - Comedor Señorial</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap');
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f0ec;
            color: #1a1218;
            padding: 2rem;
        }
        
        .report {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .report-header {
            background: linear-gradient(135deg, #1a1218 0%, #2d232a 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .report-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }
        .report-header .subtitle {
            font-size: 0.95rem;
            color: #b5a99e;
        }
        .report-header .date-badge {
            display: inline-block;
            margin-top: 0.75rem;
            padding: 0.5rem 1.5rem;
            background: rgba(214, 54, 139, 0.2);
            border: 1px solid rgba(214, 54, 139, 0.4);
            border-radius: 2rem;
            color: #f06aaf;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .stats-row {
            display: flex;
            gap: 1rem;
            padding: 1.5rem 2rem;
            background: #faf8f6;
            border-bottom: 1px solid #eee;
        }
        .stat-box {
            flex: 1;
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            border: 1px solid #eee;
        }
        .stat-box .label { font-size: 0.8rem; color: #7a6e65; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
        .stat-box .value { font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 700; color: #1a1218; }
        
        .section { padding: 1.5rem 2rem; border-bottom: 1px solid #eee; }
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1218;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #d6368b;
            display: inline-block;
        }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 0.5rem; }
        th { text-align: left; font-size: 0.8rem; color: #7a6e65; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.5rem 0.75rem; border-bottom: 2px solid #eee; }
        td { padding: 0.6rem 0.75rem; border-bottom: 1px solid #f0ece8; font-size: 0.9rem; }
        tr:last-child td { border-bottom: none; }
        
        .order-card {
            background: #faf8f6;
            border: 1px solid #eee;
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: #1a1218;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .order-card-header .order-num { color: #f06aaf; }
        .order-card-header .order-total { color: #6db56d; font-size: 1rem; }
        .order-card-body { padding: 0.75rem 1rem; }
        .order-card-body .customer { color: #7a6e65; font-size: 0.85rem; margin-bottom: 0.5rem; }
        .order-card-body table td { font-size: 0.85rem; padding: 0.4rem 0.5rem; }
        
        .badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; }
        .badge-masa { background: #f0ece8; color: #7a6e65; border: 1px solid #ddd; }
        .badge-type { background: rgba(214, 54, 139, 0.1); color: #d6368b; border: 1px solid rgba(214, 54, 139, 0.2); }
        
        .actions {
            padding: 1.5rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-print { background: #d6368b; color: white; }
        .btn-print:hover { background: #c42a7a; }
        .btn-back { background: #1a1218; color: white; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-back:hover { background: #2d232a; }
        
        .text-right { text-align: right; }
        .text-bold { font-weight: 700; }
        
        @media print {
            body { padding: 0; background: white; }
            .report { box-shadow: none; border-radius: 0; }
            .actions { display: none !important; }
            .order-card { break-inside: avoid; }
            .section { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="report">
        <!-- Header -->
        <div class="report-header">
            <h1>Comedor Señorial</h1>
            <div class="subtitle">Reporte Diario de Ventas</div>
            <div class="date-badge"><?= $fecha_display ?></div>
        </div>
        
        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="label">Órdenes</div>
                <div class="value"><?= $resumen['num_orders'] ?: 0 ?></div>
            </div>
            <div class="stat-box">
                <div class="label">Items Vendidos</div>
                <div class="value"><?php
                    $tot = 0;
                    foreach($productos as $p) $tot += $p['total_qty'];
                    echo $tot;
                ?></div>
            </div>
            <div class="stat-box">
                <div class="label">Ingresos Totales</div>
                <div class="value">$<?= number_format($resumen['total_revenue'] ?? 0, 2) ?></div>
            </div>
        </div>
        
        <!-- Resumen Productos -->
        <div class="section">
            <div class="section-title">Resumen de Productos Vendidos</div>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th class="text-right">Ingreso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                    <tr><td colspan="3" style="text-align:center; color:#999; padding:1rem;">Sin ventas registradas</td></tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($p['item_name']) ?>
                                <?php if ($p['masa']): ?>
                                    <span class="badge badge-masa"><?= $p['masa'] === 'maiz' ? 'Maíz' : 'Arroz' ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-bold">x<?= $p['total_qty'] ?></td>
                            <td class="text-right">$<?= number_format($p['revenues'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Resumen por Tipo -->
        <div class="section">
            <div class="section-title">Ventas por Tipo de Orden</div>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Items</th>
                        <th class="text-right">Ingreso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tipos)): ?>
                    <tr><td colspan="3" style="text-align:center; color:#999; padding:1rem;">Sin datos</td></tr>
                    <?php else: ?>
                        <?php foreach ($tipos as $t): ?>
                        <tr>
                            <td class="text-bold"><?= htmlspecialchars($t['order_type']) ?></td>
                            <td>x<?= $t['total_qty'] ?></td>
                            <td class="text-right">$<?= number_format($t['total_revenue'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Detalle de Órdenes -->
        <div class="section">
            <div class="section-title">Detalle de Todas las Órdenes (<?= count($ordenes) ?>)</div>
            <?php if (empty($ordenes)): ?>
                <p style="color:#999; text-align:center; padding:1rem;">No hay órdenes para esta fecha.</p>
            <?php else: ?>
                <?php foreach ($ordenes as $orden): ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <span>
                            <span class="order-num"><?= htmlspecialchars($orden['order_number']) ?></span>
                            &nbsp;—&nbsp; <?= htmlspecialchars($orden['order_time']) ?>
                        </span>
                        <span class="order-total">$<?= number_format($orden['total'], 2) ?></span>
                    </div>
                    <div class="order-card-body">
                        <div class="customer">
                            👤 <?= htmlspecialchars($orden['customer_name']) ?>
                            <?= $orden['customer_phone'] ? ' &nbsp;📱 ' . htmlspecialchars($orden['customer_phone']) : '' ?>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Cant.</th>
                                    <th>Tipo</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $items = $itemsByOrden[$orden['id']] ?? [];
                                foreach ($items as $item):
                                ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($item['item_name']) ?>
                                        <?php if ($item['masa']): ?>
                                            <span class="badge badge-masa"><?= $item['masa'] === 'maiz' ? 'Maíz' : 'Arroz' ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-bold">x<?= $item['qty'] ?></td>
                                    <td><span class="badge badge-type"><?= htmlspecialchars($item['order_type']) ?></span></td>
                                    <td class="text-right">$<?= number_format($item['total'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Actions (hidden on print) -->
        <div class="actions" id="pdfActions">
            <button class="btn btn-print" id="btnDownloadPdf" onclick="downloadPDF()">📥 Descargar PDF</button>
            <a href="inventario.php?fecha=<?= urlencode($filtro_fecha) ?>" class="btn btn-back">⬅️ Volver al Inventario</a>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
    async function downloadPDF() {
        const btn = document.getElementById('btnDownloadPdf');
        const actions = document.getElementById('pdfActions');
        btn.textContent = '⏳ Generando PDF...';
        btn.disabled = true;
        
        // Hide buttons temporarily
        actions.style.display = 'none';
        
        const report = document.querySelector('.report');
        
        try {
            const canvas = await html2canvas(report, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff'
            });
            
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            
            const pdfWidth = 210; // A4 width in mm
            const imgWidth = pdfWidth - 20; // 10mm margin each side
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            const pdf = new jsPDF('p', 'mm', 'a4');
            let yPos = 10;
            const pageHeight = 287; // A4 height minus margins
            
            // If content fits on one page
            if (imgHeight <= pageHeight) {
                pdf.addImage(imgData, 'PNG', 10, yPos, imgWidth, imgHeight);
            } else {
                // Multi-page: slice the image across pages
                let remainingHeight = imgHeight;
                while (remainingHeight > 0) {
                    pdf.addImage(imgData, 'PNG', 10, yPos, imgWidth, imgHeight);
                    remainingHeight -= pageHeight;
                    if (remainingHeight > 0) {
                        pdf.addPage();
                        yPos = -(imgHeight - remainingHeight) + 10;
                    }
                }
            }
            
            pdf.save('reporte_<?= $filtro_fecha ?>.pdf');
        } catch (err) {
            alert('Error al generar PDF: ' + err.message);
        }
        
        // Restore buttons
        actions.style.display = 'flex';
        btn.textContent = '📥 Descargar PDF';
        btn.disabled = false;
    }
    </script>
</body>
</html>
