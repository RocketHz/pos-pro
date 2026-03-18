<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>POS Pro - Sistema Completo</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Estilos Globales -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Utilidades */
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
        }
        
        /* Animaciones */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Estados de carga */
        .loading {
            opacity: 0.5;
            pointer-events: none;
        }
        
        /* Transiciones de sección */
        .section-content {
            display: none;
        }
        
        .section-content.active {
            display: block;
        }
        
        /* Prevenir comportamiento por defecto de enlaces */
        .nav-link {
            cursor: pointer;
            user-select: none;
        }
        
        .nav-link:hover {
            text-decoration: none;
        }
    </style>
    
    <!-- Estilos de módulos (se cargan dinámicamente o inline) -->
    <style id="module-styles">
        /* Estilos específicos se inyectan aquí */
    </style>
<base target="_blank">
</head>
<body class="flex">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-900 border-r border-slate-700 flex flex-col flex-shrink-0">
        <div class="p-6 border-b border-slate-700">
            <h1 class="text-2xl font-bold gradient-text">
                <i class="fas fa-cash-register mr-2"></i>POS Pro
            </h1>
            <p class="text-xs text-slate-400 mt-1">Sistema de Gestión</p>
        </div>
        
        <nav class="flex-1 py-4" id="main-nav">
            <div class="nav-item" data-section="dashboard">
                <div class="nav-link flex items-center px-6 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors border-l-4 border-transparent">
                    <i class="fas fa-home w-6"></i>
                    <span>Dashboard</span>
                </div>
            </div>
            <div class="nav-item" data-section="tables">
                <div class="nav-link flex items-center px-6 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors border-l-4 border-transparent">
                    <i class="fas fa-chair w-6"></i>
                    <span>Mesas</span>
                </div>
            </div>
            <div class="nav-item" data-section="pos">
                <div class="nav-link flex items-center px-6 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors border-l-4 border-transparent">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span>Venta Rápida</span>
                </div>
            </div>
            <div class="nav-item" data-section="inventory">
                <div class="nav-link flex items-center px-6 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors border-l-4 border-transparent">
                    <i class="fas fa-box w-6"></i>
                    <span>Inventario</span>
                </div>
            </div>
            <div class="nav-item" data-section="reports">
                <div class="nav-link flex items-center px-6 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors border-l-4 border-transparent">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>Reportes</span>
                </div>
            </div>
            <div class="nav-item" data-section="coupons">
                <div class="nav-link flex items-center px-6 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors border-l-4 border-transparent">
                    <i class="fas fa-ticket-alt w-6"></i>
                    <span>Cupones</span>
                </div>
            </div>
        </nav>
        
        <div class="p-4 border-t border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">Admin</p>
                    <p class="text-xs text-slate-400">Cajero Principal</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-hidden bg-slate-950 relative" id="main-content">
        
        <!-- Header -->
        <header class="h-16 border-b border-slate-800 flex items-center justify-between px-8 bg-slate-900/50 backdrop-blur-sm absolute top-0 left-0 right-0 z-10">
            <div class="flex items-center gap-4">
                <h2 id="page-title" class="text-xl font-bold text-white">Dashboard</h2>
                <span id="current-date" class="text-sm text-slate-400"></span>
            </div>
            <div class="flex items-center gap-4">
                <button id="new-sale-btn" class="btn-primary px-4 py-2 rounded-lg text-white text-sm font-medium hidden">
                    <i class="fas fa-plus mr-2"></i>Nueva Venta
                </button>
                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center cursor-pointer hover:bg-slate-600 transition-colors">
                    <i class="fas fa-bell text-slate-400 text-sm"></i>
                </div>
            </div>
        </header>

        <!-- Content Container -->
        <div class="h-full pt-16 overflow-auto" id="content-container">
            
            <!-- Dashboard Section -->
            <section id="dashboard-section" class="section-content p-8 animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="glass-panel rounded-xl p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm">Ventas Hoy</p>
                                <h3 class="text-2xl font-bold text-white mt-1">$<span id="stat-sales">0</span></h3>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-emerald-400"></i>
                            </div>
                        </div>
                        <p class="text-emerald-400 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i>12% vs ayer</p>
                    </div>

                    <div class="glass-panel rounded-xl p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm">Transacciones</p>
                                <h3 class="text-2xl font-bold text-white mt-1" id="stat-transactions">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                <i class="fas fa-receipt text-blue-400"></i>
                            </div>
                        </div>
                        <p class="text-blue-400 text-sm mt-2">Promedio: $<span id="stat-avg">0</span></p>
                    </div>

                    <div class="glass-panel rounded-xl p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm">Mesas Activas</p>
                                <h3 class="text-2xl font-bold text-white mt-1" id="stat-tables">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-amber-500/20 flex items-center justify-center">
                                <i class="fas fa-chair text-amber-400"></i>
                            </div>
                        </div>
                        <p class="text-slate-400 text-sm mt-2">de 12 mesas totales</p>
                    </div>

                    <div class="glass-panel rounded-xl p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm">Propinas</p>
                                <h3 class="text-2xl font-bold text-white mt-1">$<span id="stat-tips">0</span></h3>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                <i class="fas fa-heart text-purple-400"></i>
                            </div>
                        </div>
                        <p class="text-purple-400 text-sm mt-2">Promedio 15%</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="glass-panel rounded-xl p-6">
                        <h3 class="text-lg font-semibold mb-4">Ventas por Hora</h3>
                        <canvas id="chart-sales" height="200"></canvas>
                    </div>
                    <div class="glass-panel rounded-xl p-6">
                        <h3 class="text-lg font-semibold mb-4">Productos Más Vendidos</h3>
                        <canvas id="chart-products" height="200"></canvas>
                    </div>
                </div>
            </section>

            <!-- Tables Section -->
            <section id="tables-section" class="section-content p-8 animate-fade-in hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-slate-400 text-sm">Visualización en tiempo real del estado de las mesas</p>
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="flex items-center gap-2 text-slate-400">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Libre
                        </span>
                        <span class="flex items-center gap-2 text-slate-400">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span> Ocupada
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="tables-grid">
                    <!-- Generated by JS -->
                </div>
            </section>

            <!-- POS Section -->
            <section id="pos-section" class="section-content h-full hidden">
                <div class="flex h-full">
                    <!-- Products Panel -->
                    <div class="flex-1 p-6 overflow-auto">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex gap-3">
                                <select id="pos-category-filter" class="bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500">
                                    <option value="all">Todas las categorías</option>
                                    <option value="food">Comida</option>
                                    <option value="drink">Bebidas</option>
                                    <option value="dessert">Postres</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-3 text-slate-400"></i>
                                <input type="text" id="pos-search" placeholder="Buscar producto..." 
                                       class="w-full bg-slate-800 border border-slate-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="pos-products-grid">
                            <!-- Generated by JS -->
                        </div>
                    </div>

                    <!-- Cart Panel -->
                    <div class="w-96 bg-slate-900 border-l border-slate-700 flex flex-col">
                        <div class="p-6 border-b border-slate-700">
                            <h3 class="text-lg font-bold flex items-center gap-2">
                                <i class="fas fa-shopping-basket text-blue-400"></i>
                                Cuenta Actual
                            </h3>
                            <p class="text-sm text-slate-400 mt-1" id="pos-table-info">Mesa: Sin asignar</p>
                        </div>

                        <div class="flex-1 overflow-auto p-4" id="pos-cart-items">
                            <div class="text-center text-slate-500 mt-8">
                                <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                                <p>Carrito vacío</p>
                            </div>
                        </div>

                        <div class="p-6 border-t border-slate-700 bg-slate-800/50">
                            <div class="mb-4">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" id="pos-coupon" placeholder="Código de cupón" 
                                           class="flex-1 bg-slate-800 border border-slate-700 rounded px-3 py-2 text-sm uppercase">
                                    <button id="btn-apply-coupon" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded text-sm transition-colors">
                                        Aplicar
                                    </button>
                                </div>
                                <div id="pos-coupon-msg" class="text-xs hidden"></div>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-slate-400 mb-2">Propina:</p>
                                <div class="grid grid-cols-4 gap-2" id="pos-tip-buttons">
                                    <button data-tip="0" class="tip-btn border border-slate-600 rounded py-2 text-sm hover:bg-slate-700 transition-colors">0%</button>
                                    <button data-tip="10" class="tip-btn border border-slate-600 rounded py-2 text-sm hover:bg-slate-700 transition-colors">10%</button>
                                    <button data-tip="15" class="tip-btn border border-slate-600 rounded py-2 text-sm hover:bg-slate-700 transition-colors active bg-amber-500 border-amber-500 text-white">15%</button>
                                    <button data-tip="20" class="tip-btn border border-slate-600 rounded py-2 text-sm hover:bg-slate-700 transition-colors">20%</button>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4 text-sm">
                                <div class="flex justify-between text-slate-400">
                                    <span>Subtotal</span>
                                    <span>$<span id="pos-subtotal">0.00</span></span>
                                </div>
                                <div class="flex justify-between text-slate-400 hidden" id="pos-discount-row">
                                    <span>Descuento</span>
                                    <span class="text-emerald-400">-$<span id="pos-discount">0.00</span></span>
                                </div>
                                <div class="flex justify-between text-slate-400">
                                    <span>Propina</span>
                                    <span>$<span id="pos-tip">0.00</span></span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-white pt-2 border-t border-slate-700">
                                    <span>Total</span>
                                    <span>$<span id="pos-total">0.00</span></span>
                                </div>
                            </div>

                            <button id="btn-checkout" class="w-full btn-primary py-3 rounded-lg text-white font-bold text-lg">
                                <i class="fas fa-credit-card mr-2"></i>Cobrar
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Inventory Section -->
            <section id="inventory-section" class="section-content p-8 animate-fade-in hidden">
                <div class="flex justify-between items-center mb-6">
                    <button id="btn-add-product" class="btn-primary px-4 py-2 rounded-lg text-white">
                        <i class="fas fa-plus mr-2"></i>Nuevo Producto
                    </button>
                </div>

                <div class="glass-panel rounded-xl overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-800 border-b border-slate-700">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-400">Producto</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-400">Categoría</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-400">Stock</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-400">Precio</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-400">Estado</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-table" class="divide-y divide-slate-700">
                            <!-- Generated by JS -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Reports Section -->
            <section id="reports-section" class="section-content p-8 animate-fade-in hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="glass-panel rounded-xl p-6">
                        <h3 class="text-lg font-semibold mb-4">Ventas Semanales</h3>
                        <canvas id="chart-weekly"></canvas>
                    </div>
                    <div class="glass-panel rounded-xl p-6 lg:col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Métodos de Pago</h3>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-4 bg-slate-800 rounded-lg">
                                <i class="fas fa-money-bill-wave text-3xl text-emerald-400 mb-2"></i>
                                <p class="text-2xl font-bold">45%</p>
                                <p class="text-sm text-slate-400">Efectivo</p>
                            </div>
                            <div class="p-4 bg-slate-800 rounded-lg">
                                <i class="fas fa-credit-card text-3xl text-blue-400 mb-2"></i>
                                <p class="text-2xl font-bold">40%</p>
                                <p class="text-sm text-slate-400">Tarjeta</p>
                            </div>
                            <div class="p-4 bg-slate-800 rounded-lg">
                                <i class="fas fa-mobile-alt text-3xl text-purple-400 mb-2"></i>
                                <p class="text-2xl font-bold">15%</p>
                                <p class="text-sm text-slate-400">Digital</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4">Transacciones Recientes</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-700">
                                    <th class="pb-3">ID</th>
                                    <th class="pb-3">Fecha</th>
                                    <th class="pb-3">Mesa</th>
                                    <th class="pb-3">Items</th>
                                    <th class="pb-3">Total</th>
                                    <th class="pb-3">Método</th>
                                </tr>
                            </thead>
                            <tbody id="reports-transactions" class="text-sm">
                                <!-- Generated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Coupons Section -->
            <section id="coupons-section" class="section-content p-8 animate-fade-in hidden">
                <div class="flex justify-between items-center mb-6">
                    <button id="btn-create-coupon" class="btn-primary px-4 py-2 rounded-lg text-white">
                        <i class="fas fa-plus mr-2"></i>Crear Cupón
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="coupons-grid">
                    <!-- Generated by JS -->
                </div>
            </section>

        </div>
    </main>

    <!-- Receipt Modal -->
    <div id="receipt-modal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4">
        <div class="bg-white text-black max-w-sm w-full rounded-lg overflow-hidden shadow-2xl transform transition-transform scale-95" id="receipt-content">
            <div class="p-8" style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 10px 10px;">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold">RESTAURANTE DEMO</h3>
                    <p class="text-sm text-gray-600">Av. Principal #123</p>
                    <p class="text-sm text-gray-600">Tel: (555) 123-4567</p>
                    <div class="border-t-2 border-dashed border-gray-400 my-4"></div>
                    <p class="text-sm">Ticket #: <span id="receipt-id">001</span></p>
                    <p class="text-sm" id="receipt-date"></p>
                </div>

                <div class="space-y-2 mb-4 text-sm" id="receipt-items"></div>

                <div class="border-t-2 border-dashed border-gray-400 my-4"></div>

                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>$<span id="receipt-subtotal">0</span></span>
                    </div>
                    <div class="flex justify-between hidden" id="receipt-discount-row">
                        <span>Descuento:</span>
                        <span class="text-green-600">-$<span id="receipt-discount">0</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Propina:</span>
                        <span>$<span id="receipt-tip">0</span></span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t border-gray-300">
                        <span>TOTAL:</span>
                        <span>$<span id="receipt-total">0</span></span>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500">¡Gracias por su preferencia!</p>
                    <div class="mt-4">
                        <i class="fas fa-qrcode text-4xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-100 p-4 flex gap-3">
                <button id="btn-close-receipt" class="flex-1 bg-gray-200 hover:bg-gray-300 py-2 rounded font-medium transition-colors">
                    Cerrar
                </button>
                <button id="btn-print-receipt" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-medium transition-colors">
                    <i class="fas fa-print mr-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript Modular -->
    <script>
        /**
         * MÓDULO: State Management
         * Gestión centralizada del estado de la aplicación
         */
        const AppState = {
            data: {
                tables: [],
                products: [
                    { id: 1, name: 'Hamburguesa Clásica', price: 12.99, category: 'food', stock: 25, image: '🍔' },
                    { id: 2, name: 'Pizza Pepperoni', price: 15.99, category: 'food', stock: 15, image: '🍕' },
                    { id: 3, name: 'Ensalada César', price: 8.99, category: 'food', stock: 20, image: '🥗' },
                    { id: 4, name: 'Tacos (3)', price: 10.99, category: 'food', stock: 30, image: '🌮' },
                    { id: 5, name: 'Coca Cola', price: 2.50, category: 'drink', stock: 50, image: '🥤' },
                    { id: 6, name: 'Cerveza Artesanal', price: 5.99, category: 'drink', stock: 40, image: '🍺' },
                    { id: 7, name: 'Agua Mineral', price: 1.99, category: 'drink', stock: 60, image: '💧' },
                    { id: 8, name: 'Café Americano', price: 3.50, category: 'drink', stock: 45, image: '☕' },
                    { id: 9, name: 'Pastel de Chocolate', price: 6.99, category: 'dessert', stock: 12, image: '🍰' },
                    { id: 10, name: 'Helado (2 bolas)', price: 4.99, category: 'dessert', stock: 18, image: '🍨' },
                    { id: 11, name: 'Flan Napolitano', price: 5.50, category: 'dessert', stock: 15, image: '🍮' },
                    { id: 12, name: 'Brownie', price: 5.99, category: 'dessert', stock: 20, image: '🍫' }
                ],
                cart: [],
                currentTable: null,
                currentTip: 15,
                appliedCoupon: null,
                transactions: [],
                coupons: [
                    { code: 'BIENVENIDO', discount: 20, type: 'percent', active: true },
                    { code: 'DESCUENTO10', discount: 10, type: 'percent', active: true },
                    { code: 'VIP25', discount: 25, type: 'percent', active: true }
                ],
                currentSection: 'dashboard'
            },
            
            listeners: [],
            
            subscribe(callback) {
                this.listeners.push(callback);
            },
            
            notify() {
                this.listeners.forEach(cb => cb(this.data));
            },
            
            set(key, value) {
                this.data[key] = value;
                this.notify();
            },
            
            get(key) {
                return this.data[key];
            },
            
            update(fn) {
                fn(this.data);
                this.notify();
            }
        };

        /**
         * MÓDULO: Router
         * Manejo de navegación entre secciones
         */
        const Router = {
            routes: {
                dashboard: { title: 'Dashboard', element: 'dashboard-section', showNewSale: true },
                tables: { title: 'Gestión de Mesas', element: 'tables-section', showNewSale: false },
                pos: { title: 'Punto de Venta', element: 'pos-section', showNewSale: false },
                inventory: { title: 'Control de Inventario', element: 'inventory-section', showNewSale: true },
                reports: { title: 'Reportes y Estadísticas', element: 'reports-section', showNewSale: false },
                coupons: { title: 'Sistema de Cupones', element: 'coupons-section', showNewSale: false }
            },
            
            currentRoute: null,
            
            init() {
                // Event listeners para navegación
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        const section = item.dataset.section;
                        if (section) this.navigate(section);
                    });
                });
                
                // Botón nueva venta
                document.getElementById('new-sale-btn').addEventListener('click', (e) => {
                    e.preventDefault();
                    this.navigate('pos');
                });
                
                // Navegación inicial
                this.navigate('dashboard');
            },
            
            navigate(routeName) {
                if (!this.routes[routeName] || this.currentRoute === routeName) return;
                
                const route = this.routes[routeName];
                this.currentRoute = routeName;
                
                // Actualizar UI de navegación
                document.querySelectorAll('.nav-item').forEach(item => {
                    const link = item.querySelector('.nav-link');
                    if (item.dataset.section === routeName) {
                        link.classList.add('bg-slate-800', 'border-blue-500', 'text-white');
                        link.classList.remove('border-transparent', 'text-slate-300');
                    } else {
                        link.classList.remove('bg-slate-800', 'border-blue-500', 'text-white');
                        link.classList.add('border-transparent', 'text-slate-300');
                    }
                });
                
                // Ocultar todas las secciones
                document.querySelectorAll('.section-content').forEach(section => {
                    section.classList.add('hidden');
                    section.classList.remove('active');
                });
                
                // Mostrar sección objetivo
                const targetSection = document.getElementById(route.element);
                if (targetSection) {
                    targetSection.classList.remove('hidden');
                    targetSection.classList.add('active');
                }
                
                // Actualizar header
                document.getElementById('page-title').textContent = route.title;
                const newSaleBtn = document.getElementById('new-sale-btn');
                if (route.showNewSale) {
                    newSaleBtn.classList.remove('hidden');
                } else {
                    newSaleBtn.classList.add('hidden');
                }
                
                // Actualizar estado
                AppState.set('currentSection', routeName);
                
                // Trigger evento de cambio de ruta para módulos
                window.dispatchEvent(new CustomEvent('routechange', { detail: { route: routeName } }));
                
                // Scroll al top
                document.getElementById('content-container').scrollTop = 0;
            }
        };

        /**
         * MÓDULO: Dashboard
         */
        const DashboardModule = {
            charts: {},
            
            init() {
                this.initCharts();
                this.updateStats();
                
                // Escuchar cambios de estado
                AppState.subscribe(() => this.updateStats());
                
                // Actualizar fecha
                this.updateDate();
            },
            
            updateDate() {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('current-date').textContent = new Date().toLocaleDateString('es-ES', options);
            },
            
            updateStats() {
                const transactions = AppState.get('transactions');
                const tables = AppState.get('tables');
                
                const todayTotal = transactions.reduce((sum, t) => sum + t.total, 0);
                const todayTips = transactions.reduce((sum, t) => sum + t.tip, 0);
                const activeTables = tables.filter(t => t.status === 'occupied').length;
                
                document.getElementById('stat-sales').textContent = todayTotal.toFixed(0);
                document.getElementById('stat-transactions').textContent = transactions.length;
                document.getElementById('stat-tips').textContent = todayTips.toFixed(0);
                document.getElementById('stat-tables').textContent = activeTables;
                
                const avg = transactions.length > 0 ? todayTotal / transactions.length : 0;
                document.getElementById('stat-avg').textContent = avg.toFixed(2);
            },
            
            initCharts() {
                // Sales Chart
                const salesCtx = document.getElementById('chart-sales').getContext('2d');
                this.charts.sales = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: ['8am', '10am', '12pm', '2pm', '4pm', '6pm', '8pm', '10pm'],
                        datasets: [{
                            label: 'Ventas ($)',
                            data: [120, 190, 300, 500, 200, 400, 600, 350],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#94a3b8' } },
                            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                        }
                    }
                });
                
                // Products Chart
                const productsCtx = document.getElementById('chart-products').getContext('2d');
                this.charts.products = new Chart(productsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Hamburguesas', 'Pizzas', 'Bebidas', 'Postres', 'Otros'],
                        datasets: [{
                            data: [30, 25, 20, 15, 10],
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'right', labels: { color: '#94a3b8' } } }
                    }
                });
            }
        };

        /**
         * MÓDULO: Tables
         */
        const TablesModule = {
            init() {
                this.initializeTables();
                this.render();
                
                window.addEventListener('routechange', (e) => {
                    if (e.detail.route === 'tables') this.render();
                });
            },
            
            initializeTables() {
                const tables = [];
                for (let i = 1; i <= 12; i++) {
                    tables.push({
                        id: i,
                        name: `Mesa ${i}`,
                        status: Math.random() > 0.7 ? 'occupied' : 'free',
                        order: null,
                        time: Math.random() > 0.7 ? '45 min' : null
                    });
                }
                AppState.set('tables', tables);
            },
            
            render() {
                const tables = AppState.get('tables');
                const grid = document.getElementById('tables-grid');
                
                grid.innerHTML = tables.map(table => {
                    const isOccupied = table.status === 'occupied';
                    const bgClass = isOccupied 
                        ? 'bg-gradient-to-br from-red-500 to-red-600' 
                        : 'bg-gradient-to-br from-emerald-500 to-emerald-600';
                    
                    return `
                        <div class="cursor-pointer rounded-xl p-6 text-white relative overflow-hidden transform transition hover:scale-105 hover:shadow-xl ${bgClass}"
                             onclick="TablesModule.selectTable(${table.id})">
                            <div class="absolute top-2 right-2 opacity-50">
                                <i class="fas ${isOccupied ? 'fa-user-clock' : 'fa-check-circle'}"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-1">${table.name}</h3>
                            <p class="text-sm opacity-90">${isOccupied ? 'Ocupada' : 'Libre'}</p>
                            ${table.time ? `<p class="text-xs mt-2 opacity-75"><i class="fas fa-clock mr-1"></i>${table.time}</p>` : ''}
                        </div>
                    `;
                }).join('');
            },
            
            selectTable(tableId) {
                const tables = AppState.get('tables');
                const table = tables.find(t => t.id === tableId);
                
                AppState.set('currentTable', tableId);
                
                if (table.status === 'free') {
                    table.status = 'occupied';
                    table.time = '0 min';
                    AppState.set('tables', [...tables]);
                }
                
                // Cargar orden existente si hay
                if (table.order) {
                    AppState.set('cart', [...table.order]);
                } else {
                    AppState.set('cart', []);
                }
                
                Router.navigate('pos');
            }
        };

        /**
         * MÓDULO: POS (Point of Sale)
         */
        const POSModule = {
            init() {
                this.bindEvents();
                this.renderProducts();
                this.renderCart();
                
                // Escuchar cambios en el carrito
                AppState.subscribe((data) => {
                    if (data.currentSection === 'pos') {
                        this.renderCart();
                        this.updateTotals();
                    }
                });
            },
            
            bindEvents() {
                // Filtros
                document.getElementById('pos-category-filter').addEventListener('change', () => this.renderProducts());
                document.getElementById('pos-search').addEventListener('input', () => this.renderProducts());
                
                // Cupón
                document.getElementById('btn-apply-coupon').addEventListener('click', () => this.applyCoupon());
                
                // Propinas
                document.querySelectorAll('#pos-tip-buttons .tip-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const tip = parseInt(e.target.dataset.tip);
                        this.setTip(tip);
                    });
                });
                
                // Checkout
                document.getElementById('btn-checkout').addEventListener('click', () => this.processPayment());
                
                // Cerrar modal
                document.getElementById('btn-close-receipt').addEventListener('click', () => this.closeReceipt());
                document.getElementById('btn-print-receipt').addEventListener('click', () => this.printReceipt());
            },
            
            renderProducts() {
                const category = document.getElementById('pos-category-filter').value;
                const search = document.getElementById('pos-search').value.toLowerCase();
                const products = AppState.get('products');
                
                let filtered = products;
                if (category !== 'all') filtered = filtered.filter(p => p.category === category);
                if (search) filtered = filtered.filter(p => p.name.toLowerCase().includes(search));
                
                const grid = document.getElementById('pos-products-grid');
                grid.innerHTML = filtered.map(product => `
                    <div class="bg-slate-800 rounded-xl p-4 cursor-pointer border border-slate-700 hover:border-blue-500 transition-all hover:scale-102 hover:shadow-lg"
                         onclick="POSModule.addToCart(${product.id})">
                        <div class="text-4xl mb-2 text-center">${product.image}</div>
                        <h4 class="font-medium text-sm mb-1 truncate">${product.name}</h4>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-400 font-bold">$${product.price.toFixed(2)}</span>
                            <span class="text-xs text-slate-500">Stock: ${product.stock}</span>
                        </div>
                    </div>
                `).join('');
            },
            
            addToCart(productId) {
                const products = AppState.get('products');
                const cart = AppState.get('cart');
                const product = products.find(p => p.id === productId);
                
                const existing = cart.find(item => item.id === productId);
                if (existing) {
                    existing.qty++;
                } else {
                    cart.push({ ...product, qty: 1 });
                }
                
                AppState.set('cart', [...cart]);
                this.updateTableOrder();
            },
            
            updateQty(index, change) {
                const cart = AppState.get('cart');
                cart[index].qty += change;
                
                if (cart[index].qty <= 0) {
                    cart.splice(index, 1);
                }
                
                AppState.set('cart', [...cart]);
                this.updateTableOrder();
            },
            
            removeFromCart(index) {
                const cart = AppState.get('cart');
                cart.splice(index, 1);
                AppState.set('cart', [...cart]);
                this.updateTableOrder();
            },
            
            updateTableOrder() {
                const currentTable = AppState.get('currentTable');
                if (!currentTable) return;
                
                const tables = AppState.get('tables');
                const table = tables.find(t => t.id === currentTable);
                const cart = AppState.get('cart');
                
                if (table) {
                    table.order = cart.length > 0 ? [...cart] : null;
                    if (cart.length === 0) {
                        table.status = 'free';
                        table.time = null;
                    }
                    AppState.set('tables', [...tables]);
                }
            },
            
            renderCart() {
                const cart = AppState.get('cart');
                const container = document.getElementById('pos-cart-items');
                const tableId = AppState.get('currentTable');
                
                // Actualizar info de mesa
                const tableInfo = tableId ? `Mesa ${tableId}` : 'Mostrador';
                document.getElementById('pos-table-info').textContent = `Mesa: ${tableInfo}`;
                
                if (cart.length === 0) {
                    container.innerHTML = `
                        <div class="text-center text-slate-500 mt-8">
                            <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                            <p>Carrito vacío</p>
                        </div>
                    `;
                    return;
                }
                
                container.innerHTML = cart.map((item, index) => `
                    <div class="flex justify-between items-center bg-slate-800 rounded-lg p-3 mb-2">
                        <div class="flex-1 min-w-0 mr-2">
                            <h4 class="font-medium text-sm truncate">${item.name}</h4>
                            <p class="text-xs text-slate-400">$${item.price.toFixed(2)} c/u</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button onclick="POSModule.updateQty(${index}, -1)" 
                                    class="w-6 h-6 rounded bg-slate-700 hover:bg-slate-600 flex items-center justify-center text-xs transition-colors">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="text-sm font-medium w-4 text-center">${item.qty}</span>
                            <button onclick="POSModule.updateQty(${index}, 1)" 
                                    class="w-6 h-6 rounded bg-slate-700 hover:bg-slate-600 flex items-center justify-center text-xs transition-colors">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button onclick="POSModule.removeFromCart(${index})" 
                                    class="ml-1 text-red-400 hover:text-red-300 w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            },
            
            setTip(percentage) {
                AppState.set('currentTip', percentage);
                
                document.querySelectorAll('#pos-tip-buttons .tip-btn').forEach(btn => {
                    const tip = parseInt(btn.dataset.tip);
                    if (tip === percentage) {
                        btn.classList.add('active', 'bg-amber-500', 'border-amber-500', 'text-white');
                    } else {
                        btn.classList.remove('active', 'bg-amber-500', 'border-amber-500', 'text-white');
                    }
                });
                
                this.updateTotals();
            },
            
            applyCoupon() {
                const code = document.getElementById('pos-coupon').value.toUpperCase();
                const coupons = AppState.get('coupons');
                const coupon = coupons.find(c => c.code === code && c.active);
                const msgEl = document.getElementById('pos-coupon-msg');
                
                if (coupon) {
                    AppState.set('appliedCoupon', coupon);
                    msgEl.textContent = `¡Cupón aplicado! ${coupon.discount}% de descuento`;
                    msgEl.className = 'text-xs text-emerald-400 mt-1 block';
                } else {
                    AppState.set('appliedCoupon', null);
                    msgEl.textContent = 'Cupón inválido o expirado';
                    msgEl.className = 'text-xs text-red-400 mt-1 block';
                }
                
                this.updateTotals();
            },
            
            updateTotals() {
                const cart = AppState.get('cart');
                const currentTip = AppState.get('currentTip');
                const appliedCoupon = AppState.get('appliedCoupon');
                
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                let discount = 0;
                
                if (appliedCoupon) {
                    discount = subtotal * (appliedCoupon.discount / 100);
                    document.getElementById('pos-discount-row').classList.remove('hidden');
                    document.getElementById('pos-discount').textContent = discount.toFixed(2);
                } else {
                    document.getElementById('pos-discount-row').classList.add('hidden');
                }
                
                const afterDiscount = subtotal - discount;
                const tipAmount = afterDiscount * (currentTip / 100);
                const total = afterDiscount + tipAmount;
                
                document.getElementById('pos-subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('pos-tip').textContent = tipAmount.toFixed(2);
                document.getElementById('pos-total').textContent = total.toFixed(2);
                
                return { subtotal, discount, tipAmount, total };
            },
            
            processPayment() {
                const cart = AppState.get('cart');
                if (cart.length === 0) {
                    alert('El carrito está vacío');
                    return;
                }
                
                const totals = this.updateTotals();
                const transactions = AppState.get('transactions');
                const currentTable = AppState.get('currentTable');
                
                const transaction = {
                    id: String(transactions.length + 1).padStart(3, '0'),
                    date: new Date(),
                    table: currentTable ? `Mesa ${currentTable}` : 'Mostrador',
                    items: cart.reduce((sum, item) => sum + item.qty, 0),
                    subtotal: totals.subtotal,
                    discount: totals.discount,
                    tip: totals.tipAmount,
                    total: totals.total,
                    coupon: AppState.get('appliedCoupon')?.code || null
                };
                
                AppState.set('transactions', [...transactions, transaction]);
                
                // Actualizar inventario
                const products = AppState.get('products');
                cart.forEach(item => {
                    const product = products.find(p => p.id === item.id);
                    if (product) product.stock -= item.qty;
                });
                AppState.set('products', [...products]);
                
                // Mostrar recibo
                this.showReceipt(transaction);
                
                // Reset
                this.resetPOS();
            },
            
            resetPOS() {
                const currentTable = AppState.get('currentTable');
                if (currentTable) {
                    const tables = AppState.get('tables');
                    const table = tables.find(t => t.id === currentTable);
                    if (table) {
                        table.status = 'free';
                        table.order = null;
                        table.time = null;
                        AppState.set('tables', [...tables]);
                    }
                }
                
                AppState.set('cart', []);
                AppState.set('currentTable', null);
                AppState.set('appliedCoupon', null);
                AppState.set('currentTip', 15);
                
                document.getElementById('pos-coupon').value = '';
                document.getElementById('pos-coupon-msg').className = 'hidden';
                this.setTip(15);
            },
            
            showReceipt(transaction) {
                const cart = AppState.get('cart');
                
                document.getElementById('receipt-id').textContent = transaction.id;
                document.getElementById('receipt-date').textContent = transaction.date.toLocaleString('es-ES');
                document.getElementById('receipt-subtotal').textContent = transaction.subtotal.toFixed(2);
                document.getElementById('receipt-tip').textContent = transaction.tip.toFixed(2);
                document.getElementById('receipt-total').textContent = transaction.total.toFixed(2);
                
                const discountRow = document.getElementById('receipt-discount-row');
                if (transaction.discount > 0) {
                    discountRow.classList.remove('hidden');
                    document.getElementById('receipt-discount').textContent = transaction.discount.toFixed(2);
                } else {
                    discountRow.classList.add('hidden');
                }
                
                document.getElementById('receipt-items').innerHTML = cart.map(item => `
                    <div class="flex justify-between">
                        <span>${item.qty}x ${item.name}</span>
                        <span>$${(item.price * item.qty).toFixed(2)}</span>
                    </div>
                `).join('');
                
                const modal = document.getElementById('receipt-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                setTimeout(() => {
                    document.getElementById('receipt-content').classList.remove('scale-95');
                    document.getElementById('receipt-content').classList.add('scale-100');
                }, 10);
            },
            
            closeReceipt() {
                const content = document.getElementById('receipt-content');
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                
                setTimeout(() => {
                    const modal = document.getElementById('receipt-modal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            },
            
            printReceipt() {
                window.print();
            }
        };

        /**
         * MÓDULO: Inventory
         */
        const InventoryModule = {
            init() {
                this.render();
                this.bindEvents();
                
                window.addEventListener('routechange', (e) => {
                    if (e.detail.route === 'inventory') this.render();
                });
            },
            
            bindEvents() {
                document.getElementById('btn-add-product').addEventListener('click', () => this.addProduct());
            },
            
            render() {
                const products = AppState.get('products');
                const tbody = document.getElementById('inventory-table');
                
                tbody.innerHTML = products.map(product => {
                    const status = product.stock < 10 ? 'Bajo' : product.stock < 20 ? 'Medio' : 'OK';
                    const statusColor = product.stock < 10 ? 'text-red-400' : product.stock < 20 ? 'text-amber-400' : 'text-emerald-400';
                    const barColor = product.stock < 10 ? 'bg-red-500' : 'bg-emerald-500';
                    const width = Math.min((product.stock / 50) * 100, 100);
                    
                    return `
                        <tr class="hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">${product.image}</span>
                                    <div>
                                        <p class="font-medium text-white">${product.name}</p>
                                        <p class="text-xs text-slate-400">ID: ${String(product.id).padStart(3, '0')}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-400 capitalize">${product.category}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium w-8">${product.stock}</span>
                                    <div class="w-16 h-2 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full ${barColor}" style="width: ${width}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium">$${product.price.toFixed(2)}</td>
                            <td class="px-6 py-4 ${statusColor} text-sm font-medium">${status}</td>
                            <td class="px-6 py-4">
                                <button onclick="InventoryModule.editStock(${product.id})" class="text-blue-400 hover:text-blue-300 mr-3 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="InventoryModule.deleteProduct(${product.id})" class="text-red-400 hover:text-red-300 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            },
            
            addProduct() {
                const name = prompt('Nombre del producto:');
                if (!name) return;
                
                const price = parseFloat(prompt('Precio:', '9.99'));
                const category = prompt('Categoría (food/drink/dessert):', 'food');
                const stock = parseInt(prompt('Stock inicial:', '20'));
                
                if (price && category && stock >= 0) {
                    const products = AppState.get('products');
                    products.push({
                        id: Math.max(...products.map(p => p.id)) + 1,
                        name,
                        price,
                        category,
                        stock,
                        image: '📦'
                    });
                    AppState.set('products', [...products]);
                    this.render();
                }
            },
            
            editStock(productId) {
                const products = AppState.get('products');
                const product = products.find(p => p.id === productId);
                const newStock = parseInt(prompt(`Nuevo stock para ${product.name}:`, product.stock));
                
                if (newStock >= 0) {
                    product.stock = newStock;
                    AppState.set('products', [...products]);
                    this.render();
                }
            },
            
            deleteProduct(productId) {
                if (!confirm('¿Eliminar este producto?')) return;
                
                const products = AppState.get('products');
                const index = products.findIndex(p => p.id === productId);
                products.splice(index, 1);
                AppState.set('products', [...products]);
                this.render();
            }
        };

        /**
         * MÓDULO: Reports
         */
        const ReportsModule = {
            chart: null,
            
            init() {
                this.initChart();
                
                window.addEventListener('routechange', (e) => {
                    if (e.detail.route === 'reports') this.render();
                });
            },
            
            initChart() {
                const ctx = document.getElementById('chart-weekly').getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                        datasets: [{
                            label: 'Ventas',
                            data: [1200, 1900, 1500, 2200, 2800, 3500, 3100],
                            backgroundColor: '#10b981',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#94a3b8' } },
                            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                        }
                    }
                });
            },
            
            render() {
                const transactions = AppState.get('transactions');
                const tbody = document.getElementById('reports-transactions');
                
                tbody.innerHTML = transactions.slice(-10).reverse().map(t => `
                    <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                        <td class="py-3 font-mono text-slate-400">#${t.id}</td>
                        <td class="py-3">${t.date.toLocaleTimeString('es-ES')}</td>
                        <td class="py-3">${t.table}</td>
                        <td class="py-3">${t.items} items</td>
                        <td class="py-3 font-medium">$${t.total.toFixed(2)}</td>
                        <td class="py-3"><i class="fas fa-credit-card text-blue-400"></i></td>
                    </tr>
                `).join('');
            }
        };

        /**
         * MÓDULO: Coupons
         */
        const CouponsModule = {
            init() {
                this.render();
                this.bindEvents();
                
                window.addEventListener('routechange', (e) => {
                    if (e.detail.route === 'coupons') this.render();
                });
            },
            
            bindEvents() {
                document.getElementById('btn-create-coupon').addEventListener('click', () => this.createCoupon());
            },
            
            render() {
                const coupons = AppState.get('coupons');
                const grid = document.getElementById('coupons-grid');
                
                grid.innerHTML = coupons.map((coupon, index) => {
                    const borderColor = coupon.active ? 'border-emerald-500' : 'border-red-500';
                    const statusClass = coupon.active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400';
                    const statusText = coupon.active ? 'Activo' : 'Inactivo';
                    
                    return `
                        <div class="glass-panel rounded-xl p-6 border-l-4 ${borderColor}">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-white font-mono">${coupon.code}</h3>
                                    <p class="text-slate-400 text-sm mt-1">Descuento: ${coupon.discount}%</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs ${statusClass}">
                                    ${statusText}
                                </span>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button onclick="CouponsModule.toggleCoupon(${index})" 
                                        class="flex-1 bg-slate-700 hover:bg-slate-600 py-2 rounded text-sm transition-colors">
                                    ${coupon.active ? 'Desactivar' : 'Activar'}
                                </button>
                                <button onclick="CouponsModule.deleteCoupon(${index})" 
                                        class="px-4 bg-red-500/20 hover:bg-red-500/30 text-red-400 py-2 rounded text-sm transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            },
            
            createCoupon() {
                const code = prompt('Ingrese código del cupón (ej: VERANO20):');
                if (!code) return;
                
                const discount = parseInt(prompt('Porcentaje de descuento:', '10'));
                if (discount > 0 && discount <= 100) {
                    const coupons = AppState.get('coupons');
                    coupons.push({
                        code: code.toUpperCase(),
                        discount: discount,
                        type: 'percent',
                        active: true
                    });
                    AppState.set('coupons', [...coupons]);
                    this.render();
                }
            },
            
            toggleCoupon(index) {
                const coupons = AppState.get('coupons');
                coupons[index].active = !coupons[index].active;
                AppState.set('coupons', [...coupons]);
                this.render();
            },
            
            deleteCoupon(index) {
                if (!confirm('¿Eliminar este cupón?')) return;
                
                const coupons = AppState.get('coupons');
                coupons.splice(index, 1);
                AppState.set('coupons', [...coupons]);
                this.render();
            }
        };

        /**
         * INICIALIZACIÓN DE LA APLICACIÓN
         */
        document.addEventListener('DOMContentLoaded', () => {
            // Inicializar módulos en orden
            Router.init();
            DashboardModule.init();
            TablesModule.init();
            POSModule.init();
            InventoryModule.init();
            ReportsModule.init();
            CouponsModule.init();
            
            // Cargar datos de ejemplo
            loadMockData();
        });

        // Datos de ejemplo para demo
        function loadMockData() {
            // Transacciones de ejemplo
            const mockTransactions = [
                { id: '001', date: new Date(Date.now() - 3600000), table: 'Mesa 3', items: 4, subtotal: 45.96, discount: 0, tip: 6.89, total: 52.85, coupon: null },
                { id: '002', date: new Date(Date.now() - 7200000), table: 'Mesa 7', items: 2, subtotal: 28.50, discount: 5.70, tip: 3.42, total: 26.22, coupon: 'DESCUENTO20' },
                { id: '003', date: new Date(Date.now() - 10800000), table: 'Mostrador', items: 1, subtotal: 12.99, discount: 0, tip: 1.95, total: 14.94, coupon: null }
            ];
            
            AppState.set('transactions', mockTransactions);
        }

        // Exponer funciones necesarias globalmente para los onclick inline
        window.TablesModule = TablesModule;
        window.POSModule = POSModule;
        window.InventoryModule = InventoryModule;
        window.CouponsModule = CouponsModule;
    </script>
</body>
</html>