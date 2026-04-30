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
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .admin-header h1 { color: var(--accent-gold); font-size: 1.5rem; }
        .admin-header-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; font-family: 'Inter', sans-serif; transition: all 0.2s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: var(--accent-primary); color: white; }
        .btn-danger { background: var(--accent-red); color: white; text-decoration: none; }
        
        .table-wrapper { background: var(--bg-secondary); border-radius: 12px; padding: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; color: var(--text-primary); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border-subtle); }
        th { color: var(--accent-gold); }
        .img-preview { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; }
        
        /* ============================
           MODAL STYLES - Override main CSS 
           which sets opacity:0 + visibility:hidden
           on .modal-overlay class
           ============================ */
        #productModal {
            display: none !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: rgba(0,0,0,0.75) !important;
            backdrop-filter: blur(8px) !important;
            z-index: 9999 !important;
            justify-content: center !important;
            align-items: center !important;
            /* Override the main CSS that hides .modal-overlay */
            opacity: 1 !important;
            visibility: visible !important;
        }
        #productModal.admin-modal-open {
            display: flex !important;
        }
        .admin-modal-box {
            background: var(--bg-secondary);
            padding: 30px;
            border-radius: 16px;
            width: 100%;
            max-width: 550px;
            color: var(--text-primary);
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            animation: adminModalIn 0.3s ease;
        }
        @keyframes adminModalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .admin-modal-box h2 { margin-bottom: 20px; color: var(--accent-gold); font-size: 1.3rem; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: var(--text-secondary); font-size: 0.9rem; font-weight: 600; }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            background: var(--bg-primary);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 2px rgba(214, 54, 139, 0.15);
        }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }

        /* Emoji Picker */
        .emoji-field-wrapper { display: flex; align-items: center; gap: 10px; }
        .emoji-field-wrapper input { flex: 1; }
        .emoji-preview { font-size: 2rem; min-width: 40px; text-align: center; }
        .btn-emoji-picker {
            padding: 8px 14px;
            background: var(--bg-glass);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        .btn-emoji-picker:hover {
            border-color: var(--accent-primary);
            background: rgba(214, 54, 139, 0.1);
        }
        .emoji-picker-panel {
            display: none;
            background: var(--bg-primary);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 12px;
            margin-top: 8px;
            max-height: 200px;
            overflow-y: auto;
        }
        .emoji-picker-panel.open { display: block; }
        .emoji-picker-category { margin-bottom: 8px; }
        .emoji-picker-category-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
            display: block;
        }
        .emoji-picker-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 2px;
        }
        .emoji-btn {
            background: none;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: background 0.15s;
            line-height: 1;
        }
        .emoji-btn:hover {
            background: rgba(214, 54, 139, 0.15);
        }

        /* Image Upload */
        .file-upload-wrapper {
            position: relative;
        }
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: var(--bg-glass);
            border: 2px dashed var(--border-subtle);
            border-radius: 10px;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .file-upload-label:hover {
            border-color: var(--accent-primary);
            color: var(--accent-primary);
        }
        .file-upload-label .upload-icon { font-size: 1.5rem; }
        .file-upload-input { display: none; }
        .image-preview-container {
            display: none;
            margin-top: 10px;
            text-align: center;
            position: relative;
        }
        .image-preview-container.has-image { display: block; }
        .image-preview-thumb {
            max-width: 120px;
            max-height: 120px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid var(--border-subtle);
        }
        .image-preview-remove {
            position: absolute;
            top: -6px;
            right: calc(50% - 66px);
            background: var(--accent-red);
            color: white;
            border: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            font-size: 0.75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .current-image-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* URL field */
        .url-or-label {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin: 8px 0;
        }
    </style>
</head>
<body>

    <div class="admin-header">
        <h1>⚙️ Panel de Administración del Menú</h1>
        <div class="admin-header-actions">
            <button class="btn btn-primary" onclick="openModal()">+ Agregar Producto</button>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            <a href="index.php" class="btn btn-primary" style="background:var(--accent-green)">Ver Punto de Venta</a>
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
                        <button class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;" onclick='editProduct(<?= json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) ?>)'>Editar</button>
                        <button class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem; margin-left: 5px;" onclick="deleteProduct('<?= htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8') ?>')">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="productModal">
        <div class="admin-modal-box">
            <h2 id="modalTitle">Agregar Producto</h2>
            <form id="productForm" onsubmit="saveProduct(event)" enctype="multipart/form-data">
                <input type="hidden" id="original_id" name="original_id">
                <input type="hidden" id="action" name="action" value="save">
                
                <div class="form-group">
                    <label>ID Único (ej. t17, bf60)</label>
                    <input type="text" id="p_id" name="id" required placeholder="Ej: t17, e9, bf60, bc8">
                </div>
                
                <div class="form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" id="p_nombre" name="nombre" required placeholder="Ej: Pupusa de Huevo/Queso">
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
                    <input type="number" step="0.01" min="0.01" id="p_precio" name="precio" required placeholder="0.95">
                </div>
                
                <div class="form-group">
                    <label>Emoji</label>
                    <div class="emoji-field-wrapper">
                        <input type="text" id="p_emoji" name="emoji" placeholder="Escribe o selecciona un emoji...">
                        <span class="emoji-preview" id="emojiPreview">🫓</span>
                        <button type="button" class="btn-emoji-picker" onclick="toggleEmojiPicker()" title="Abrir selector de emojis">😀</button>
                    </div>
                    <div class="emoji-picker-panel" id="emojiPickerPanel">
                        <!-- Pupusas / Comida -->
                        <div class="emoji-picker-category">
                            <span class="emoji-picker-category-label">🍽️ Comida</span>
                            <div class="emoji-picker-grid" id="emojiGrid_food">
                            </div>
                        </div>
                        <!-- Bebidas -->
                        <div class="emoji-picker-category">
                            <span class="emoji-picker-category-label">🥤 Bebidas</span>
                            <div class="emoji-picker-grid" id="emojiGrid_drinks">
                            </div>
                        </div>
                        <!-- Huevos y Animales -->
                        <div class="emoji-picker-category">
                            <span class="emoji-picker-category-label">🥚 Huevos y Animales</span>
                            <div class="emoji-picker-grid" id="emojiGrid_eggs">
                            </div>
                        </div>
                        <!-- Otros -->
                        <div class="emoji-picker-category">
                            <span class="emoji-picker-category-label">✨ Otros</span>
                            <div class="emoji-picker-grid" id="emojiGrid_other">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>📷 Imagen del Producto (Opcional)</label>
                    <div class="file-upload-wrapper">
                        <label class="file-upload-label" for="p_imagen" id="fileUploadLabel">
                            <span class="upload-icon">📁</span>
                            <span>Arrastra o haz clic para subir una imagen</span>
                        </label>
                        <input type="file" id="p_imagen" name="imagen" accept="image/*" class="file-upload-input" onchange="previewImage(this)">
                    </div>
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <img id="imagePreviewThumb" class="image-preview-thumb" src="" alt="Preview">
                        <button type="button" class="image-preview-remove" onclick="removeImagePreview()">✕</button>
                        <div class="current-image-label" id="currentImageLabel"></div>
                    </div>
                    <div class="url-or-label">— o pega una URL de imagen —</div>
                    <input type="text" id="p_imagen_url" name="imagen_url_manual" placeholder="https://ejemplo.com/imagen.jpg" style="width:100%; padding:10px 12px; background:var(--bg-primary); border:1px solid var(--border-subtle); color:var(--text-primary); border-radius:8px; box-sizing:border-box; font-size:0.9rem;">
                    <small style="color:var(--text-muted); display:block; margin-top:5px;">La imagen subida tiene prioridad sobre la URL.</small>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-danger" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">💾 Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modalEl = document.getElementById('productModal');
        const productFormEl = document.getElementById('productForm');
        const emojiInput = document.getElementById('p_emoji');
        const emojiPreview = document.getElementById('emojiPreview');
        const emojiPickerPanel = document.getElementById('emojiPickerPanel');

        // ========== EMOJI DATA ==========
        const EMOJI_DATA = {
            food: ['🫓','🧀','🫘','🍖','🍗','🐷','🌿','🟢','🍃','🌶️','🥕','🧄','🧅','🌱','🐔','🥓','🍄','🥩','🦐','🫔','🌽','🍌','🍳','🥚','🥘','🍲','🥗','🍕','🌮','🌯','🥪','🍔','🍟','🥟','🥠','🥡','🧆','🥙','🧇','🥞','🧈','🍞','🥐','🥖'],
            drinks: ['🍹','🧃','🥤','🥫','💧','🍺','☕','🫖','🍵','🥛','🧊','⚡','🔋','🍶','🍾','🥂','🍷','🍸','🥃','🍻','🧉','🫗'],
            eggs: ['🥚','🍳','🐣','🐤','🐥','🐔','🐓','🦆','🦃','🐷','🐮','🐟','🦐','🦞','🦑','🐙','🦀','🐄','🐖','🐑','🐐'],
            other: ['⭐','✨','🔥','❤️','💛','💚','🌈','🎉','🏷️','💲','🪶','🍬','🍭','🍫','🍩','🍪','🎂','🍰','🧁','🍦','🍨','🍧','🥄','🍴','🥢','🏪','🛒','📦','🛵','🍽️']
        };

        // Build emoji grids
        function buildEmojiGrids() {
            Object.keys(EMOJI_DATA).forEach(cat => {
                const grid = document.getElementById('emojiGrid_' + cat);
                if (!grid) return;
                grid.innerHTML = EMOJI_DATA[cat].map(e => 
                    `<button type="button" class="emoji-btn" onclick="selectEmoji('${e}')">${e}</button>`
                ).join('');
            });
        }
        buildEmojiGrids();

        function selectEmoji(emoji) {
            const current = emojiInput.value;
            if (current && !current.endsWith(',')) {
                emojiInput.value = current + ',' + emoji;
            } else {
                emojiInput.value = (current || '') + emoji;
            }
            updateEmojiPreview();
        }

        function updateEmojiPreview() {
            const val = emojiInput.value.trim();
            emojiPreview.textContent = val || '🫓';
        }

        emojiInput.addEventListener('input', updateEmojiPreview);

        function toggleEmojiPicker() {
            emojiPickerPanel.classList.toggle('open');
        }

        // ========== IMAGE PREVIEW ==========
        function previewImage(input) {
            const container = document.getElementById('imagePreviewContainer');
            const thumb = document.getElementById('imagePreviewThumb');
            const label = document.getElementById('currentImageLabel');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumb.src = e.target.result;
                    container.classList.add('has-image');
                    label.textContent = input.files[0].name;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImagePreview() {
            const container = document.getElementById('imagePreviewContainer');
            const input = document.getElementById('p_imagen');
            container.classList.remove('has-image');
            input.value = '';
        }

        // ========== MODAL FUNCTIONS ==========
        function toggleSubcategoria() {
            const cat = document.getElementById('p_categoria').value;
            document.getElementById('subcategoria_group').style.display = (cat === 'bebidasFrias') ? 'block' : 'none';
        }

        function openModal() {
            document.getElementById('modalTitle').textContent = 'Agregar Nuevo Producto';
            productFormEl.reset();
            document.getElementById('original_id').value = '';
            document.getElementById('p_imagen_url').value = '';
            emojiPreview.textContent = '🫓';
            emojiPickerPanel.classList.remove('open');
            removeImagePreview();
            toggleSubcategoria();
            // Show modal - use class to override the external CSS
            modalEl.classList.add('admin-modal-open');
        }

        function closeModal() {
            modalEl.classList.remove('admin-modal-open');
            emojiPickerPanel.classList.remove('open');
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
            document.getElementById('p_imagen_url').value = p.imagen_url || '';
            updateEmojiPreview();
            emojiPickerPanel.classList.remove('open');
            
            // Show current image if exists
            const container = document.getElementById('imagePreviewContainer');
            const thumb = document.getElementById('imagePreviewThumb');
            const label = document.getElementById('currentImageLabel');
            if (p.imagen_url) {
                thumb.src = p.imagen_url;
                container.classList.add('has-image');
                label.textContent = 'Imagen actual del producto';
            } else {
                removeImagePreview();
            }
            
            toggleSubcategoria();
            // Show modal
            modalEl.classList.add('admin-modal-open');
        }

        // Close modal on backdrop click
        modalEl.addEventListener('click', function(e) {
            if (e.target === modalEl) closeModal();
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalEl.classList.contains('admin-modal-open')) {
                closeModal();
            }
        });

        async function saveProduct(e) {
            e.preventDefault();
            const formData = new FormData(productFormEl);
            
            // If no file uploaded but there's a URL, pass it
            const fileInput = document.getElementById('p_imagen');
            const urlInput = document.getElementById('p_imagen_url');
            if ((!fileInput.files || fileInput.files.length === 0) && urlInput.value.trim()) {
                formData.append('imagen_url_from_field', urlInput.value.trim());
            }
            
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
                    btnSave.textContent = '💾 Guardar';
                }
            } catch (err) {
                alert('Error de conexión');
                console.error(err);
                btnSave.disabled = false;
                btnSave.textContent = '💾 Guardar';
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
