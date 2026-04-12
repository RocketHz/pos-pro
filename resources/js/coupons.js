// resources/js/coupons.js

window.CouponModule = {
  async editCoupon(couponId) {
    const res = await fetch('/api/coupons');
    const data = await res.json();
    const coupon = data.coupons?.find(c => c.id === couponId);
    if (!coupon) return;

    const code = prompt('Código del cupón:', coupon.code);
    if (code === null) return;

    const type = prompt('Tipo (percentage o fixed):', coupon.type);
    if (type === null) return;

    const value = prompt('Valor:', coupon.value);
    if (value === null) return;

    const minPurchase = prompt('Compra mínima (0 para ninguna):', coupon.min_purchase);
    if (minPurchase === null) return;

    const usageLimit = prompt('Límite total de usos (vacío = ilimitado):', coupon.usage_limit || '');
    if (usageLimit === null) return;

    const usageLimitPerCustomer = prompt('Límite por cliente (vacío = ilimitado):', coupon.usage_limit_per_customer || '');

    try {
      const response = await fetch(`/api/coupons/${couponId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          code,
          type,
          value: parseFloat(value),
          min_purchase: parseFloat(minPurchase) || 0,
          usage_limit: usageLimit ? parseInt(usageLimit) : null,
          usage_limit_per_customer: usageLimitPerCustomer ? parseInt(usageLimitPerCustomer) : null
        })
      });

      const result = await response.json();

      if (response.ok) {
        AppState.showAlert('✅ ' + result.message);
        this.loadCoupons();
      } else {
        AppState.showAlert('❌ ' + (result.message || 'Error al actualizar'));
      }
    } catch (err) {
      console.error('Error:', err);
      AppState.showAlert('Error de conexión');
    }
  },

  async deleteCoupon(couponId) {
    UIRenderer.showConfirmModal('¿Estás seguro de eliminar este cupón?', 'Eliminar Cupón', async (confirmed) => {
      if (!confirmed) return;

      try {
        const response = await fetch(`/api/coupons/${couponId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        const result = await response.json();

        if (response.ok) {
          AppState.showAlert('✅ ' + result.message);
          this.loadCoupons();
        } else {
          AppState.showAlert('❌ ' + (result.message || 'Error al eliminar'));
        }
      } catch (err) {
        console.error('Error:', err);
        AppState.showAlert('Error de conexión');
      }
    });
  },

  async toggleActive(couponId, isActive) {
    try {
      const res = await fetch('/api/coupons');
      const data = await res.json();
      const coupon = data.coupons?.find(c => c.id === couponId);
      if (!coupon) return;

      const response = await fetch(`/api/coupons/${couponId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          code: coupon.code,
          type: coupon.type,
          value: parseFloat(coupon.value),
          min_purchase: parseFloat(coupon.min_purchase) || 0,
          usage_limit: coupon.usage_limit || null,
          usage_limit_per_customer: coupon.usage_limit_per_customer || null,
          is_active: isActive
        })
      });

      if (!response.ok) {
        AppState.showAlert('Error al actualizar estado del cupón');
        this.loadCoupons();
      }
    } catch (err) {
      console.error('Error:', err);
      this.loadCoupons();
    }
  },

  async createCoupon() {
    const code = prompt('Código del cupón (ej: DESCUENTO20):');
    if (!code) return;

    const type = prompt('Tipo: percentage o fixed', 'fixed');
    if (!type) return;

    const value = prompt('Valor del descuento:');
    if (!value) return;

    const minPurchase = prompt('Compra mínima (0 para ninguna):', '0');

    const usageLimit = prompt('Límite total de usos (vacío = ilimitado):', '');

    const usageLimitPerCustomer = prompt('Límite por cliente (vacío = ilimitado, ej: 1 para primer cliente):', '');

    try {
      const response = await fetch('/api/coupons', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          code,
          type,
          value: parseFloat(value),
          min_purchase: parseFloat(minPurchase) || 0,
          usage_limit: usageLimit ? parseInt(usageLimit) : null,
          usage_limit_per_customer: usageLimitPerCustomer ? parseInt(usageLimitPerCustomer) : null
        })
      });

      const result = await response.json();

      if (response.ok) {
        AppState.showAlert('✅ ' + result.message);
        this.loadCoupons();
      } else {
        AppState.showAlert('❌ ' + (result.message || 'Error al crear'));
      }
    } catch (err) {
      console.error('Error:', err);
      AppState.showAlert('Error de conexión');
    }
  },

  async loadCoupons() {
    try {
      const res = await fetch('/api/coupons');
      const data = await res.json();
      UIRenderer.renderCoupons(data.coupons || []);
    } catch (err) {
      console.error('Error cargando cupones:', err);
    }
  }
};
