// resources/js/inventory.js

window.InventoryModule = {
  selectedEmoji: '🍽️',

  selectEmoji(emoji) {
    this.selectedEmoji = emoji;
    document.getElementById('product-emoji').value = emoji;

    // Visual feedback
    document.querySelectorAll('.emoji-btn').forEach(btn => {
      btn.classList.remove('bg-blue-600', 'ring-2', 'ring-blue-400');
      if (btn.textContent.trim() === emoji) {
        btn.classList.add('bg-blue-600', 'ring-2', 'ring-blue-400');
      }
    });
  },

  async editProduct(productId) {
    const product = AppState.data.products.find(p => p.id === productId);
    if (!product) return;

    UIRenderer.showProductModal(product);
  },

  async deleteProduct(productId) {
    UIRenderer.showConfirmModal('¿Estás seguro de eliminar este producto?', 'Eliminar Producto', async (confirmed) => {
      if (!confirmed) return;

      try {
        const response = await fetch(`/api/products/${productId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        const result = await response.json();

        if (response.ok) {
          AppState.showAlert('✅ ' + result.message);
          await this.reloadInventory();
        } else {
          AppState.showAlert('❌ ' + (result.message || 'Error al eliminar'));
        }
      } catch (err) {
        console.error('Error:', err);
        AppState.showAlert('Error de conexión');
      }
    });
  },

  addProduct() {
    UIRenderer.showProductModal();
  },

  async submitProduct() {
    const name = document.getElementById('product-name').value.trim();
    const price = document.getElementById('product-price').value;
    const stock = document.getElementById('product-stock').value;
    const categoryId = document.getElementById('product-category').value;
    const image = document.getElementById('product-emoji').value || '🍽️';
    const isActive = document.getElementById('product-is-active').checked;
    const editId = document.getElementById('product-edit-id').value;

    if (!name || !price || !stock || !categoryId) {
      alert('Por favor completa todos los campos obligatorios');
      return;
    }

    const payload = {
      name,
      price: parseFloat(price),
      stock: parseInt(stock),
      category_id: parseInt(categoryId),
      image,
      is_active: isActive
    };

    try {
      let response;
      if (editId) {
        response = await fetch(`/api/products/${editId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(payload)
        });
      } else {
        response = await fetch('/api/products', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(payload)
        });
      }

      const result = await response.json();

      if (response.ok) {
        alert('✅ ' + result.message);
        UIRenderer.hideProductModal();
        await this.reloadInventory();
      } else {
        alert('❌ ' + (result.message || 'Error'));
      }
    } catch (err) {
      console.error('Error:', err);
      alert('Error de conexión');
    }
  },

  async reloadInventory() {
    try {
      const res = await fetch('/api/products/all');
      const data = await res.json();
      UIRenderer.renderInventory(data.products || []);
      // Also reload products for POS
      await AppState.init();
    } catch (err) {
      console.error('Error recargando inventario:', err);
    }
  }
};
