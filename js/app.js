/**
 * ============================================
 * PUPUSERÍA - SISTEMA DE ÓRDENES
 * Frontend Application Logic
 * ============================================
 */

// === Menu Data — Comedor Señorial ===
const MENU = {
  tradicionales: [
    { id: 't1', name: 'Revueltas', emoji: '🫓', price: 0.95 },
    { id: 't2', name: 'Frijol/Queso', emoji: '🫘,🧀', price: 0.95 },
    { id: 't3', name: 'Frijol', emoji: '🫘', price: 0.95 },
    { id: 't4', name: 'Jamón/Queso', emoji: '🍗,🧀', price: 0.95 },
    { id: 't5', name: 'Salami/Queso', emoji: '🍖,🧀', price: 0.95 },
    { id: 't6', name: 'Chicharrón/Frijol', emoji: '🐷,🫘', price: 0.95 },
    { id: 't7', name: 'Queso', emoji: '🧀', price: 0.95 },
    { id: 't8', name: 'Queso/Loroco', emoji: '🌿,🧀', price: 0.95 },
    { id: 't9', name: 'Ayote/Queso', emoji: '🟢,🧀', price: 0.95 },
    { id: 't10', name: 'Mora/Queso', emoji: '🍃,🧀', price: 0.95 },
    { id: 't11', name: 'Jalapeño/Queso', emoji: '🌶️,🧀', price: 0.95 },
    { id: 't12', name: 'Zanahoria/Queso', emoji: '🥕,🧀', price: 0.95 },
    { id: 't13', name: 'Ajo/Queso', emoji: '🧄,🧀', price: 0.95 },
    { id: 't14', name: 'Chicharrón/Queso', emoji: '🐷,🧀', price: 0.95 },
    { id: 't15', name: 'Cebolla/Queso', emoji: '🧅,🧀', price: 0.95 },
    { id: 't16', name: 'Papelillo/Queso', emoji: '🌱,🧀', price: 0.95 },
  ],
  especiales: [
    { id: 'e1', name: 'Pollo/Queso', emoji: '🐔,🧀', price: 1.25 },
    { id: 'e2', name: 'Tocino/Queso', emoji: '🥓,🧀', price: 1.25 },
    { id: 'e3', name: 'Hongos/Queso', emoji: '🍄,🧀', price: 1.25 },
    { id: 'e4', name: 'Chorizo/Queso', emoji: '🥩,🧀', price: 1.25 },
    { id: 'e5', name: 'Camarón/Queso', emoji: '🦐,🧀', price: 1.25 },
    { id: 'e6', name: 'Chicharrón', emoji: '🐷', price: 1.25 },
    { id: 'e7', name: 'Revuelta/Jalapeño', emoji: '🫓,🌶️', price: 1.25 },
    { id: 'e8', name: 'Revuelta/Queso', emoji: '🫓,🧀', price: 1.25 },
  ],
  tamalesYMas: [
    { id: 'm1', name: 'Tamal de Pollo', emoji: '🫔,🐔', price: 1.00 },
    { id: 'm2', name: 'Tamal de Costilla', emoji: '🫔,🍖', price: 1.00 },
    { id: 'm3', name: 'Tamal de Azúcar', emoji: '🫔', price: 1.00 },
    { id: 'm4', name: 'Tamal Pisque', emoji: '🫔,🫘', price: 0.60 },
    { id: 'm5', name: 'Tamal chipilin', emoji: '🫔,🌱', price: 0.60 },
    { id: 'm6', name: 'Tamal de Elote', emoji: '🫔,🌽', price: 0.50 },
    { id: 'm7', name: 'Canoas ', emoji: '🍌', price: 1.25, img: 'https://live.staticflickr.com/7336/10968270496_4be4a259a1_z.jpg' },
  ],
  bebidasFrias: [
    { id: 'bf1', name: 'Fresco Natural', emoji: '🍹', price: 0.50, sub: 'jugos y frescos' },
    { id: 'bf2', name: 'Fresco Natural Doble', emoji: '🍹', price: 1.00, sub: 'jugos y frescos' },
    { id: 'bf7', name: 'Jugo de lata Petit', emoji: '🥫', price: 0.50, sub: 'jugos y frescos', img: "https://th.bing.com/th/id/OIP.1mwlG4SAuSeMtuGfzKtTkwHaJ4?w=150&h=199&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf8', name: 'Frutado pequeño', emoji: '🧃', price: 0.50, sub: 'jugos y frescos' },
    {
      id: 'bf9', name: 'Jugo del Valle normal', emoji: '🧃', price: 0.75, sub: 'jugos y frescos', img: "https://walmartsv.vtexassets.com/arquivos/ids/486812-500-auto?v=638572752134100000&width=500&height=auto&aspect=true"
    },
    { id: 'bf3', name: 'Gaseosa normal', emoji: '🥤', price: 0.75, sub: 'gaseosas', img: 'img/gaseosa-normal.jpg' },
    { id: 'bf4', name: 'Gaseosa lata', emoji: '🥫', price: 1.00, sub: 'gaseosas', img: 'img/gaseosa-lata.jpg' },
    { id: 'bf10', name: 'Coca-Cola chiquita', emoji: '🥤', price: 0.75, sub: 'gaseosas', img: 'img/coca-cola-chiquita.jpg' },
    { id: 'bf11', name: 'Té Lipton normal', emoji: '🧊', price: 0.75, sub: 'jugos y frescos', img: 'https://th.bing.com/th/id/OIP.2MUMB9uXqNCHWCJtuQMIJQHaHa' },
    { id: 'bf12', name: 'Pepsi medium', emoji: '🥤', price: 1.00, sub: 'gaseosas', img: 'img/pepsi-medium.jpg' },
    { id: 'bf13', name: 'Mirinda medium', emoji: '🥤', price: 1.00, sub: 'gaseosas', img: 'img/mirinda-medium.jpg' },
    { id: 'bf14', name: '7up medium', emoji: '🥤', price: 1.00, sub: 'gaseosas', img: 'img/7up-medium.jpg' },
    { id: 'bf15', name: 'Té Lipton 1.5 lt', emoji: '🧊', price: 1.50, sub: 'jugos y frescos', img: 'https://tse3.mm.bing.net/th/id/OIP.4XE9egjRlDWCSwMA50TDnwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3' },
    { id: 'bf16', name: 'Té Lipton 2.5 lt', emoji: '🧊', price: 2.50, sub: 'jugos y frescos', img: 'https://tse3.mm.bing.net/th/id/OIP.RZA4kJlCOc9au434egG2qgHaHa' },
    { id: 'bf17', name: 'Pepsi 1.5 lt', emoji: '🥤', price: 1.50, sub: 'gaseosas', img: 'https://th.bing.com/th/id/OIP.EjV5i1mmBGn_aFiUQv7cXgHaHa' },
    { id: 'bf18', name: 'Pepsi 3 lt', emoji: '🥤', price: 2.50, sub: 'gaseosas', img: 'https://th.bing.com/th/id/OIP.Crl7RIdqrB3hLvb9PxiAVQHaHa' },
    { id: 'bf19', name: 'Coca-Cola 1.5 lt', emoji: '🥤', price: 1.50, sub: 'gaseosas', img: 'https://www.bing.com/th/id/OIP.EJnLfC1GqNP6TuE9UZ62hQHaHa?w=202&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2' },
    { id: 'bf20', name: 'Coca-Cola 2.5 lt', emoji: '🥤', price: 2.50, sub: 'gaseosas', img: 'https://www.bing.com/th/id/OIP.zYLd_lkAkEMTujL8bE97TgHaHa' },
    { id: 'bf5', name: 'Agua Cristal', emoji: '💧', price: 0.50, sub: 'agua', img: "https://th.bing.com/th/id/OIP.aL5LwSbiCsHNHehDKEQ2ngHaHa?w=181&h=181&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3", },
    { id: 'bf23', name: 'Agua mineral', emoji: '🪧', price: 0.75, sub: 'agua', img: "https://tse2.mm.bing.net/th/id/OIP.p0_AL5NQOMiTlXTSbllB1AHaKg?pid=ImgDet&w=178&h=252&c=7&dpr=1,5&o=7&rm=3" },
    { id: 'bf24', name: 'Agua 1 lt', emoji: '💧', price: 1.00, sub: 'agua', img: "https://tse2.mm.bing.net/th/id/OIP.p0_AL5NQOMiTlXTSbllB1AHaKg?pid=ImgDet&w=178&h=252&c=7&dpr=1,5&o=7&rm=3" },
    { id: 'bf6', name: 'Pilsener', emoji: '🍺', price: 1.00, sub: 'cervezas', img: "https://tse2.mm.bing.net/th/id/OIP.4eEPeeDxFMU_0kKWZhN-kgHaHa?pid=ImgDet&w=178&h=178&c=7&dpr=1,5&o=7&rm=3" },
    { id: 'bf21', name: 'Pilsener Lata', emoji: '🥫', price: 1.25, sub: 'cervezas', img: "https://th.bing.com/th/id/OIP.qTi0mrMt4IoCyQRLqZCldQHaHY?w=203&h=202&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf22', name: 'Salutary Lata', emoji: '🥫', price: 0.75, sub: 'gaseosas', img: 'https://images.deliveryhero.io/image/pedidosya/products/642c746470d9b0f412578ed5.jpg' },
    { id: 'bf25', name: 'Pepsi o Mirinda plastica', emoji: '🥤', price: 0.75, sub: 'gaseosas', img: "https://www.bing.com/th/id/OIP.TRayWmSw-hq1pW2ygTT0gAHaK5?w=160&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2", },
    { id: 'bf26', name: 'Salutary plastica', emoji: '🥤', price: 0.75, sub: 'gaseosas', img: "https://lacolonia.vtexassets.com/arquivos/ids/248847-800-auto?v=638671089334830000&width=800&height=auto&aspect=true", },
    { id: 'bf27', name: 'Pepsi plastica pequeña', emoji: '🥤', price: 0.75, sub: 'gaseosas', img: "https://res.cloudinary.com/riqra/image/upload/v1678811229/sellers/13/ebdvbbx1vfinzeslgk26.jpg", },
    { id: 'bf28', name: 'Pepsi Lata', emoji: '🥤', price: 0.75, sub: 'gaseosas', img: 'https://www.bing.com/th/id/OIP.2gg274m6dp3ffTXbFILF7AHaHa?w=212&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2', },
    { id: 'bf29', name: 'Jugo de la Granja', emoji: '🧃', price: 0.75, sub: 'jugos y frescos', img: "https://latinfoodsatyourdoor.com/cdn/shop/files/jugo-de-naranja-de-la-granja.png?v=1747920030&width=1500", },
    { id: 'bf30', name: 'Jugo Tampico', emoji: '🧃', price: 0.75, sub: 'jugos y frescos', img: "https://tse2.mm.bing.net/th/id/OIP.p0_AL5NQOMiTlXTSbllB1AHaKg?pid=ImgDet&w=178&h=252&c=7&dpr=1,5&o=7&rm=3" },
    { id: 'bf31', name: 'Chocolatina ¼', emoji: '🪶', price: 0.50, sub: 'jugos y frescos', img: "https://th.bing.com/th/id/OIP.rN3M9hA2NnZn_j_WGI5qUgHaHa?w=191&h=191&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3", },
    { id: 'bf32', name: 'Chocolatina ½', emoji: '🪶', price: 0.75, sub: 'jugos y frescos', img: "https://tse1.mm.bing.net/th/id/OIP.xQLiuaDYjqMbl6U4YHtt8gHaHa?rs=1&pid=ImgDetMain&o=7&rm=3", },
    { id: 'bf33', name: 'Powerade azul o rojo normal', emoji: '⚡', price: 1.00, sub: 'energéticas', img: "https://tse1.mm.bing.net/th/id/OIP.kOFYicqd8pcVAvTSBoSR0wHaGf?pid=ImgDet&w=178&h=156&c=7&dpr=1,5&o=7&rm=3" },
    { id: 'bf34', name: 'Powerade azul o rojo grande', emoji: '⚡', price: 1.50, sub: 'energéticas', img: "https://www.aperitissimo.fr/wp-content/uploads/2023/06/powerade-1024x576.png", },
    { id: 'bf35', name: 'Gatorade', emoji: '⚡', price: 1.00, sub: 'energéticas', img: "https://th.bing.com/th/id/OIP.Fn5kDtpgaGHqziRBinGS_gHaHa?w=212&h=212&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf36', name: 'Raptor', emoji: '🔋', price: 1.00, sub: 'energéticas', img: "https://th.bing.com/th/id/OIP.LWUVimpPTHEeW1G9mHBhAwHaHa?w=176&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf37', name: 'Monster', emoji: '🔋', price: 2.00, sub: 'energéticas', img: "https://th.bing.com/th/id/OIP.qBLWsqBIw9a2u78d_6546gHaHa?w=218&h=218&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf38', name: 'Be Light', emoji: '💧', price: 0.75, sub: 'energéticas', img: "https://th.bing.com/th/id/OIP.Vl-F8GMDqLmGhvwzMj99eAHaHa?w=172&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf39', name: 'Golden', emoji: '🍺', price: 1.25, sub: 'cervezas', img: "https://th.bing.com/th/id/OIP.sw8ZqN4yg4P1q6Wsfp6nkQHaHa?w=163&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf40', name: 'Corona', emoji: '🍺', price: 2.00, sub: 'cervezas', img: "https://th.bing.com/th/id/OIP.Ntmj6jczeQzPrE0mYwFDvwHaHa?w=202&h=202&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf41', name: 'Pilsener grande', emoji: '🍺', price: 1.75, sub: 'cervezas', img: "https://th.bing.com/th/id/OIP.hh0TXCpGb1KpD2_xKsn8jAAAAA?w=115&h=186&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },
    { id: 'bf42', name: 'Regia grande', emoji: '🍺', price: 1.75, sub: 'cervezas', img: "https://tse4.mm.bing.net/th/id/OIP.jRDtQkH9yzxO8r71mwDgWwHaSC?pid=ImgDet&w=144&h=350&c=7&dpr=1,5&o=7&rm=3" },
    { id: 'bf43', name: 'Suprema', emoji: '🍺', price: 1.50, sub: 'cervezas', img: "https://tse3.mm.bing.net/th/id/OIP.lnfg0oJ3v-WkqGFMFW51qQAAAA?rs=1&pid=ImgDetMain&o=7&rm=3" },
    { id: 'bf44', name: 'Coca Cola 3 lt', emoji: '🥤', price: 3.00, sub: 'gaseosas', img: "https://www.bing.com/th/id/OIP.PL876p8XUtZezqUWRXNSdwHaHa?w=199&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2", },
    { id: 'bf45', name: 'Frutado Normal', emoji: '🥤', price: 0.75, sub: 'jugos y frescos', img: "https://th.bing.com/th/id/OIP.gDMALHNSBuy1epqx6OzLhQHaFj?w=220&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3" },

  ],
  bebidasCalientes: [
    { id: 'bc1', name: 'Café', emoji: '🫖', price: 0.50 },
    { id: 'bc2', name: 'Café con leche', emoji: '🫖,🥛', price: 0.75 },
    { id: 'bc3', name: 'Chocolate', emoji: '☕', price: 0.75 },
    { id: 'bc4', name: 'Chocolate con leche', emoji: '☕,🥛', price: 0.75 },
    { id: 'bc5', name: 'Leche Caliente', emoji: '🍵', price: 0.75 },
    { id: 'bc6', name: 'Cappuccino', emoji: '☕', price: 1.00 },
    { id: 'bc7', name: 'Café con cremora', emoji: '🫖', price: 0.75 },
  ]
};

// Category display info
const CATEGORY_INFO = {
  tradicionales: { icon: '🫓', label: 'Pupusas Tradicionales', badge: '$0.95 c/u' },
  especiales: { icon: '⭐', label: 'Pupusas Especiales', badge: '$1.25 c/u' },
  tamalesYMas: { icon: '🫔', label: 'Tamales y Más', badge: '' },
  bebidasFrias: { icon: '🧊', label: 'Bebidas Frías', badge: '' },
  bebidasCalientes: { icon: '☕', label: 'Bebidas Calientes', badge: '' },
};

// Subcategory info for bebidasFrias
const SUBCATEGORY_INFO = {
  'jugos y frescos': { icon: '🧃', label: 'Jugos y Frescos' },
  gaseosas: { icon: '🥤', label: 'Gaseosas' },
  'energéticas': { icon: '⚡', label: 'Energéticas' },
  cervezas: { icon: '🍺', label: 'Cervezas' },
  agua: { icon: '💧', label: 'Agua' },
};

// === Order Type Options ===
const ORDER_TYPES = ['Comer Aquí', 'Para Llevar', 'Domicilio'];
const TYPE_EMOJIS = { 'Comer Aquí': '🍽️', 'Para Llevar': '📦', 'Domicilio': '🛵' };

// === State ===
let order = {}; // { itemId: { ...itemData, qty: N, orderType: string } }
let activeCategory = 'tradicionales';
let activeSubcategory = null; // subcategory filter for bebidasFrias
let activeOrderType = null; // must be selected before ordering

// === DOM References ===
const menuGrid = document.getElementById('menuGrid');
const orderItemsContainer = document.getElementById('orderItems');
const orderCountEl = document.getElementById('orderCount');
const orderTotalEl = document.getElementById('orderTotal');
const btnTicket = document.getElementById('btnTicket');
const btnDetails = document.getElementById('btnDetails');
const btnClear = document.getElementById('btnClear');
const modalOverlay = document.getElementById('modalOverlay');
const ticketBody = document.getElementById('ticketBody');
const toastEl = document.getElementById('toast');
const datetimeEl = document.getElementById('datetime');
const sectionHeader = document.getElementById('sectionHeader');
const orderTypeSelect = document.getElementById('orderTypeSelect');
const customerNameInput = document.getElementById('customerName');
const customerPhoneInput = document.getElementById('customerPhone');
const customerHoraInput = document.getElementById('customerHora');

// === Initialize ===
document.addEventListener('DOMContentLoaded', () => {
  renderMenu();
  updateSectionHeader();
  updateOrderUI();
  updateDateTime();
  setInterval(updateDateTime, 1000);

  // Category tabs
  document.querySelectorAll('.category-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      activeCategory = tab.dataset.category;
      activeSubcategory = null; // reset subcategory when changing category
      document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      renderMenu();
      updateSectionHeader();
    });
  });

  // Order type buttons in sidebar (switch mode for new items)
  document.querySelectorAll('.order-type__options .order-type__btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.order-type__btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const typeMap = { 'comer-aqui': 'Comer Aquí', 'para-llevar': 'Para Llevar', 'domicilio': 'Domicilio' };
      activeOrderType = typeMap[btn.dataset.type] || 'Comer Aquí';
      renderMenu();
      updateOrderUI();
    });
  });

  // Buttons
  btnTicket.addEventListener('click', generateTicket);
  btnClear.addEventListener('click', clearOrder);

  // Modal close
  modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) closeModal();
  });

  // Mobile toggle
  const orderHeader = document.querySelector('.order-panel__header');
  orderHeader.addEventListener('click', () => {
    if (window.innerWidth <= 900) {
      document.querySelector('.order-panel').classList.toggle('expanded');
    }
  });

  // Phone number validation: only digits, max 8
  customerPhoneInput.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 8);
  });
  customerPhoneInput.setAttribute('maxlength', '8');
  customerPhoneInput.setAttribute('pattern', '[0-9]*');
  customerPhoneInput.setAttribute('inputmode', 'numeric');

  // Hora deseada validation: only digits and colon, max 5 (HH:MM)
  customerHoraInput.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9:]/g, '').slice(0, 5);
  });
});

