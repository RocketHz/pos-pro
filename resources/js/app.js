import './bootstrap';
import { AppState } from './state';
import { UIRenderer } from './ui-renderer';
import './inventory';
import './coupons';

window.AppState = AppState;
window.UIRenderer = UIRenderer;

document.addEventListener('DOMContentLoaded', () => {
  AppState.init();

  const navItems = document.querySelectorAll('.nav-item');
  const sections = document.querySelectorAll('.section-content');
  const pageTitle = document.getElementById('page-title');
  const newSaleBtn = document.getElementById('new-sale-btn');

  const sectionTitles = {
    'dashboard': 'Panel de Control',
    'tables': 'Gestión de Mesas',
    'pos': 'Punto de Venta',
    'inventory': 'Inventario de Productos',
    'reports': 'Reportes y Estadísticas',
    'coupons': 'Cupones de Descuento'
  };

  // --- Section navigation ---
  navItems.forEach(item => {
    item.addEventListener('click', async () => {
      const target = item.getAttribute('data-section');

      sections.forEach(s => {
        s.classList.add('hidden');
        s.classList.remove('active');
      });

      const activeSection = document.getElementById(`${target}-section`);
      if (activeSection) {
        activeSection.classList.remove('hidden');
        setTimeout(() => activeSection.classList.add('active'), 10);
      }

      navItems.forEach(n => {
        const link = n.querySelector('.nav-link');
        link.classList.remove('bg-slate-800', 'text-white', 'border-blue-500');
        link.classList.add('text-slate-300', 'border-transparent');
      });

      const activeLink = item.querySelector('.nav-link');
      activeLink.classList.add('bg-slate-800', 'text-white', 'border-blue-500');
      activeLink.classList.remove('text-slate-300', 'border-transparent');

      if (pageTitle) pageTitle.innerText = sectionTitles[target] || 'POS Pro';

      if (newSaleBtn) {
        newSaleBtn.classList.toggle('hidden', target !== 'dashboard');
      }

      // Load section-specific data
      if (target === 'dashboard') {
        await AppState.loadDashboardData();
        await AppState.loadPaymentMethods();
      } else if (target === 'reports') {
        await loadReports();
      } else if (target === 'coupons') {
        await CouponModule.loadCoupons();
      } else if (target === 'inventory') {
        try {
          const res = await fetch('/api/products/all');
          const data = await res.json();
          UIRenderer.renderInventory(data.products || []);
        } catch (err) {
          console.error('Error cargando inventario:', err);
        }
      }
    });
  });

  // --- Tip buttons ---
  document.querySelectorAll('.tip-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tip-btn').forEach(b => b.classList.remove('active', 'bg-amber-500', 'border-amber-500', 'text-white'));
      btn.classList.add('active', 'bg-amber-500', 'border-amber-500', 'text-white');
      AppState.updateTip(btn.getAttribute('data-tip'));
    });
  });

  // --- Coupon button ---
  const btnApplyCoupon = document.getElementById('btn-apply-coupon');
  const inputCoupon = document.getElementById('pos-coupon');
  if (btnApplyCoupon && inputCoupon) {
    btnApplyCoupon.addEventListener('click', () => AppState.applyCoupon(inputCoupon.value));
  }

  // --- Checkout button -> open payment modal ---
  const btnCheckout = document.getElementById('btn-checkout');
  if (btnCheckout) {
    btnCheckout.addEventListener('click', () => AppState.openCheckoutModal());
  }

  // --- New Sale button -> go to POS ---
  if (newSaleBtn) {
    newSaleBtn.addEventListener('click', () => {
      const posItem = document.querySelector('[data-section="pos"]');
      if (posItem) posItem.click();
    });
  }

  // --- POS table selector change ---
  const posTableSelector = document.getElementById('pos-table-selector');
  if (posTableSelector) {
    posTableSelector.addEventListener('change', (e) => {
      const tableId = e.target.value ? parseInt(e.target.value) : null;
      AppState.selectTable(tableId);
    });
  }

  // --- Payment method change ---
  const paymentSelect = document.getElementById('checkout-payment-method');
  if (paymentSelect) {
    paymentSelect.addEventListener('change', (e) => UIRenderer.onPaymentMethodChange(e.target.value));
  }

  // --- Submit checkout ---
  const btnSubmitCheckout = document.getElementById('btn-submit-checkout');
  if (btnSubmitCheckout) {
    btnSubmitCheckout.addEventListener('click', () => UIRenderer.submitCheckout());
  }

  // --- Close checkout modal ---
  const btnCloseCheckout = document.getElementById('btn-close-checkout');
  if (btnCloseCheckout) {
    btnCloseCheckout.addEventListener('click', () => UIRenderer.hideCheckoutModal());
  }

  // --- Table action modal buttons ---
  const btnTableOccupy = document.getElementById('table-btn-occupy');
  if (btnTableOccupy) btnTableOccupy.addEventListener('click', () => UIRenderer.tableOccupyAndGoPOS());

  const btnTableFree = document.getElementById('table-btn-free');
  if (btnTableFree) btnTableFree.addEventListener('click', () => UIRenderer.tableFree());

  const btnTableReserve = document.getElementById('table-btn-reserve');
  if (btnTableReserve) btnTableReserve.addEventListener('click', () => UIRenderer.tableReserve());

  const btnCloseTableAction = document.getElementById('btn-close-table-action');
  if (btnCloseTableAction) btnCloseTableAction.addEventListener('click', () => UIRenderer.hideTableActionModal());

  // --- Receipt modal ---
  const btnCloseReceipt = document.getElementById('btn-close-receipt');
  const receiptModal = document.getElementById('receipt-modal');
  if (btnCloseReceipt) btnCloseReceipt.addEventListener('click', () => UIRenderer.hideReceipt());
  if (receiptModal) {
    receiptModal.addEventListener('click', (e) => { if (e.target === receiptModal) UIRenderer.hideReceipt(); });
  }

  const btnPrintReceipt = document.getElementById('btn-print-receipt');
  if (btnPrintReceipt) {
    btnPrintReceipt.addEventListener('click', () => {
      const content = document.getElementById('receipt-content');
      if (content) {
        const win = window.open('', '_blank');
        win.document.write('<html><head><title>Ticket</title><style>body{font-family:monospace;font-size:12px;}</style></head><body>');
        win.document.write(content.innerHTML);
        win.document.write('</body></html>');
        win.document.close();
        win.print();
      }
    });
  }

  // --- Product modal ---
  const btnAddProduct = document.getElementById('btn-add-product');
  if (btnAddProduct) btnAddProduct.addEventListener('click', () => InventoryModule.addProduct());

  const btnSubmitProduct = document.getElementById('btn-submit-product');
  if (btnSubmitProduct) btnSubmitProduct.addEventListener('click', () => InventoryModule.submitProduct());

  const btnCloseProduct = document.getElementById('btn-close-product-modal');
  if (btnCloseProduct) btnCloseProduct.addEventListener('click', () => UIRenderer.hideProductModal());

  // --- Alert modal ---
  const btnCloseAlert = document.getElementById('btn-close-alert');
  if (btnCloseAlert) btnCloseAlert.addEventListener('click', () => UIRenderer.hideAlertModal());

  // --- Confirm modal ---
  const btnConfirmYes = document.getElementById('btn-confirm-yes');
  if (btnConfirmYes) btnConfirmYes.addEventListener('click', () => UIRenderer.confirmYes());

  const btnConfirmNo = document.getElementById('btn-confirm-no');
  if (btnConfirmNo) btnConfirmNo.addEventListener('click', () => UIRenderer.confirmNo());

  // --- Coupon create ---
  const btnCreateCoupon = document.getElementById('btn-create-coupon');
  if (btnCreateCoupon) btnCreateCoupon.addEventListener('click', () => CouponModule.createCoupon());

  // --- Date display ---
  const dateEl = document.getElementById('current-date');
  if (dateEl) {
    dateEl.innerText = new Date().toLocaleDateString('es', {
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
  }

  // Close modals on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      UIRenderer.hideCheckoutModal();
      UIRenderer.hideReceipt();
      UIRenderer.hideProductModal();
      UIRenderer.hideTableActionModal();
      UIRenderer.hideAlertModal();
      UIRenderer.hideConfirmModal();
    }
  });
});

