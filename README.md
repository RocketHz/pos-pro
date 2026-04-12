# 🚀 POS Pro - Sistema de Gestión de Restaurante

**Laravel 11 + PostgreSQL + Blade + Tailwind CSS + Chart.js**

Sistema completo para gestión de restaurantes con punto de venta (POS), inventario, cupones, reportes y gestión de mesas.

---

## 📌 Estado del Proyecto

| Componente        | Tecnología                        |
|--------------------|-----------------------------------|
| **Backend**        | Laravel 11.x / PHP 8.3            |
| **Base de Datos**  | PostgreSQL                        |
| **Frontend**       | Blade Templates + Tailwind CSS    |
| **Build**          | Vite                              |
| **Gráficos**       | Chart.js                          |
| **Autenticación**  | Laravel Breeze                    |

---

## ✅ Funcionalidades Implementadas

### Dashboard
- [x] Ventas del día, transacciones, ticket promedio, propinas (datos reales)
- [x] Gráfico de ventas por hora (últimas 12 horas)
- [x] Top productos más vendidos (Chart.js doughnut)
- [x] Botón "Nueva Venta" → redirige al POS
- [x] Se actualiza automáticamente tras cada venta
- [x] Métodos de pago del día con porcentajes reales

### Punto de Venta (POS)
- [x] Catálogo de productos con filtro por categoría y búsqueda
- [x] Carrito con cantidades (+/-) y eliminación
- [x] Selector de mesa (dropdown en panel del carrito)
- [x] Sistema de propinas (0%, 10%, 15%, 20%)
- [x] Cupones validados desde servidor (`/api/coupons/check`)
- [x] **Modal de cobro con 4 métodos de pago:**
  - 💵 Efectivo
  - 💳 Punto de Venta
  - 📱 Pago Móvil / Transferencia (requiere referencia)
  - 🔹 Otro (requiere especificar)
- [x] Modal de recibo/ticket con opción de imprimir
- [x] Stock se descuenta automáticamente al cobrar
- [x] Mesa se marca como ocupada al vender

### Gestión de Mesas
- [x] Vista de grilla con estado visual (Libre / Ocupada / Reservada)
- [x] **Modal de acciones por mesa:**
  - Libre → "Ocupar e Ir a Venta" / "Reservar"
  - Ocupada → "Liberar Mesa"
  - Reservada → "Ocupar e Ir a Venta" / "Liberar"
- [x] Sincronización con base de datos en tiempo real

### Inventario
- [x] Tabla con todos los productos (nombre, categoría, stock, precio, estado)
- [x] **Modal completo para crear/editar productos:**
  - Selector visual de 60+ emojis
  - Campos: nombre, categoría, precio, stock, activo/inactivo
- [x] Eliminación con modal de confirmación
- [x] Datos persistidos en PostgreSQL

### Cupones
- [x] Validación server-side contra base de datos
- [x] Tipos: porcentaje (`percentage`) y fijo (`fixed`)
- [x] Compra mínima, descuento máximo, fecha de expiración
- [x] **Límite total de usos** (`usage_limit`)
- [x] **Límite por cliente** (`usage_limit_per_customer`) — rastreado por user_id o IP
- [x] **Auto-desactivación** cuando se agotan los usos
- [x] Toggle activo/inactivo en la UI
- [x] Indicador visual de cupón agotado

### Reportes
- [x] Ventas semanales (gráfico de barras)
- [x] Métodos de pago del día con estadísticas reales
- [x] Transacciones recientes con método de pago visible
- [x] Datos actualizados al navegar a la sección

### Sesión de Usuario
- [x] Login / Registro / Logout (Breeze)
- [x] Perfil editable

---

## 🗂️ Estructura de Archivos Clave

### Backend
```
app/Http/Controllers/Api/
├── ProductController.php     # CRUD productos
├── OrderController.php       # Crear ordenes, stock, pagos
├── TableController.php       # Mesas (index, updateStatus, free)
├── CouponController.php      # Validar, CRUD, trackUsage, auto-deactivate
├── DashboardController.php   # Stats, weekly sales, payment methods, transactions

app/Models/
├── Product.php
├── Order.php
├── OrderItem.php
├── Table.php
├── Category.php
├── Coupon.php

routes/api.php                # Todas las rutas API
```

### Frontend
```
resources/js/
├── state.js           # AppState centralizado, carrito, checkout
├── ui-renderer.js     # Renderiza productos, carrito, mesas, charts, modales
├── inventory.js       # CRUD productos (usa modal)
├── coupons.js         # CRUD cupones, toggle active
├── app.js             # Wiring de eventos y navegación

resources/views/
├── layouts/app.blade.php   # Layout principal + todos los modales
├── dashboard.blade.php     # Vista principal (usa x-app-layout)
```

### Base de Datos (Tablas principales)
- `products` — nombre, precio, stock, categoría, imagen/emoji, is_active
- `categories` — nombre, icono
- `tables` — número, capacidad, estado (available/occupied/reserved)
- `orders` — table_id, user_id, total, discount, tip, payment_method, payment_reference, status
- `order_items` — order_id, product_id, quantity, price
- `coupons` — code, type, value, usage_limit, usage_limit_per_customer, is_active, expires_at
- `coupon_usage` — tracking de uso por cliente

---

## 📡 API Routes