// === Update Section Header ===
function updateSectionHeader() {
  const info = CATEGORY_INFO[activeCategory];
  if (sectionHeader && info) {
    let label = info.label;
    let badge = info.badge;
    // Show subcategory in header when active
    if (activeCategory === 'bebidasFrias' && activeSubcategory && SUBCATEGORY_INFO[activeSubcategory]) {
      const subInfo = SUBCATEGORY_INFO[activeSubcategory];
      label = `${info.label} — ${subInfo.icon} ${subInfo.label}`;
      badge = '';
    }
    sectionHeader.innerHTML = `
            <span class="section-header__icon">${info.icon}</span>
            <h2>${label}</h2>
            ${badge ? `<span class="section-header__badge">${badge}</span>` : '<span class="section-header__badge">Selecciona para agregar</span>'}
        `;
  }
}

// === Render Menu ===
function renderMenu() {
  let items = MENU[activeCategory] || [];

  // If no order type selected, show big order type selection screen
  if (!activeOrderType) {
    menuGrid.style.display = 'none';
    orderTypeSelect.style.display = 'flex';
    orderTypeSelect.innerHTML = `
      <div class="order-type-select__header">
        <span class="order-type-select__icon">🛎️</span>
        <h2 class="order-type-select__title">¿Cómo será tu orden?</h2>
        <p class="order-type-select__subtitle">Selecciona el tipo de orden para comenzar</p>
      </div>
      <div class="order-type-select__buttons">
        <button class="order-type__btn" data-type="comer-aqui">🍽️ Comer Aquí</button>
        <button class="order-type__btn" data-type="para-llevar">📦 Para Llevar</button>
        <button class="order-type__btn" data-type="domicilio">🛵 Domicilio</button>
      </div>
    `;
    // Add click listeners for big buttons
    document.querySelectorAll('.order-type-select__buttons .order-type__btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const typeMap = { 'comer-aqui': 'Comer Aquí', 'para-llevar': 'Para Llevar', 'domicilio': 'Domicilio' };
        activeOrderType = typeMap[btn.dataset.type] || 'Comer Aquí';
        // Sync sidebar order type buttons
        document.querySelectorAll('.order-type__btn').forEach(b => b.classList.remove('active'));
        const sidebarBtn = document.getElementById({ 'comer-aqui': 'typeAqui', 'para-llevar': 'typeLlevar', 'domicilio': 'typeDomicilio' }[btn.dataset.type]);
        if (sidebarBtn) sidebarBtn.classList.add('active');
        
        // Hide global order-type toggle in sidebar if not Comer Aquí
        const orderTypeHeader = document.querySelector('.order-type');
        if (orderTypeHeader) {
          orderTypeHeader.style.display = activeOrderType === 'Comer Aquí' ? 'block' : 'none';
        }

        showToast(`✅ Orden: ${activeOrderType}`);
        renderMenu();
        updateOrderUI();
      });
    });
    return;
  }

  // Order type is selected, show menu grid
  orderTypeSelect.style.display = 'none';
  menuGrid.style.display = 'grid';

  // Sub-filter bar for bebidasFrias
  let subFilterHTML = '';
  if (activeCategory === 'bebidasFrias') {
    const subs = Object.entries(SUBCATEGORY_INFO);
    subFilterHTML = `
      <div class="sub-filter-bar">
        ${subs.map(([key, info]) => `
          <button class="sub-filter-btn ${activeSubcategory === key ? 'active' : ''}" data-sub="${key}">
            ${info.icon} ${info.label}
          </button>
        `).join('')}
      </div>
    `;
    // Filter items by subcategory if one is selected
    if (activeSubcategory) {
      items = items.filter(i => i.sub === activeSubcategory);
    }
  }

  // Check if this category has pupusas (need dough type)
  const isPupusaCategory = (activeCategory === 'tradicionales' || activeCategory === 'especiales');

  // Add custom pupusa card for especiales
  let customCardHTML = '';
  if (activeCategory === 'especiales') {
    customCardHTML = `
      <div class="menu-item menu-item--custom" id="menu-custom-pupusa">
        <span class="menu-item__emoji">✏️</span>
        <span class="menu-item__name">Personalizada</span>
        <span class="menu-item__price" style="color: var(--accent-green);">Precio libre</span>
      </div>
    `;
  }

  const itemsHTML = items.map(item => {
    const typeKey = `${item.id}__${activeOrderType.replace(/\s/g, '_')}`;
    // For pupusas, check both dough types
    const maizKey = `${item.id}__${activeOrderType.replace(/\s/g, '_')}__maiz`;
    const arrozKey = `${item.id}__${activeOrderType.replace(/\s/g, '_')}__arroz`;
    const hasAnyOrder = isPupusaCategory ? (!!order[maizKey] || !!order[arrozKey]) : !!order[typeKey];
    const activeEntry = isPupusaCategory ? (order[maizKey] || order[arrozKey]) : order[typeKey];
    const isActiveForType = hasAnyOrder;
    return `
      <div class="menu-item ${isActiveForType ? 'active' : ''}" data-id="${item.id}" id="menu-${item.id}">
        ${item.img ? `<img class="menu-item__img" src="${item.img}" alt="${item.name}">` : `<span class="menu-item__emoji">${item.emoji}</span>`}
        <span class="menu-item__name">${item.name}</span>
        <span class="menu-item__price">$${item.price.toFixed(2)}</span>
        <div class="qty-selector ${isActiveForType ? 'visible' : ''}" id="qty-${item.id}">
          <button class="qty-btn remove" onclick="event.stopPropagation(); changeQty('${item.id}', -1)">−</button>
          <span class="qty-display" id="qtyVal-${item.id}">${isActiveForType ? activeEntry.qty : 0}</span>
          <button class="qty-btn add" onclick="event.stopPropagation(); changeQty('${item.id}', 1)">+</button>
        </div>
        ${isPupusaCategory ? `
        <div class="dough-popup" id="dough-${item.id}">
          <span class="dough-popup__title">Tipo de masa:</span>
          <button class="dough-btn" data-id="${item.id}" data-masa="maiz">🌽 Maíz</button>
          <button class="dough-btn" data-id="${item.id}" data-masa="arroz">🍚 Arroz</button>
        </div>
        ` : ''}
      </div>
    `;
  }).join('');

  menuGrid.innerHTML = subFilterHTML + itemsHTML + customCardHTML;

  // Custom pupusa card click
  const customCard = document.getElementById('menu-custom-pupusa');
  if (customCard) {
    customCard.addEventListener('click', () => {
      showCustomPupusaForm();
    });
  }

  // Helper: get compound key for current type
  function getOrderKey(itemId) {
    return `${itemId}__${activeOrderType.replace(/\s/g, '_')}`;
  }

  // Add click listeners
  document.querySelectorAll('.menu-item').forEach(el => {
    el.addEventListener('click', (e) => {
      // Don't trigger if clicking on qty buttons or dough buttons
      if (e.target.closest('.qty-btn') || e.target.closest('.dough-btn')) return;

      const id = el.dataset.id;
      const item = findItem(id);
      if (!item) return;

      if (isPupusaCategory) {
        const maizKey = `${id}__${activeOrderType.replace(/\s/g, '_')}__maiz`;
        const arrozKey = `${id}__${activeOrderType.replace(/\s/g, '_')}__arroz`;

        // If already ordered with a dough type, just add +1 directly
        if (order[maizKey]) {
          order[maizKey].qty += 1;
          renderMenu();
          updateOrderUI();
          return;
        }
        if (order[arrozKey]) {
          order[arrozKey].qty += 1;
          renderMenu();
          updateOrderUI();
          return;
        }

        // First time: show dough type popup
        const popup = document.getElementById(`dough-${id}`);
        // Hide all other popups first
        document.querySelectorAll('.dough-popup.visible').forEach(p => {
          if (p !== popup) p.classList.remove('visible');
        });
        popup.classList.toggle('visible');
      } else {
        const key = getOrderKey(id);
        if (!order[key]) {
          order[key] = { ...item, qty: 1, orderType: activeOrderType };
          showToast(`✅ ${item.name} (${activeOrderType})`);
        }
        renderMenu();
        updateOrderUI();
      }
    });
  });

  // Dough type button click listeners
  document.querySelectorAll('.dough-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const id = btn.dataset.id;
      const masa = btn.dataset.masa;
      const item = findItem(id);
      if (!item) return;

      const key = `${id}__${activeOrderType.replace(/\s/g, '_')}__${masa}`;
      if (!order[key]) {
        order[key] = { ...item, qty: 1, orderType: activeOrderType, masa: masa };
        const masaLabel = masa === 'maiz' ? '🌽 Maíz' : '🍚 Arroz';
        showToast(`✅ ${item.name} (${masaLabel}) (${activeOrderType})`);
      } else {
        order[key].qty += 1;
      }

      // Hide popup
      document.getElementById(`dough-${id}`).classList.remove('visible');
      renderMenu();
      updateOrderUI();
    });
  });

  // Sub-filter button click listeners
  document.querySelectorAll('.sub-filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const sub = btn.dataset.sub;
      // Toggle: clicking same subcategory deselects it (show all)
      activeSubcategory = (activeSubcategory === sub) ? null : sub;
      renderMenu();
      updateSectionHeader();
    });
  });
}

