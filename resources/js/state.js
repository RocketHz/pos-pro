/**
         * MÓDULO: State Management
         * Gestión centralizada del estado de la aplicación
         */
export const AppState = {
  data: {
    products: [],
    categories: [],
    cart: [],
    currentTable: null,
  },

  async init() {
    try {
      const response = await fetch('/api/products');
      const data = await response.json();
      this.data.products = data.products;
      this.data.categories = data.categories;

      // Evento personalizado para avisar que los datos están listos
      document.dispatchEvent(new CustomEvent('state:loaded'));
    } catch (error) {
      console.error("Error cargando el estado:", error);
    }
  },

  // Dentro de resources/js/state.js
  addToCart(productId) {
    const product = this.data.products.find(p => p.id === productId);
    if (product) {
      this.data.cart.push({ ...product, tempId: Date.now() });
      // Avisamos a la UI que debe actualizarse
      document.dispatchEvent(new CustomEvent('cart:updated'));
    }
  },
};