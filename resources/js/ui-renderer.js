// resources/js/ui-renderer.js

export const UIRenderer = {
  renderProducts(products) {
    const grid = document.getElementById('pos-products-grid');
    if (!grid) return;
    if (products.length === 0) {
      grid.innerHTML = '<p class="text-slate-500 col-span-full text-center">No hay productos</p>';
      return;
    }
    grid.innerHTML = products.map(product => `
      <div class="glass-panel p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all cursor-pointer group"
           onclick="AppState.addToCart(${product.id})">
        <div class="text-4xl mb-2 group-hover:scale-110 transition-transform">${product.image || '🍔'}</div>
        <h3 class="font-bold text-white">${product.name}</h3>
        <p class="text-blue-400 font-bold">$${parseFloat(product.price).toFixed(2)}</p>
        <p class="text-slate-500 text-xs">Stock: ${product.stock}</p>
      </div>
    `).join('');
  },

  renderCategories(categories) {
    const container = document.getElementById('categories-container');
    if (!container) return;
    container.innerHTML = categories.map(cat => `
      <button onclick="AppState.filterByCategory(${cat.id})"
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700">
        <span>${cat.icon}</span>
        <span>${cat.name}</span>
      </button>
    `).join('');
  },

  updateCartUI(cart) {
    const cartContainer = document.getElementById('pos-cart-items');
    if (!cartContainer) return;

    if (cart.length === 0) {
      cartContainer.innerHTML = `
        <div class="text-center text-slate-500 mt-8">
          <i class="fas fa-shopping-cart text-4xl mb-2"></i>
          <p>Carrito vacío</p>
        </div>`;
      return;
    }

    cartContainer.innerHTML = cart.map(item => `
      <div class="flex justify-between items-center bg-slate-800/50 p-3 rounded-lg mb-2 border border-slate-700">
        <div class="flex-1">
          <p class="text-white text-sm font-medium">${item.name}</p>
          <p class="text-blue-400 text-xs">$${parseFloat(item.price).toFixed(2)} c/u</p>
        </div>
        <div class="flex items-center gap-2">
          <button onclick="AppState.updateQuantity(${item.tempId}, ${item.quantity - 1})"
                  class="w-6 h-6 rounded bg-slate-700 text-white hover:bg-slate-600 text-xs">-</button>
          <span class="text-white text-sm w-6 text-center">${item.quantity}</span>
          <button onclick="AppState.updateQuantity(${item.tempId}, ${item.quantity + 1})"
                  class="w-6 h-6 rounded bg-slate-700 text-white hover:bg-slate-600 text-xs">+</button>
        </div>
        <button onclick="AppState.removeFromCart(${item.tempId})" class="text-red-400 hover:text-red-300 p-2 ml-2">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    `).join('');
  },

  updatePOSTableSelector(tableId) {
    const select = document.getElementById('pos-table-selector');
    if (select) select.value = tableId || '';
  },

  renderTables(tables) {
    const grid = document.getElementById('tables-grid');
    if (!grid) return;

    grid.innerHTML = tables.map(table => {
      const isOccupied = table.status === 'occupied';
      const isReserved = table.status === 'reserved';
      let borderColor = 'border-emerald-500';
      let textColor = 'text-emerald-400';
      let statusText = 'Libre';
      let actionBtn = '';

      if (isOccupied) {
        borderColor = 'border-red-500';
        textColor = 'text-red-400';
        statusText = 'Ocupada';
        actionBtn = `<button onclick="event.stopPropagation(); AppState.showTableActionModal(${table.id})" class="mt-3 w-full bg-amber-600 hover:bg-amber-700 text-white py-2 rounded text-xs font-bold transition-colors">
          <i class="fas fa-cog mr-1"></i>Acciones
        </button>`;
      } else if (isReserved) {
        borderColor = 'border-amber-500';
        textColor = 'text-amber-400';
        statusText = 'Reservada';
        actionBtn = `<button onclick="event.stopPropagation(); AppState.showTableActionModal(${table.id})" class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-xs font-bold transition-colors">
          <i class="fas fa-cog mr-1"></i>Acciones
        </button>`;
      } else {
        actionBtn = `<button onclick="event.stopPropagation(); AppState.showTableActionModal(${table.id})" class="mt-3 w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded text-xs font-bold transition-colors">
          <i class="fas fa-chair mr-1"></i>Ocupar Mesa
        </button>`;
      }

      return `
        <div class="glass-panel p-6 rounded-xl border-2 transition-all hover:scale-105 ${borderColor}">
          <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center">
              <i class="fas fa-chair ${textColor}"></i>
            </div>
            <span class="text-[10px] uppercase font-bold px-2 py-1 rounded bg-slate-900 ${textColor}">
              ${statusText}
            </span>
          </div>
          <h3 class="text-xl font-bold text-white">Mesa ${table.number}</h3>
          <p class="text-xs text-slate-400 mt-1">Capacidad: ${table.capacity} pers.</p>
          ${actionBtn}
        </div>
      `;
    }).join('');
  },

  // ---- TABLE ACTION MODAL ----
  showTableActionModal(tableId) {
    const modal = document.getElementById('table-action-modal');
    if (!modal) return;

    const table = AppState.data.tables.find(t => t.id === tableId);
    if (!table) return;

    const isOccupied = table.status === 'occupied';
    const isReserved = table.status === 'reserved';

    document.getElementById('table-action-title').innerText = `Mesa ${table.number}`;
    document.getElementById('table-action-status').innerText = isOccupied ? 'Ocupada' : (isReserved ? 'Reservada' : 'Libre');
    document.getElementById('table-action-status').className = `text-sm font-bold ${isOccupied ? 'text-red-400' : (isReserved ? 'text-amber-400' : 'text-emerald-400')}`;
    document.getElementById('table-action-capacity').innerText = `${table.capacity} personas`;

    // Store table ID
    document.getElementById('table-action-id').value = tableId;

    // Show/hide relevant buttons
    const btnOccupy = document.getElementById('table-btn-occupy');
    const btnFree = document.getElementById('table-btn-free');
    const btnReserve = document.getElementById('table-btn-reserve');

    if (isOccupied) {
      btnOccupy.classList.add('hidden');
      btnFree.classList.remove('hidden');
      btnReserve.classList.add('hidden');
    } else if (isReserved) {
      btnOccupy.classList.remove('hidden');
      btnFree.classList.remove('hidden');
      btnReserve.classList.add('hidden');
    } else {
      btnOccupy.classList.remove('hidden');
      btnFree.classList.add('hidden');
      btnReserve.classList.remove('hidden');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  hideTableActionModal() {
    const modal = document.getElementById('table-action-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  },

  async tableOccupyAndGoPOS() {
    const tableId = parseInt(document.getElementById('table-action-id').value);
    this.hideTableActionModal();

    // Mark as occupied
    await AppState.toggleTableStatus(tableId, 'occupy');

    // Select table and go to POS
    AppState.selectTableAndGoToPOS(tableId);
  },

  async tableOccupy() {
    const tableId = parseInt(document.getElementById('table-action-id').value);
    this.hideTableActionModal();
    await AppState.toggleTableStatus(tableId, 'occupy');
  },

  async tableFree() {
    const tableId = parseInt(document.getElementById('table-action-id').value);
    this.hideTableActionModal();
    await AppState.toggleTableStatus(tableId, 'free');
  },

  async tableReserve() {
    const tableId = parseInt(document.getElementById('table-action-id').value);
    this.hideTableActionModal();
    await AppState.toggleTableStatus(tableId, 'reserve');
  },

  renderInventory(products) {
    const tbody = document.getElementById('inventory-table');
    if (!tbody) return;

    if (products.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" class="text-center text-slate-500 py-8">No hay productos</td></tr>';
      return;
    }

    tbody.innerHTML = products.map(product => `
      <tr class="hover:bg-slate-800/50 transition-colors">
        <td class="px-6 py-4">
          <div class="flex items-center gap-3">
            <span class="text-2xl">${product.image || '🍽️'}</span>
            <span class="text-white font-medium">${product.name}</span>
          </div>
        </td>
        <td class="px-6 py-4 text-slate-300">${product.category?.name || 'Sin categoría'}</td>
        <td class="px-6 py-4 text-slate-300">${product.stock}</td>
        <td class="px-6 py-4 text-blue-400 font-bold">$${parseFloat(product.price).toFixed(2)}</td>
        <td class="px-6 py-4">
          <span class="px-2 py-1 rounded text-xs font-bold ${product.is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'}">
            ${product.is_active ? 'Activo' : 'Inactivo'}
          </span>
        </td>
        <td class="px-6 py-4">
          <div class="flex gap-2">
            <button onclick="InventoryModule.editProduct(${product.id})" class="text-blue-400 hover:text-blue-300">
              <i class="fas fa-edit"></i>
            </button>
            <button onclick="InventoryModule.deleteProduct(${product.id})" class="text-red-400 hover:text-red-300">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    `).join('');
  },

  renderCoupons(coupons) {
    const grid = document.getElementById('coupons-grid');
    if (!grid) return;

    if (coupons.length === 0) {
      grid.innerHTML = '<p class="text-slate-500 col-span-full text-center">No hay cupones creados</p>';
      return;
    }

    grid.innerHTML = coupons.map(coupon => {
      const usageText = coupon.usage_limit ? `${coupon.usage_count} / ${coupon.usage_limit}` : `${coupon.usage_count}`;
      const isExhausted = coupon.usage_limit && coupon.usage_count >= coupon.usage_limit;

      return `
      <div class="glass-panel p-6 rounded-xl border border-slate-700 ${isExhausted ? 'opacity-60' : ''}">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="text-lg font-bold text-white">${coupon.code}</h3>
            <p class="text-sm text-slate-400">
              ${coupon.type === 'percentage' ? `${coupon.value}% descuento` : `$${parseFloat(coupon.value).toFixed(2)} fijo`}
            </p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer" ${coupon.is_active ? 'checked' : ''}
                   onchange="CouponModule.toggleActive(${coupon.id}, this.checked)">
            <div class="w-9 h-5 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
          </label>
        </div>
        <div class="text-xs text-slate-500 space-y-1">
          <p>Compra mínima: $${parseFloat(coupon.min_purchase || 0).toFixed(2)}</p>
          <p>Usos: ${usageText}</p>
          ${isExhausted ? '<p class="text-amber-400 font-bold">⚠️ Cupón agotado</p>' : ''}
          ${coupon.usage_limit_per_customer ? `<p>Límite por cliente: ${coupon.usage_limit_per_customer}</p>` : ''}
          ${coupon.expires_at ? `<p>Expira: ${new Date(coupon.expires_at).toLocaleDateString()}</p>` : '<p>Sin expiración</p>'}
        </div>
        <div class="flex gap-2 mt-4">
          <button onclick="CouponModule.editCoupon(${coupon.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm">
            <i class="fas fa-edit mr-1"></i>Editar
          </button>
          <button onclick="CouponModule.deleteCoupon(${coupon.id})" class="bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm px-3">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    `}).join('');
  },

  renderDashboard(stats, chartData) {
    // Update stat cards safely
    const elSales = document.getElementById('stat-sales');
    const elTrans = document.getElementById('stat-transactions');
    const elAvg = document.getElementById('stat-avg');
    const elTips = document.getElementById('stat-tips');
    const elTables = document.getElementById('stat-tables');

    if (elSales) elSales.innerText = parseFloat(stats.salesToday || 0).toFixed(2);
    if (elTrans) elTrans.innerText = stats.transactions || 0;
    if (elAvg) elAvg.innerText = parseFloat(stats.avgTicket || 0).toFixed(2);
    if (elTips) elTips.innerText = parseFloat(stats.tips || 0).toFixed(2);
    if (elTables) elTables.innerText = stats.activeTables || 0;

    // Sales chart
    const salesCanvas = document.getElementById('chart-sales');
    if (!salesCanvas) return;

    const existingSalesChart = Chart.getChart(salesCanvas);
    if (existingSalesChart) existingSalesChart.destroy();

    const hours = [];
    for (let i = 11; i >= 0; i--) {
      hours.push(new Date(Date.now() - i * 3600000).toLocaleTimeString('es', {hour: '2-digit', minute: '2-digit'}));
    }

    new Chart(salesCanvas.getContext('2d'), {
      type: 'line',
      data: {
        labels: hours,
        datasets: [{
          label: 'Ventas ($)',
          data: chartData.hourlySales || [],
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: { legend: { display: false } },
        scales: {
          x: { ticks: { color: '#94a3b8', maxRotation: 45 } },
          y: { ticks: { color: '#94a3b8' }, beginAtZero: true }
        }
      }
    });

    // Top products chart
    const productsCanvas = document.getElementById('chart-products');
    if (!productsCanvas) return;

    const existingProductsChart = Chart.getChart(productsCanvas);
    if (existingProductsChart) existingProductsChart.destroy();

    if (chartData.topProducts && chartData.topProducts.length > 0) {
      const colors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'];
      new Chart(productsCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: chartData.topProducts.map(p => p.name),
          datasets: [{
            data: chartData.topProducts.map(p => p.total),
            backgroundColor: colors.slice(0, chartData.topProducts.length)
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          aspectRatio: 1.5,
          plugins: {
            legend: { position: 'bottom', labels: { color: '#e2e8f0' } }
          }
        }
      });
    }
  },

  renderPaymentMethods(methods) {
    const container = document.getElementById('payment-methods-container');
    if (!container) return;

    container.innerHTML = methods.map(m => `
      <div class="p-4 bg-slate-800 rounded-lg">
        <i class="${m.icon} text-3xl ${m.color} mb-2"></i>
        <p class="text-2xl font-bold">${m.percentage}%</p>
        <p class="text-sm text-slate-400">${m.label}</p>
        <p class="text-xs text-slate-500">${m.count} transacciones</p>
      </div>
    `).join('');
  },

  renderWeeklySales(labels, data) {
    const canvas = document.getElementById('chart-weekly');
    if (!canvas) return;

    const existingChart = Chart.getChart(canvas);
    if (existingChart) existingChart.destroy();

    new Chart(canvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Ventas ($)',
          data: data,
          backgroundColor: '#3b82f6',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.6,
        plugins: { legend: { display: false } },
        scales: {
          x: { ticks: { color: '#94a3b8' } },
          y: { ticks: { color: '#94a3b8' }, beginAtZero: true }
        }
      }
    });
  },

  renderRecentTransactions(orders) {
    const tbody = document.getElementById('reports-transactions');
    if (!tbody) return;

    if (orders.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500 py-8">No hay transacciones</td></tr>';
      return;
    }

    tbody.innerHTML = orders.map(order => `
      <tr class="hover:bg-slate-800/50 transition-colors">
        <td class="py-3 text-slate-400">#${order.id}</td>
        <td class="py-3 text-white">${order.date}</td>
        <td class="py-3 text-slate-300">${order.table}</td>
        <td class="py-3 text-slate-300">${order.items}</td>
        <td class="py-3 text-emerald-400 font-bold">$${order.total}</td>
        <td class="py-3 text-slate-300">${order.payment_method}</td>
        <td class="py-3">
          <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400">${order.status}</span>
        </td>
      </tr>
    `).join('');
  },

  // ---- CHECKOUT MODAL ----
  showCheckoutModal() {
    const modal = document.getElementById('checkout-modal');
    if (!modal) return;

    document.getElementById('checkout-subtotal').innerText = AppState.data.totals.subtotal.toFixed(2);
    document.getElementById('checkout-discount').innerText = AppState.data.totals.discount.toFixed(2);
    document.getElementById('checkout-tip').innerText = AppState.data.totals.tip.toFixed(2);
    document.getElementById('checkout-total').innerText = AppState.data.totals.total.toFixed(2);

    document.getElementById('checkout-payment-method').value = 'cash';
    document.getElementById('checkout-reference-group').classList.add('hidden');
    document.getElementById('checkout-details-group').classList.add('hidden');
    document.getElementById('checkout-reference').value = '';
    document.getElementById('checkout-details').value = '';
    document.getElementById('checkout-reference').required = false;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  hideCheckoutModal() {
    const modal = document.getElementById('checkout-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  },

  onPaymentMethodChange(method) {
    const refGroup = document.getElementById('checkout-reference-group');
    const detailsGroup = document.getElementById('checkout-details-group');
    const refInput = document.getElementById('checkout-reference');

    refGroup.classList.add('hidden');
    detailsGroup.classList.add('hidden');
    refInput.value = '';
    refInput.required = false;

    if (method === 'mobile_transfer') {
      refGroup.classList.remove('hidden');
      refInput.required = true;
    } else if (method === 'other') {
      detailsGroup.classList.remove('hidden');
    }
  },

  submitCheckout() {
    const method = document.getElementById('checkout-payment-method').value;
    const reference = document.getElementById('checkout-reference').value;
    const details = document.getElementById('checkout-details').value;

    if (method === 'mobile_transfer' && !reference.trim()) {
      AppState.showAlert('Debe ingresar un número de referencia para Pago Móvil/Transferencia');
      return;
    }

    if (method === 'other' && !details.trim()) {
      AppState.showAlert('Debe especificar el método de pago');
      return;
    }

    const paymentData = {
      payment_method: method,
      payment_reference: reference.trim() || null,
      payment_details: details.trim() || null
    };

    this.hideCheckoutModal();
    AppState.processCheckout(paymentData);
  },

  // ---- RECEIPT MODAL ----
  showReceipt(order, orderId) {
    const modal = document.getElementById('receipt-modal');
    if (!modal) return;

    const paymentLabels = {
      cash: 'Efectivo',
      pos: 'Punto de Venta',
      mobile_transfer: 'Pago Móvil/Transferencia',
      other: 'Otro'
    };

    document.getElementById('receipt-id').innerText = String(orderId).padStart(4, '0');
    document.getElementById('receipt-date').innerText = new Date().toLocaleString('es');

    const itemsContainer = document.getElementById('receipt-items');
    const orderItems = order.items || [];
    itemsContainer.innerHTML = orderItems.map(item => `
      <div class="flex justify-between">
        <span>${item.quantity}x ${item.product?.name || 'Producto'}</span>
        <span>$${(item.price * item.quantity).toFixed(2)}</span>
      </div>
    `).join('');

    document.getElementById('r-subtotal').innerText = parseFloat(order.total + (order.discount || 0) - (order.tip || 0)).toFixed(2);
    document.getElementById('r-discount').innerText = (order.discount || 0).toFixed(2);
    document.getElementById('r-tip').innerText = (order.tip || 0).toFixed(2);
    document.getElementById('r-total').innerText = parseFloat(order.total).toFixed(2);

    const paymentMethodEl = document.getElementById('receipt-payment-method');
    if (paymentMethodEl) paymentMethodEl.innerText = paymentLabels[order.payment_method] || 'Otro';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  hideReceipt() {
    const modal = document.getElementById('receipt-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  },

  // ---- PRODUCT MODAL ----
  showProductModal(product = null) {
    const modal = document.getElementById('product-modal');
    if (!modal) return;

    const catSelect = document.getElementById('product-category');
    catSelect.innerHTML = AppState.data.categories.map(c =>
      `<option value="${c.id}" ${product && product.category_id === c.id ? 'selected' : ''}>${c.icon} ${c.name}</option>`
    ).join('');

    const emojiGrid = document.getElementById('product-emoji-grid');
    const emojis = ['🍔','🍟','🌭','🍕','🥤','🍺','💧','🍰','🍦','🌮','🌯','🍗','🥗','🍝','🥘','🍲','☕','🧃','🍷','🥂','🍾','🧁','🎂','🍩','🍪','🥧','🍫','🍬','🍭','🥜','🫘','🍿','🧀','🥩','🍖','🦐','🦀','🐟','🥚','🥞','🧇','🥐','🥖','🍞','🥨','🥯','🧄','🧅','🍅','🥑','🌽','🥕','🍆','🥔','🍌','🍎','🍊','🍋','🍇','🍓','🫐','🍒','🍑','🥝','🍍','🥥'];
    const currentEmoji = product ? (product.image || '🍽️') : '🍽️';
    if (typeof InventoryModule !== 'undefined') InventoryModule.selectedEmoji = currentEmoji;
    emojiGrid.innerHTML = emojis.map(e => `
      <button type="button" onclick="InventoryModule.selectEmoji('${e}')"
              class="emoji-btn text-2xl p-2 rounded hover:bg-slate-700 transition-colors ${e === currentEmoji ? 'bg-blue-600 ring-2 ring-blue-400' : ''}">
        ${e}
      </button>
    `).join('');

    document.getElementById('product-name').value = product ? product.name : '';
    document.getElementById('product-price').value = product ? product.price : '';
    document.getElementById('product-stock').value = product ? product.stock : '';
    document.getElementById('product-emoji').value = currentEmoji;
    document.getElementById('product-is-active').checked = product ? product.is_active : true;

    const idField = document.getElementById('product-edit-id');
    idField.value = product ? product.id : '';

    document.getElementById('product-modal-title').innerText = product ? 'Editar Producto' : 'Nuevo Producto';
    document.getElementById('btn-submit-product').innerText = product ? 'Actualizar' : 'Crear Producto';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  hideProductModal() {
    const modal = document.getElementById('product-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  },

  // ---- ALERT MODAL ----
  showAlertModal(message) {
    const modal = document.getElementById('alert-modal');
    if (!modal) {
      alert(message);
      return;
    }
    document.getElementById('alert-message').innerText = message;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  hideAlertModal() {
    const modal = document.getElementById('alert-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  },

  // ---- CONFIRM MODAL ----
  _confirmCallback: null,

  showConfirmModal(message, title, callback) {
    const modal = document.getElementById('confirm-modal');
    if (!modal) {
      callback(confirm(message));
      return;
    }
    document.getElementById('confirm-title').innerText = title;
    document.getElementById('confirm-message').innerText = message;
    this._confirmCallback = callback;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  hideConfirmModal() {
    const modal = document.getElementById('confirm-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  },

  confirmYes() {
    this.hideConfirmModal();
    if (this._confirmCallback) this._confirmCallback(true);
    this._confirmCallback = null;
  },

  confirmNo() {
    this.hideConfirmModal();
    if (this._confirmCallback) this._confirmCallback(false);
    this._confirmCallback = null;
  }
};