// === Find Item in Menu ===
function findItem(id) {
  for (const category of Object.values(MENU)) {
    const found = category.find(i => i.id === id);
    if (found) return found;
  }
  return null;
}

// === Change Quantity ===
function changeQty(id, delta) {
  const isPupusa = id.startsWith('t') || id.startsWith('e');
  const baseKey = `${id}__${activeOrderType.replace(/\s/g, '_')}`;

  if (isPupusa) {
    // For pupusas, find the existing entry with masa suffix
    const maizKey = `${baseKey}__maiz`;
    const arrozKey = `${baseKey}__arroz`;
    const existingKey = order[maizKey] ? maizKey : order[arrozKey] ? arrozKey : null;

    if (existingKey) {
      order[existingKey].qty += delta;
      if (order[existingKey].qty <= 0) {
        const name = order[existingKey].name;
        delete order[existingKey];
        showToast(`🗑️ ${name} eliminada`);
      }
    }
    // If no existing entry, do nothing (user needs to pick masa type first)
  } else {
    if (!order[baseKey]) {
      const item = findItem(id);
      if (item && delta > 0) {
        order[baseKey] = { ...item, qty: 1, orderType: activeOrderType };
      }
    } else {
      order[baseKey].qty += delta;
      if (order[baseKey].qty <= 0) {
        const name = order[baseKey].name;
        delete order[baseKey];
        showToast(`🗑️ ${name} eliminada`);
      }
    }
  }
  renderMenu();
  updateOrderUI();
}

