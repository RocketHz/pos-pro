/**
 * MÓDULO: State Management
 * Gestión centralizada del estado de la aplicación
 */
export const AppState = {
  data: {
    products: [],
    categories: [],
    tables: [],
    cart: [],
    currentTable: null,
    currentTipPercentage: 15,
    currentDiscount: 0,
    totals: {subtotal: 0, discount: 0, tip: 0, total: 0},
    stats: {
      salesToday: 0,
      transactions: 0,
      avgTicket: 0,
      tips: 0,
      activeTables: 0,
      paymentMethods: []
    },
    chartData: {
      hourlySales: [],
      topProducts: []
    }
  },

  async init() {
    try {
      const [prodRes, tableRes] = await Promise.all([
        fetch('/api/products'),
        fetch('/api/tables')
      ]);

      const prodData = await prodRes.json();
      const tableData = await tableRes.json();
      this.data.products = prodData.products || [];
      this.data.categories = prodData.categories || [];
      this.data.tables = tableData || [];

      document.dispatchEvent(new CustomEvent('state:loaded'));
    } catch (error) {
      console.error("Error cargando el estado:", error);
    }
  },

  selectTable(tableId) {
    this.data.currentTable = tableId;

    const infoPanel = document.getElementById('pos-table-info');
    if (infoPanel) {
      const table = this.data.tables.find(t => t.id === tableId);
      infoPanel.innerText = `Mesa: ${table ? table.number : 'Sin asignar'}`;
    }
  },

  showTableActionModal(tableId) {
    if (typeof UIRenderer !== 'undefined') {
      UIRenderer.showTableActionModal(tableId);
    }
  },

  async toggleTableStatus(tableId, action) {
    const table = this.data.tables.find(t => t.id === tableId);
    if (!table) return;

    try {
      let response;
      if (action === 'free') {
        response = await fetch(`/api/tables/${tableId}/free`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
      } else if (action === 'occupy') {
        response = await fetch(`/api/tables/${tableId}`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ status: 'occupied' })
        });
      } else if (action === 'reserve') {
        response = await fetch(`/api/tables/${tableId}`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ status: 'reserved' })
        });
      }

      if (response && response.ok) {
        const result = await response.json();
        const t = this.data.tables.find(t => t.id === tableId);
        if (t) t.status = result.table.status;
        document.dispatchEvent(new CustomEvent('state:loaded'));
      }
    } catch (err) {
      console.error('Error cambiando estado de mesa:', err);
    }
  },

  async selectTableAndGoToPOS(tableId) {
    // Close the action modal
    if (typeof UIRenderer !== 'undefined') {
      UIRenderer.hideTableActionModal();
    }

    this.selectTable(tableId);

    // Navigate to POS
    const posItem = document.querySelector('[data-section="pos"]');
    if (posItem) posItem.click();
  },

  addToCart(productId) {
    const product = this.data.products.find(p => p.id === productId);
    if (!product) return;

    const existing = this.data.cart.find(item => item.id === productId);
    if (existing) {
      existing.quantity++;
    } else {
      this.data.cart.push({ ...product, quantity: 1, tempId: Date.now() });
    }

    document.dispatchEvent(new CustomEvent('cart:updated'));
  },

  removeFromCart(tempId) {
    this.data.cart = this.data.cart.filter(item => item.tempId !== tempId);
    document.dispatchEvent(new CustomEvent('cart:updated'));
  },

  updateQuantity(tempId, quantity) {
    const item = this.data.cart.find(i => i.tempId === tempId);
    if (item) {
      if (quantity <= 0) {
        this.removeFromCart(tempId);
      } else {
        item.quantity = parseInt(quantity);
        document.dispatchEvent(new CustomEvent('cart:updated'));
      }
    }
  },

  updateTip(percentage) {
    this.data.currentTipPercentage = parseFloat(percentage);
    this.calculateTotals();
    document.dispatchEvent(new CustomEvent('cart:updated'));
  },

  async applyCoupon(code) {
    if (!code || code.trim() === '') return;

    const msgElement = document.getElementById('pos-coupon-msg');

    try {
      const response = await fetch('/api/coupons/check', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          code: code.trim(),
          subtotal: this.data.totals.subtotal
        })
      });

      const result = await response.json();

      if (response.ok && result.valid) {
        this.data.currentDiscount = parseFloat(result.discount);
        if (msgElement) {
          msgElement.innerText = `¡${result.message} (-$${parseFloat(result.discount).toFixed(2)})`;
          msgElement.className = "text-xs text-emerald-400 mt-1";
          msgElement.classList.remove('hidden');
        }
      } else {
        this.data.currentDiscount = 0;
        if (msgElement) {
          msgElement.innerText = result.message || "Cupón inválido";
          msgElement.className = "text-xs text-red-400 mt-1";
          msgElement.classList.remove('hidden');
        }
      }
    } catch (error) {
      console.error("Error validando cupón:", error);
      this.data.currentDiscount = 0;
      if (msgElement) {
        msgElement.innerText = "Error de conexión";
        msgElement.className = "text-xs text-red-400 mt-1";
        msgElement.classList.remove('hidden');
      }
    }

    this.calculateTotals();
    document.dispatchEvent(new CustomEvent('cart:updated'));
  },

  calculateTotals() {
    const subtotal = this.data.cart.reduce((sum, item) => sum + (parseFloat(item.price) * (item.quantity || 1)), 0);
    const tipAmount = subtotal * (this.data.currentTipPercentage / 100);
    const discountAmount = this.data.currentDiscount;

    this.data.totals = {
      subtotal: subtotal,
      discount: discountAmount,
      tip: tipAmount,
      total: Math.max(0, subtotal + tipAmount - discountAmount)
    };
  },

  openCheckoutModal() {
    if (this.data.cart.length === 0) {
      this.showAlert("El carrito está vacío.");
      return;
    }
    if (typeof UIRenderer !== 'undefined') {
      UIRenderer.showCheckoutModal();
    }
  },

  async processCheckout(paymentData) {
    if (this.data.cart.length === 0) {
      this.showAlert("El carrito está vacío.");
      return;
    }

    const orderData = {
      table_id: this.data.currentTable,
      items: this.data.cart.map(item => ({
        product_id: item.id,
        quantity: item.quantity || 1
      })),
      total: this.data.totals.total,
      discount: this.data.totals.discount,
      tip: this.data.totals.tip,
      payment_method: paymentData.payment_method,
      payment_reference: paymentData.payment_reference || null,
      payment_details: paymentData.payment_details || null
    };

    try {
      const response = await fetch('/api/orders', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(orderData)
      });

      const result = await response.json();

      if (response.ok) {
        // Show receipt modal
        if (typeof UIRenderer !== 'undefined') {
          UIRenderer.showReceipt(result.order, result.order_id);
        }

        // Clear cart
        this.data.cart = [];
        this.data.currentTable = null;
        this.data.currentDiscount = 0;
        this.calculateTotals();
        document.dispatchEvent(new CustomEvent('cart:updated'));

        const infoPanel = document.getElementById('pos-table-info');
        if (infoPanel) infoPanel.innerText = 'Mesa: Sin asignar';

        // Update POS table selector
        this.updatePOSTableSelector(null);

        // Reload data
        await this.init();
        // Force dashboard to refresh so next time user visits it shows updated data
        await this.loadDashboardData();
        await this.loadPaymentMethods();
      } else {
        this.showAlert("❌ Error: " + (result.details || result.error || "No se pudo procesar la venta"));
      }
    } catch (error) {
      console.error("Error en la petición:", error);
      this.showAlert("Error crítico de conexión.");
    }
  },

  updatePOSTableSelector(tableId) {
    const select = document.getElementById('pos-table-selector');
    if (select) {
      select.value = tableId || '';
    }
  },

  async loadDashboardData() {
    try {
      const response = await fetch('/api/dashboard-stats');
      const data = await response.json();

      this.data.stats.salesToday = data.stats.salesToday;
      this.data.stats.transactions = data.stats.transactions;
      this.data.stats.avgTicket = data.stats.avgTicket;
      this.data.stats.tips = data.stats.tips;
      this.data.stats.activeTables = data.stats.activeTables;
      this.data.chartData.hourlySales = data.charts.hourlySales;
      this.data.chartData.topProducts = data.charts.topProducts;

      // Direct render instead of relying on event
      if (typeof UIRenderer !== 'undefined') {
        UIRenderer.renderDashboard(this.data.stats, this.data.chartData);
      }
    } catch (error) {
      console.error("Error cargando estadísticas:", error);
    }
  },

  async loadPaymentMethods() {
    try {
      const response = await fetch('/api/reports/payment-methods');
      const data = await response.json();
      this.data.stats.paymentMethods = data.methods || [];
      if (typeof UIRenderer !== 'undefined') {
        UIRenderer.renderPaymentMethods(this.data.stats.paymentMethods);
      }
    } catch (error) {
      console.error("Error cargando métodos de pago:", error);
    }
  },

  // Universal alert modal
  showAlert(message) {
    if (typeof UIRenderer !== 'undefined') {
      UIRenderer.showAlertModal(message);
    } else {
      alert(message);
    }
  },

  // Universal confirm modal
  async showConfirm(message, title = 'Confirmar') {
    return new Promise((resolve) => {
      if (typeof UIRenderer !== 'undefined') {
        UIRenderer.showConfirmModal(message, title, resolve);
      } else {
        resolve(confirm(message));
      }
    });
  }
};
