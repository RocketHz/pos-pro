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
                                    <button data-tip="15" class="tip-btn border rounded py-2 text-sm hover:bg-slate-700 transition-colors active bg-amber-500 border-amber-500 text-white">15%</button>
                                    <button data-tip="20" class="tip-btn border border-slate-600 rounded py-2 text-sm hover:bg-slate-700 transition-colors">20%</button>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4 text-sm">
                                <div class="flex justify-between text-slate-400">
                                    <span>Subtotal</span>
                                    <span>$<span id="pos-subtotal">0.00</span></span>
                                </div>
                                <div class="flex justify-between text-slate-400 " id="pos-discount-row">
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
                    <div class="flex justify-between" id="receipt-discount-row">
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

</body>
</html>