// When state is loaded, render products, categories, tables
document.addEventListener('state:loaded', () => {
  UIRenderer.renderCategories(AppState.data.categories);
  UIRenderer.renderProducts(AppState.data.products);
  UIRenderer.renderTables(AppState.data.tables);
  UIRenderer.updatePOSTableSelector(AppState.data.currentTable);

  // Populate POS table selector
  const select = document.getElementById('pos-table-selector');
  if (select) {
    const currentVal = select.value;
    select.innerHTML = '<option value="">Sin mesa</option>' +
      AppState.data.tables.map(t =>
        `<option value="${t.id}" ${t.status === 'occupied' ? 'style="color:red"' : ''}>${t.number} (${t.status === 'occupied' ? 'Ocupada' : 'Libre'})</option>`
      ).join('');
    select.value = currentVal || '';
  }
});

// Update totals when cart is updated
document.addEventListener('cart:updated', () => {
  const el1 = document.getElementById('pos-subtotal');
  const el2 = document.getElementById('pos-discount');
  const el3 = document.getElementById('pos-tip');
  const el4 = document.getElementById('pos-total');
  if (el1) el1.innerText = AppState.data.totals.subtotal.toFixed(2);
  if (el2) el2.innerText = AppState.data.totals.discount.toFixed(2);
  if (el3) el3.innerText = AppState.data.totals.tip.toFixed(2);
  if (el4) el4.innerText = AppState.data.totals.total.toFixed(2);
  UIRenderer.updateCartUI(AppState.data.cart);
});

async function loadReports() {
  try {
    const [weeklyRes, transRes, payRes] = await Promise.all([
      fetch('/api/reports/weekly-sales'),
      fetch('/api/reports/recent-transactions'),
      fetch('/api/reports/payment-methods')
    ]);
    const weeklyData = await weeklyRes.json();
    const transData = await transRes.json();
    const payData = await payRes.json();
    UIRenderer.renderWeeklySales(weeklyData.labels || [], weeklyData.data || []);
    UIRenderer.renderRecentTransactions(transData.orders || []);
    UIRenderer.renderPaymentMethods(payData.methods || []);
  } catch (err) {
    console.error('Error cargando reportes:', err);
  }
}