// === Update Order UI ===
function updateOrderUI() {
  const entries = Object.values(order);
  const totalItems = entries.reduce((sum, item) => sum + item.qty, 0);
  const totalPrice = entries.reduce((sum, item) => sum + (item.price * item.qty), 0);

  // Update count badge
  orderCountEl.textContent = totalItems;
  orderCountEl.classList.remove('bump');
  void orderCountEl.offsetWidth; // trigger reflow
  orderCountEl.classList.add('bump');

  // Update total
  orderTotalEl.textContent = `$${totalPrice.toFixed(2)}`;

  // Enable/disable buttons
  btnTicket.disabled = entries.length === 0;
  btnDetails.disabled = entries.length === 0;

  // Render order items
  if (entries.length === 0) {
    orderItemsContainer.innerHTML = `
      <div class="order-empty">
        <span class="order-empty__icon">📋</span>
        <span class="order-empty__text">No hay items en la orden.<br>Selecciona del menú para comenzar.</span>
      </div>
    `;
    return;
  }

  orderItemsContainer.innerHTML = Object.entries(order).map(([key, item]) => `
    <div class="order-item" id="order-${key}">
      <span class="order-item__emoji">${item.emoji}</span>
      <div class="order-item__info">
        <div class="order-item__name">${item.name}${item.masa ? ` <span class="order-item__masa">${item.masa === 'maiz' ? '🌽' : '🍚'} ${item.masa === 'maiz' ? 'Maíz' : 'Arroz'}</span>` : ''}</div>
        <div class="order-item__detail">$${item.price.toFixed(2)} c/u</div>
      </div>
      ${activeOrderType === 'Comer Aquí' ? `
      <button class="order-item__type-badge" onclick="cycleItemType('${key}')" title="Click para cambiar tipo">
        ${TYPE_EMOJIS[item.orderType] || '🍽️'} ${item.orderType || 'Comer Aquí'}
      </button>
      ` : `
      <div class="order-item__type-badge" style="cursor:default; background:transparent; border-color:transparent; opacity:0.8;">
        ${TYPE_EMOJIS[item.orderType] || '🍽️'} ${item.orderType || activeOrderType}
      </div>
      `}
      <div class="order-item__qty-controls">
        <button class="qty-btn remove" onclick="changeQtyByKey('${key}', -1)">−</button>
        <span class="order-item__qty">${item.qty}</span>
        <button class="qty-btn add" onclick="changeQtyByKey('${key}', 1)">+</button>
      </div>
      <span class="order-item__price">$${(item.price * item.qty).toFixed(2)}</span>
    </div>
  `).join('');
}