| Method   | Route                          | Descripción                      |
|----------|--------------------------------|----------------------------------|
| GET      | `/api/products`                | Productos activos + categorías   |
| GET      | `/api/products/all`            | Todos los productos (inventario) |
| POST     | `/api/products`                | Crear producto                   |
| PUT      | `/api/products/{id}`           | Actualizar producto              |
| DELETE   | `/api/products/{id}`           | Eliminar producto                |
| POST     | `/api/orders`                  | Registrar venta                  |
| GET      | `/api/orders`                  | Lista de órdenes del día         |
| GET      | `/api/orders/{id}`             | Detalle de orden                 |
| GET      | `/api/tables`                  | Todas las mesas                  |
| PATCH    | `/api/tables/{id}`             | Cambiar estado de mesa           |
| POST     | `/api/tables/{id}/free`        | Liberar mesa                     |
| POST     | `/api/coupons/check`           | Validar cupón (server-side)      |
| GET      | `/api/coupons`                 | Lista de cupones                 |
| POST     | `/api/coupons`                 | Crear cupón                      |
| PUT      | `/api/coupons/{id}`            | Actualizar cupón                 |
| DELETE   | `/api/coupons/{id}`            | Eliminar cupón                   |
| GET      | `/api/dashboard-stats`         | Estadísticas del dashboard       |
| GET      | `/api/reports/weekly-sales`    | Ventas de la semana              |
| GET      | `/api/reports/payment-methods` | Métodos de pago del día          |
| GET      | `/api/reports/recent-transactions` | Últimas 20 transacciones    |

---

## 🚀 Comandos Rápidos

```bash
php artisan serve          # Iniciar servidor (http://127.0.0.1:8000)
php artisan migrate        # Ejecutar migraciones pendientes
php artisan db:seed        # Poblar con datos de ejemplo
npm run dev                # Compilar assets en modo desarrollo
npm run build              # Compilar assets para producción
```

**Credenciales de prueba:** `admin@example.com` / `password`

**Cupones de ejemplo:** `PROMO10`, `DESCUENTO20`, `BIENVENIDO`, `PRIMERO5`

---

## 🛠️ Instrucciones para IA / Desarrolladores

1. **Consistencia visual:** Mantener estética "Dark Mode" con clases Tailwind y la variable global `AppState`.
2. **Seguridad:** Todas las peticiones POST deben incluir token CSRF. Usar FormRequest validation en Laravel.
3. **Base de datos:** Decimal para precios, enum para estados.
4. **Modales:** Usar el sistema de modales del layout (alert, confirm, product, checkout, table-action, receipt) en vez de `alert()`, `confirm()`, `prompt()` nativos.

---

## 📋 Tareas Pendientes

### Sesión y Perfil
- [ ] Mostrar nombre del usuario logueado en el sidebar
- [ ] Botón de cerrar sesión en el sidebar / header
- [ ] Página de perfil funcional (editar nombre, email, password)
- [ ] Redirigir a login si no hay sesión activa

### Navegación e Interactividad
- [ ] Indicador visual de sección activa en el sidebar (ya parcialmente funciona)
- [ ] Botones del dashboard "12% vs ayer" → calcular comparativa real
- [ ] Filtro por categoría en POS (`pos-category-filter`) — no implementado
- [ ] Búsqueda en POS (`pos-search`) — no implementado
- [ ] Loading states / spinners al hacer peticiones fetch
- [ ] Toasts/notificaciones en lugar de alert modales para acciones exitosas

### POS
- [ ] Soporte para múltiples items por producto en carrito (agrupar por product_id)
- [ ] Botón "Cancelar Venta" en checkout
- [ ] Vista de "órdenes pendientes" (mesas con orden abierta pero sin pagar)
- [ ] Dividir cuenta por mesa
- [ ] Reimprimir ticket de orden anterior

### Mesas
- [ ] Vista detallada de mesa ocupada (ver orden actual, items consumidos)
- [ ] Timer de cuánto lleva ocupada la mesa
- [ ] Color diferente para mesas con orden en proceso vs mesa sin actividad
- [ ] Drag & drop para reasignar clientes entre mesas

### Inventario
- [ ] Modal de creación con carga de imagen real (upload de archivos)
- [ ] Historial de movimientos de stock (entradas/salidas)
- [ ] Alerta de stock bajo
- [ ] Búsqueda y filtros en la tabla de inventario
- [ ] Paginación para muchos productos

### Cupones
- [ ] Modal completo para crear/editar cupones (reemplazar prompts restantes)
- [ ] Vista de estadísticas de uso de cada cupón
- [ ] Cupones por rango de fechas (start_date → end_date)
- [ ] Cupones aplicables solo a categorías/productos específicos

### Reportes
- [ ] Selector de rango de fechas para reportes
- [ ] Exportar reportes a CSV / PDF
- [ ] Gráfico de tendencia de ventas (día/semana/mes)
- [ ] Reporte de propinas por empleado
- [ ] Reporte de productos con menor movimiento

### Impresión de Tickets
- [ ] Formato térmico 80mm para impresoras de tickets
- [ ] Auto-impresión al confirmar pago (opcional)
- [ ] QR con datos de la orden en el ticket

### Backend / Infraestructura
- [ ] Tests Feature para endpoints de órdenes, cupones, productos
- [ ] Rate limiting en endpoints de cupones (evitar brute force)
- [ ] Logs de auditoría (quién creó/modificó/eliminó qué)
- [ ] Roles y permisos (Admin vs Cajero)
- [ ] Soft deletes en modelos principales
