<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sistema de órdenes para Pupusería - Menú interactivo y generación de tickets">
  <title>Comedor Señorial - Sistema de Órdenes</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon"
    href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🫓</text></svg>">
</head>

<body>

  <!-- Header -->
  <header class="header">
    <div class="header__brand">
      <img src="img/logo.png" alt="Comedor Señorial" class="header__logo">
      <div>
        <h1 class="header__title">Comedor Señorial</h1>
        <span class="header__subtitle">Pupusería — Sistema de Órdenes</span>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 1rem;">
      <button id="btnVolver" onclick="goBackToOrderType()" style="display:none; background: rgba(255,255,255,0.15); padding: 0.4rem 0.8rem; border-radius: 6px; color: white; font-weight: 700; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.3); cursor: pointer; font-family: 'Outfit', sans-serif;">⬅ Volver</button>
      <a href="inventario.php?logout=1" style="background: rgba(255,255,255,0.1); padding: 0.4rem 0.8rem; border-radius: 6px; text-decoration: none; color: white; font-weight: 600; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.2);">📊 Inventario Diario</a>
      <div class="header__datetime" id="datetime"></div>
    </div>
  </header>

  <!-- Main App -->
  <main class="app">
    <!-- Menu Section -->
    <section class="menu-section">
      <!-- Category Tabs -->
      <div class="category-tabs">
        <button class="category-tab active" data-category="tradicionales" id="tab-tradicionales">
          🫓 Tradicionales
        </button>
        <button class="category-tab" data-category="especiales" id="tab-especiales">
          ⭐ Especiales
        </button>
        <button class="category-tab" data-category="tamalesYMas" id="tab-tamalesYMas">
          🫔 Tamales y Más
        </button>
        <button class="category-tab" data-category="bebidasFrias" id="tab-bebidasFrias">
          🧊 Bebidas Frías
        </button>
        <button class="category-tab" data-category="bebidasCalientes" id="tab-bebidasCalientes">
          ☕ Bebidas Calientes
        </button>
      </div>

      <!-- Section Header -->
      <div class="section-header" id="sectionHeader">
        <span class="section-header__icon">🫓</span>
        <h2>Pupusas Tradicionales</h2>
        <span class="section-header__badge">$0.95 c/u</span>
      </div>

      <!-- Order Type Selection Screen -->
      <div class="order-type-select" id="orderTypeSelect">
        <!-- Rendered by JS -->
      </div>

      <!-- Menu Grid -->
      <div class="menu-grid" id="menuGrid">
        <!-- Items rendered by JS -->
      </div>
    </section>

    <!-- Order Panel -->
    <aside class="order-panel">
      <div class="order-panel__header">
        <h2>
          🧾 Orden Actual
          <span class="order-count" id="orderCount">0</span>
        </h2>
        <button class="btn-clear" id="btnClear" onclick="clearOrder()">
          🗑️ Limpiar
        </button>
      </div>

      <!-- Customer Info -->
      <div class="customer-info">
        <div class="customer-field">
          <label for="customerName" class="customer-label">👤 Nombre <span class="required">*</span></label>
          <input type="text" id="customerName" class="customer-input" placeholder="Nombre del cliente" required>
        </div>
        <div class="customer-field">
          <label for="customerPhone" class="customer-label">📱 Teléfono <span class="optional">(opcional)</span></label>
          <input type="tel" id="customerPhone" class="customer-input" placeholder="0000-0000">
        </div>
        <div class="customer-field">
          <label for="customerHora" class="customer-label">🕐 Hora deseada <span class="optional">(opcional)</span></label>
          <input type="text" id="customerHora" class="customer-input" placeholder="Ej: 12:30" maxlength="5" inputmode="numeric">
        </div>
      </div>

      <!-- Order Type (hidden by default, shown only for Comer Aquí) -->
      <div class="order-type" style="display:none;">
        <label class="customer-label">🏷️ Tipo de Orden</label>
        <div class="order-type__options">
          <button class="order-type__btn" data-type="comer-aqui" id="typeAqui"><span class="emoji-animate emoji-plates">🍽️</span> Comer Aquí</button>
          <button class="order-type__btn" data-type="para-llevar" id="typeLlevar"><span class="emoji-animate emoji-box">📦</span> Para Llevar</button>
          <button class="order-type__btn" data-type="domicilio" id="typeDomicilio"><span class="emoji-animate emoji-moto">🛵</span> Domicilio</button>
        </div>
      </div>


      <div class="order-items" id="orderItems">
        <div class="order-empty">
          <span class="order-empty__icon">📋</span>
          <span class="order-empty__text">No hay items en la orden.<br>Selecciona del menú para comenzar.</span>
        </div>
      </div>

      <div class="order-footer">
        <div class="order-total">
          <span class="order-total__label">Total</span>
          <span class="order-total__value" id="orderTotal">$0.00</span>
        </div>
        <div class="order-footer__buttons">
          <button class="btn-details" id="btnDetails" disabled onclick="showOrderDetails()">
            📋 Ver Detalles
          </button>
          <button class="btn-ticket" id="btnTicket" disabled>
            🧾 Generar Ticket
          </button>
        </div>
      </div>
    </aside>
  </main>

  <!-- Ticket Modal -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal-content">
      <div id="ticketBody">
        <!-- Ticket rendered by JS -->
      </div>
      <div class="modal-actions">
        <button class="btn-print" onclick="printTicket()" id="btnPrint">🖨️ Imprimir</button>
        <button class="btn-new-order" onclick="newOrder()" id="btnNewOrder">🆕 Nueva Orden</button>
        <button class="btn-close-modal" onclick="closeModal()" id="btnCloseModal">✖ Cerrar</button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast" id="toast"></div>

  <!-- Scripts -->
  <script src="js/app.js?v=<?= time() ?>"></script>
</body>

</html>