// === Cycle Item Order Type (re-keys the entry) ===
function cycleItemType(key) {
  if (!order[key]) return;
  const item = order[key];
  const currentIndex = ORDER_TYPES.indexOf(item.orderType);
  const nextIndex = (currentIndex + 1) % ORDER_TYPES.length;
  const newType = ORDER_TYPES[nextIndex];
  const masaSuffix = item.masa ? `__${item.masa}` : '';
  const newKey = `${item.id}__${newType.replace(/\s/g, '_')}${masaSuffix}`;

  // If an entry already exists for the new type, merge quantities
  if (order[newKey]) {
    order[newKey].qty += item.qty;
  } else {
    order[newKey] = { ...item, orderType: newType };
  }
  delete order[key];
  showToast(`🏷️ ${item.name} → ${newType}`);
  renderMenu();
  updateOrderUI();
}

// === Show Order Details (Preview) ===
function showOrderDetails() {
  const entries = Object.values(order);
  if (entries.length === 0) return;

  const totalPrice = entries.reduce((sum, item) => sum + (item.price * item.qty), 0);
  const totalItems = entries.reduce((sum, item) => sum + item.qty, 0);

  // Group by order type
  const typeGroups = {};
  for (const item of entries) {
    const type = item.orderType || 'Comer Aquí';
    if (!typeGroups[type]) typeGroups[type] = [];
    typeGroups[type].push(item);
  }

  const usedTypes = Object.keys(typeGroups);

  let html = `
    <div class="ticket" style="border: 2px dashed var(--accent-primary);">
      <div class="ticket__header">
        <div class="ticket__title" style="font-size:1.1rem;">📋 Vista Previa de Orden</div>
        <div class="ticket__info">${totalItems} item${totalItems > 1 ? 's' : ''} en la orden</div>
      </div>
  `;

  if (usedTypes.length > 1) {
    html += `<div class="ticket__order-type ticket__order-type--multi">🔀 ORDEN MIXTA</div>`;
  } else {
    html += `<div class="ticket__order-type">${TYPE_EMOJIS[usedTypes[0]] || ''} ${usedTypes[0]}</div>`;
  }

  for (const type of usedTypes) {
    const groupItems = typeGroups[type];
    if (usedTypes.length > 1) {
      html += `<div class="ticket__type-header">${TYPE_EMOJIS[type] || ''} ${type.toUpperCase()}</div>`;
    }

    const tradicionales = groupItems.filter(i => i.id.startsWith('t'));
    const especiales = groupItems.filter(i => i.id.startsWith('e'));
    const personalizadas = groupItems.filter(i => i.id.startsWith('custom_') || (!i.id.startsWith('t') && !i.id.startsWith('e') && !i.id.startsWith('m') && !i.id.startsWith('bf') && !i.id.startsWith('bc')));
    const tamalesYMas = groupItems.filter(i => i.id.startsWith('m'));
    const bebidasFrias = groupItems.filter(i => i.id.startsWith('bf'));
    const bebidasCalientes = groupItems.filter(i => i.id.startsWith('bc'));

    const sections = [
      { items: tradicionales, icon: '🫓', label: 'Tradicionales' },
      { items: especiales, icon: '⭐', label: 'Especiales' },
      { items: personalizadas, icon: '✏️', label: 'Personalizadas' },
      { items: tamalesYMas, icon: '🫔', label: 'Tamales y Más' },
      { items: bebidasFrias, icon: '�', label: 'Bebidas Frías' },
      { items: bebidasCalientes, icon: '☕', label: 'Bebidas Calientes' },
    ];

    for (const section of sections) {
      if (section.items.length > 0) {
        html += `
          <div class="ticket__section-title">${section.icon} ${section.label}</div>
          <div class="ticket__items">
            ${section.items.map(item => `
              <div class="ticket__item">
                <span class="ticket__item-name">${item.emoji} ${item.name}${item.masa ? ` (${item.masa === 'maiz' ? 'Maíz' : 'Arroz'})` : ''}</span>
                <span class="ticket__item-qty">x${item.qty}</span>
                <span class="ticket__item-price">$${(item.price * item.qty).toFixed(2)}</span>
              </div>
            `).join('')}
          </div>
        `;
      }
    }

    if (usedTypes.length > 1) {
      html += `<hr class="ticket__divider ticket__divider--light">`;
    }
  }

  html += `
      <hr class="ticket__divider">
      <div class="ticket__totals">
        <div class="ticket__total-row grand">
          <span>TOTAL</span>
          <span>$${totalPrice.toFixed(2)}</span>
        </div>
      </div>
      <div class="ticket__footer">
        <em style="font-size:0.75rem; color:#999;">Esta es solo una vista previa.<br>Genera el ticket para confirmar la orden.</em>
      </div>
    </div>
  `;

  ticketBody.innerHTML = html;
  // Hide ticket-specific buttons, only show close
  document.getElementById('btnPrint').style.display = 'none';
  document.getElementById('btnNewOrder').style.display = 'none';
  openModal();
}

