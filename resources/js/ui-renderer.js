// resources/js/ui-renderer.js

export const UIRenderer = {
  // Renderiza las tarjetas de productos en el grid
  renderProducts(products) {
    const grid = document.getElementById('products-grid');
    if (!grid) return;

    grid.innerHTML = products.map(product => `
            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 hover:border-blue-500 transition-all cursor-pointer group"
                 onclick="AppState.addToCart(${product.id})">
                <div class="text-4xl mb-2 group-hover:scale-110 transition-transform">${product.image || '🍔'}</div>
                <h3 class="font-bold text-white">${product.name}</h3>
                <p class="text-blue-400 font-bold">$${parseFloat(product.price).toFixed(2)}</p>
                <p class="text-gray-500 text-xs">Stock: ${product.stock}</p>
            </div>
        `).join('');
  },

  // Renderiza los botones de categorías
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

  // Actualiza la lista lateral del carrito
  updateCartUI(cart) {
    const cartContainer = document.getElementById('cart-items');
    const totalElement = document.getElementById('cart-total');
    if (!cartContainer) return;

    if (cart.length === 0) {
      cartContainer.innerHTML = '<p class="text-gray-500 text-center py-4">El carrito está vacío</p>';
      if (totalElement) totalElement.innerText = '$0.00';
      return;
    }

    let total = 0;
    cartContainer.innerHTML = cart.map(item => {
      total += parseFloat(item.price);
      return `
                <div class="flex justify-between items-center bg-gray-700/50 p-2 rounded-lg mb-2">
                    <div>
                        <p class="text-white text-sm font-medium">${item.name}</p>
                        <p class="text-blue-400 text-xs">$${parseFloat(item.price).toFixed(2)}</p>
                    </div>
                    <button onclick="AppState.removeFromCart(${item.tempId})" class="text-red-400 hover:text-red-300">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
    }).join('');

    if (totalElement) totalElement.innerText = `$${total.toFixed(2)}`;
  }
};