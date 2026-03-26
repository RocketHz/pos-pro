import './bootstrap';

import { AppState } from './state';
import { UIRenderer } from './ui-renderer';

window.AppState = AppState;
window.UIRenderer = UIRenderer;

// Escuchar cuando los datos se carguen desde la base de datos
document.addEventListener('state:loaded', () => {
  UIRenderer.renderCategories(AppState.data.categories);
  UIRenderer.renderProducts(AppState.data.products);
});

// Escuchar cuando el carrito cambie
document.addEventListener('cart:updated', () => {
  UIRenderer.updateCartUI(AppState.data.cart);
});

document.addEventListener('DOMContentLoaded', () => {
  AppState.init();
});