// === Change Quantity by compound key (from order panel) ===
function changeQtyByKey(key, delta) {
  if (!order[key]) return;
  order[key].qty += delta;
  if (order[key].qty <= 0) {
    const name = order[key].name;
    delete order[key];
    showToast(`🗑️ ${name} eliminada`);
  }
  renderMenu();
  updateOrderUI();
}

// === Sequential Order Number (1-100, cycles) ===
function getNextOrderNumber() {
  let current = parseInt(localStorage.getItem('orderCounter') || '0', 10);
  current = current + 1;
  if (current > 100) current = 1;
  localStorage.setItem('orderCounter', String(current));
  return current;
}

// === Sequential "Para Llevar" List Number (1-100, cycles) ===
function getNextLlevarNumber() {
  let current = parseInt(localStorage.getItem('llevarCounter') || '0', 10);
  current = current + 1;
  if (current > 100) current = 1;
  localStorage.setItem('llevarCounter', String(current));
  return current;
}

// === Generate Ticket ===
async function generateTicket() {
  const entries = Object.values(order);
  if (entries.length === 0) return;

  // Validate customer name
  const customerName = customerNameInput.value.trim();
  if (!customerName) {
    customerNameInput.classList.add('error');
    customerNameInput.focus();
    showToast('⚠️ El nombre del cliente es obligatorio');
    setTimeout(() => customerNameInput.classList.remove('error'), 1500);
    return;
  }
  const customerPhone = customerPhoneInput.value.trim();
  const customerHora = customerHoraInput.value.trim();

  btnTicket.disabled = true;
  btnTicket.innerHTML = '⏳ Generando...';

  try {
    const seqNumber = getNextOrderNumber();
    // If order is Para Llevar, assign a llevar list number
    const isLlevar = activeOrderType === 'Para Llevar';
    const llevarNum = isLlevar ? getNextLlevarNumber() : null;
    const response = await fetch('generar_ticket.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ items: entries, customerName, customerPhone, customerHora, orderNumber: seqNumber, llevarNumber: llevarNum })
    });

    const data = await response.json();

    if (data.success) {
      renderTicket(data);
      openModal();
    } else {
      showToast('❌ Error al generar ticket');
    }
  } catch (err) {
    // Fallback: generate ticket client-side if PHP not available
    const fallbackData = generateTicketLocal(entries, customerName, customerPhone);
    renderTicket(fallbackData);
    openModal();
  }

  btnTicket.disabled = false;
  btnTicket.innerHTML = '🧾 Generar Ticket';
}

