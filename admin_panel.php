<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$db_path = __DIR__ . '/pupuseria.db';
$db = new PDO("sqlite:" . $db_path);
$stmt = $db->query("SELECT * FROM productos ORDER BY categoria, nombre");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Menú</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body { padding: 2rem; background-color: var(--bg-primary); }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .admin-header h1 { color: var(--accent-gold); }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; font-family: var(--font-family); }
        .btn-primary { background: var(--accent-primary); color: white; }
        .btn-danger { background: var(--accent-red); color: white; text-decoration: none; }
        
        .table-wrapper { background: var(--bg-secondary); border-radius: 12px; padding: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; color: var(--text-primary); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border-subtle); }
        th { color: var(--accent-gold); }
        .img-preview { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; }
        
        /* Modal Styles */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; justify-content: center; align-items: center; }
        .modal { background: var(--bg-secondary); padding: 25px; border-radius: 12px; width: 100%; max-width: 500px; color: var(--text-primary); }
        .modal h2 { margin-bottom: 20px; color: var(--accent-gold); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: var(--text-secondary); }
        .form-group input, .form-group select { width: 100%; padding: 10px; background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary); border-radius: 6px; box-sizing: border-box;}
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="admin-header">
        <h1>⚙️ Panel de Administración del Menú</h1>
        <div>
            <button class="btn btn-primary" onclick="openModal()">+ Agregar Producto</button>
            <a href="logout.php" class="btn btn-danger" style="margin-left: 10px;">Cerrar Sesión</a>
            <a href="index.php" class="btn btn-primary" style="margin-left: 10px; background:var(--accent-green)">Ver Punto de Venta</a>
        </div>
    </div>

    <div class="table-wrapper">
        <table id="productosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categoría</th>
                    <th>Imagen/Emoji</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($productos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['categoria']) . ($p['subcategoria'] ? ' ('.$p['subcategoria'].')' : '') ?></td>
                    <td>
                        <?php if($p['imagen_url']): ?>
                            <img src="<?= htmlspecialchars($p['imagen_url']) ?>" class="img-preview">
                        <?php else: ?>
                            <span style="font-size: 1.5rem;"><?= htmlspecialchars($p['emoji']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td>$<?= number_format($p['precio'], 2) ?></td>
                    <td>
                        <button class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;" onclick='editProduct(<?= json_encode($p) ?>)'>Editar</button>
                        <button class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem; margin-left: 5px;" onclick="deleteProduct('<?= htmlspecialchars($p['id']) ?>')">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="productModal">
        <div class="modal">
            <h2 id="modalTitle">Agregar Producto</h2>
            <form id="productForm" onsubmit="saveProduct(event)">
                <input type="hidden" id="original_id" name="original_id">
                <input type="hidden" id="action" name="action" value="save">
                
                <div class="form-group">
                    <label>ID Único (ej. t17, bf60)</label>
                    <input type="text" id="p_id" name="id" required>
                </div>
                
                <div class="form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" id="p_nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label>Categoría</label>
                    <select id="p_categoria" name="categoria" required onchange="toggleSubcategoria()">
                        <option value="tradicionales">Tradicionales</option>
                        <option value="especiales">Especiales</option>
                        <option value="tamalesYMas">Tamales y Más</option>
                        <option value="bebidasFrias">Bebidas Frías</option>
                        <option value="bebidasCalientes">Bebidas Calientes</option>
                    </select>
                </div>

                <div class="form-group" id="subcategoria_group" style="display:none;">
                    <label>Subcategoría (solo para bebidas frías)</label>
                    <select id="p_subcategoria" name="subcategoria">
                        <option value="">Ninguna</option>
                        <option value="jugos y frescos">Jugos y Frescos</option>
                        <option value="gaseosas">Gaseosas</option>
                        <option value="energéticas">Energéticas</option>
                        <option value="cervezas">Cervezas</option>
                        <option value="agua">Agua</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Precio ($)</label>
                    <input type="number" step="0.01" id="p_precio" name="precio" required>
                </div>
                
                <div class="form-group">
                    <label>Emoji (Opcional, si no hay imagen)</label>
                    <input type="text" id="p_emoji" name="emoji">
                </div>

                <div class="form-group">
                    <label>Subir Imagen (Opcional, desde tu computadora)</label>
                    <input type="file" id="p_imagen" name="imagen" accept="image/*">
                    <small style="color:var(--text-muted); display:block; margin-top:5px;">Si subes una imagen, se recortará automáticamente y reemplazará al emoji.</small>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-danger" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('productModal');
        const form = document.getElementById('productForm');

        function toggleSubcategoria() {
            const cat = document.getElementById('p_categoria').value;
            document.getElementById('subcategoria_group').style.display = (cat === 'bebidasFrias') ? 'block' : 'none';
        }

        function openModal() {
            document.getElementById('modalTitle').textContent = 'Agregar Nuevo Producto';
            form.reset();
            document.getElementById('original_id').value = '';
            toggleSubcategoria();
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function editProduct(p) {
            document.getElementById('modalTitle').textContent = 'Editar Producto';
            document.getElementById('original_id').value = p.id;
            document.getElementById('p_id').value = p.id;
            document.getElementById('p_nombre').value = p.nombre;
            document.getElementById('p_categoria').value = p.categoria;
            document.getElementById('p_subcategoria').value = p.subcategoria || '';
            document.getElementById('p_precio').value = p.precio;
            document.getElementById('p_emoji').value = p.emoji || '';
            toggleSubcategoria();
            modal.style.display = 'flex';
        }

        async function saveProduct(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const btnSave = document.getElementById('btnSave');
            btnSave.disabled = true;
            btnSave.textContent = 'Guardando...';

            try {
                const res = await fetch('guardar_producto.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                    btnSave.disabled = false;
                    btnSave.textContent = 'Guardar';
                }
            } catch (err) {
                alert('Error de conexión');
                console.error(err);
                btnSave.disabled = false;
                btnSave.textContent = 'Guardar';
            }
        }

        async function deleteProduct(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                try {
                    const res = await fetch('eliminar_producto.php?id=' + id);
                    const data = await res.json();
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                } catch (err) {
                    alert('Error de conexión');
                }
            }
        }
    </script>
</body>
</html>