// === Fallback Local Ticket Generation ===
function generateTicketLocal(entries, customerName, customerPhone) {
  const subtotal = entries.reduce((sum, item) => sum + (item.price * item.qty), 0);
  const seqNum = getNextOrderNumber();
  const orderNum = 'Orden #' + seqNum;
  const now = new Date();
  const isLlevar = activeOrderType === 'Para Llevar';
  const llevarNum = isLlevar ? getNextLlevarNumber() : null;

  return {
    success: true,
    orderNumber: orderNum,
    date: now.toLocaleDateString('es-SV'),
    time: now.toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit' }),
    items: entries,
    subtotal: subtotal,
    total: subtotal,
    customerName: customerName,
    customerPhone: customerPhone,
    customerHora: customerHoraInput.value.trim(),
    llevarNumber: llevarNum
  };
}

// === Render Ticket ===
function renderTicket(data) {
  // Group items by order type
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
        ${data.customerHora ? `<span class="ticket__customer-phone">🕐 Hora deseada: ${data.customerHora.length === 4 ? data.customerHora.slice(0,2) + ':' + data.customerHora.slice(2) : data.customerHora}</span>` : ''}
      </div>
      ${data.llevarNumber ? `
      <div class="ticket__llevar-badge">
        📦 Número de Orden: <strong>#${data.llevarNumber}</strong>
      </div>
      ` : ''}
  `;

  // If only one type, show single big badge
  if (!hasMultipleTypes) {
    html += `<div class="ticket__order-type">${TYPE_EMOJIS[usedTypes[0]] || ''} ${usedTypes[0]}</div>`;
  } else {
    // Multiple types: show combined badge
    html += `<div class="ticket__order-type ticket__order-type--multi">🔀 ORDEN MIXTA</div>`;
  }

  // Render items grouped by order type
  for (const type of usedTypes) {
    const groupItems = typeGroups[type];

    // Show type header (always if multiple, skip if single since badge already shows it)
    if (hasMultipleTypes) {
      html += `<div class="ticket__type-header">${TYPE_EMOJIS[type] || ''} ${type.toUpperCase()}</div>`;
    }

    // Group by food category within this type
    const tradicionales = groupItems.filter(i => i.id.startsWith('t'));
    const especiales = groupItems.filter(i => i.id.startsWith('e'));
    const personalizadas = groupItems.filter(i => i.id.startsWith('custom_') || (!i.id.startsWith('t') && !i.id.startsWith('e') && !i.id.startsWith('m') && !i.id.startsWith('bf') && !i.id.startsWith('bc')));
    const tamalesYMas = groupItems.filter(i => i.id.startsWith('m'));
    const bebidasFrias = groupItems.filter(i => i.id.startsWith('bf'));
    const bebidasCalientes = groupItems.filter(i => i.id.startsWith('bc'));

    const sections = [
      { items: tradicionales, icon: '🫓', label: 'Tradicionales' },
      { items: especiales, icon: '⭐', label: 'Especiales' },
      { items: personalizadas, icon: '✏️', label: 'Personalizadas' },
      { items: tamalesYMas, icon: '🫔', label: 'Tamales y Más' },
      { items: bebidasFrias, icon: '�', label: 'Bebidas Frías' },
      { items: bebidasCalientes, icon: '☕', label: 'Bebidas Calientes' },
    ];

    for (const section of sections) {
      if (section.items.length > 0) {
        html += `
          <div class="ticket__section-title">${section.icon} ${section.label}</div>
          <div class="ticket__items">
            ${section.items.map(item => `
              <div class="ticket__item">
                <span class="ticket__item-name">${item.emoji} ${item.name}${item.masa ? ` (${item.masa === 'maiz' ? 'Maíz' : 'Arroz'})` : ''}</span>
                <span class="ticket__item-qty">x${item.qty}</span>
                <span class="ticket__item-price">$${(item.price * item.qty).toFixed(2)}</span>
              </div>
            `).join('')}
          </div>
        `;
      }
    }

    // Divider between type groups
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
        <div class="ticket__footer-emoji">🙏</div>
        ¡Gracias por su compra, ${data.customerName}!<br>
        <strong>Comedor Señorial — ¡Buen provecho!</strong>
      </div>
    </div>
  `;

  ticketBody.innerHTML = html;
  // Restore ticket-specific buttons
  document.getElementById('btnPrint').style.display = '';
  document.getElementById('btnNewOrder').style.display = '';
}

// === Modal Controls ===
function openModal() {
  modalOverlay.classList.add('visible');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  modalOverlay.classList.remove('visible');
  document.body.style.overflow = '';
}

function printTicket() {
  window.print();
}

function newOrder() {
  order = {};
  activeOrderType = null;
  closeModal();
  // Clear customer fields
  customerNameInput.value = '';
  customerPhoneInput.value = '';
  // Reset category to Tradicionales
  activeCategory = 'tradicionales';
  activeSubcategory = null;
  document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('tab-tradicionales').classList.add('active');
  
  // Show order type selector again
  const orderTypeHeader = document.querySelector('.order-type');
  if (orderTypeHeader) orderTypeHeader.style.display = 'block';

  renderMenu();
  updateSectionHeader();
  updateOrderUI();
  showToast('🆕 Nueva orden iniciada');
}

// === Clear Order ===
function clearOrder() {
  if (Object.keys(order).length === 0) return;
  order = {};
  renderMenu();
  updateOrderUI();
  showToast('🗑️ Orden limpiada');
}

// === Custom Pupusa Form ===
let customPupusaCounter = 0;

function showCustomPupusaForm() {
  // Create overlay
  const overlay = document.createElement('div');
  overlay.className = 'custom-form-overlay';
  overlay.innerHTML = `
    <div class="custom-form">
      <div class="custom-form__header">
        <h3>✏️ Pupusa Personalizada</h3>
        <button class="custom-form__close" id="customFormClose">✖</button>
      </div>
      <div class="custom-form__body">
        <div class="custom-form__field">
          <label class="customer-label">🫓 Ingredientes</label>
          <input type="text" id="customIngredients" class="customer-input" placeholder="Ej: Pollo, Loroco, Queso" autofocus>
        </div>
        <div class="custom-form__field">
          <label class="customer-label">💰 Precio Unitario ($)</label>
          <input type="number" id="customPrice" class="customer-input" placeholder="0.00" step="0.05" min="0.25">
        </div>
        <div class="custom-form__field">
          <label class="customer-label">🌾 Tipo de Masa</label>
          <div class="custom-form__masa-options">
            <button class="dough-btn custom-masa-btn active" data-masa="maiz">🌽 Maíz</button>
            <button class="dough-btn custom-masa-btn" data-masa="arroz">🍚 Arroz</button>
          </div>
        </div>
      </div>
      <div class="custom-form__footer">
        <button class="btn-ticket custom-form__submit" id="customFormSubmit">➕ Agregar a Orden</button>
      </div>
    </div>
  `;
  document.body.appendChild(overlay);

  // Show with animation
  requestAnimationFrame(() => overlay.classList.add('visible'));

  let selectedMasa = 'maiz';

  // Masa toggle
  overlay.querySelectorAll('.custom-masa-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      overlay.querySelectorAll('.custom-masa-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      selectedMasa = btn.dataset.masa;
    });
  });

  // Close
  const closeForm = () => {
    overlay.classList.remove('visible');
    setTimeout(() => overlay.remove(), 300);
  };

  overlay.querySelector('#customFormClose').addEventListener('click', closeForm);
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeForm();
  });

  // Submit
  overlay.querySelector('#customFormSubmit').addEventListener('click', () => {
    const ingredients = document.getElementById('customIngredients').value.trim();
    const price = parseFloat(document.getElementById('customPrice').value);

    if (!ingredients) {
      document.getElementById('customIngredients').classList.add('error');
      setTimeout(() => document.getElementById('customIngredients').classList.remove('error'), 1500);
      showToast('⚠️ Escribe los ingredientes');
      return;
    }
    if (!price || price <= 0) {
      document.getElementById('customPrice').classList.add('error');
      setTimeout(() => document.getElementById('customPrice').classList.remove('error'), 1500);
      showToast('⚠️ Ingresa un precio válido');
      return;
    }

    customPupusaCounter++;
    const customId = `custom_${customPupusaCounter}`;
    const key = `${customId}__${activeOrderType.replace(/\s/g, '_')}__${selectedMasa}`;
    const masaLabel = selectedMasa === 'maiz' ? '🌽 Maíz' : '🍚 Arroz';

    order[key] = {
      id: customId,
      name: ingredients,
      emoji: '✏️',
      price: price,
      qty: 1,
      orderType: activeOrderType,
      masa: selectedMasa
    };

    showToast(`✅ ${ingredients} (${masaLabel}) $${price.toFixed(2)}`);
    closeForm();
    renderMenu();
    updateOrderUI();
  });
}

// === Toast Notification ===
let toastTimeout;
function showToast(message) {
  toastEl.innerHTML = message;
  toastEl.classList.add('show');
  clearTimeout(toastTimeout);
  toastTimeout = setTimeout(() => {
    toastEl.classList.remove('show');
  }, 2000);
}

// === Update DateTime ===
function updateDateTime() {
  const now = new Date();
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  const date = now.toLocaleDateString('es-SV', options);
  const time = now.toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

  if (datetimeEl) {
    datetimeEl.innerHTML = `<span>${date}</span><span>${time}</span>`;
  }
}
