🚀 POS Pro - Sistema de Gestión de Restaurante (Laravel 11 + Postgres)
======================================================================

Este documento sirve como **Mapa de Ruta y Contexto Técnico** para agentes de IA y desarrolladores. El objetivo es transformar un prototipo frontend estático en una aplicación web robusta y funcional.

📌 Estado Actual del Proyecto
-----------------------------

*   **Backend:** Laravel 11.x / PHP 8.3.
    
*   **Base de Datos:** PostgreSQL (Base de datos pos\_pro).
    
*   **Frontend:** Blade Templates con Tailwind CSS (CDN/Vite) y JavaScript Vanilla para la lógica de estado (AppState).
    
*   **Autenticación:** Laravel Breeze ya instalado y configurado.
    

🗺️ Mapa de Ruta: Objetivos de Completitud
------------------------------------------

### Fase 1: Persistencia de Datos (El Corazón)

_El objetivo es que los productos y mesas no desaparezcan al recargar la página._

1.  **Modelos y Migraciones:**
    
    *   \[ \] Crear modelo Product (nombre, precio, categoría, stock, imagen/emoji).
        
    *   \[ \] Crear modelo Table (número, estado: libre/ocupada, capacidad).
        
    *   \[ \] Crear modelo Order y OrderItem (para guardar las ventas).
        
2.  **API interna:**
    
    *   \[ \] Crear controladores para que el JavaScript de la vista pueda hacer fetch() a los productos reales de la base de datos en lugar de usar el array AppState.data.products estático.
        

### Fase 2: Lógica del Punto de Venta (POS)

_Hacer que el botón "Cobrar" guarde la venta real._

1.  **Sincronización de Carrito:**
    
    *   \[ \] Conectar el módulo POSModule.js con un endpoint de Laravel para registrar transacciones.
        
    *   \[ \] Implementar la actualización de stock automática en Postgres cuando se confirme una venta.
        
2.  **Gestión de Mesas:**
    
    *   \[ \] Vincular el estado de las mesas en la interfaz con la base de datos para que varios dispositivos vean la misma mesa ocupada.
        

### Fase 3: Módulo de Inventario y Cupones

_Permitir al administrador gestionar el negocio._

1.  **CRUD de Productos:**
    
    *   \[ \] Crear formularios funcionales para "Nuevo Producto" que guarden datos en Postgres.
        
    *   \[ \] Implementar la carga de imágenes o selección de iconos.
        
2.  **Sistema de Cupones:**
    
    *   \[ \] Validar los cupones desde el servidor para evitar que un cliente edite el código JS y se asigne descuentos falsos.
        

### Fase 4: Reportes Reales

_Transformar los gráficos de Chart.js en datos verdaderos._

1.  **Consultas de Agregación:**
    
    *   \[ \] Crear lógica en el controlador de Reportes para sumar ventas por día, mes y productos más vendidos mediante SQL.
        
2.  **Impresión de Tickets:**
    
    *   \[ \] Formatear la vista del modal de recibo para que sea compatible con impresoras térmicas (80mm).
        

🛠️ Instrucciones para el Agente de IA
--------------------------------------

Si eres una IA ayudando en este proyecto, sigue estas reglas:

1.  **Prioriza la Consistencia:** Mantén la estética "Dark Mode" y el uso de la variable global AppState para la reactividad en el frontend.
    
2.  **Seguridad:** Asegúrate de que todas las peticiones POST incluyan el token CSRF y validación de Request en Laravel.
    
3.  **Postgres:** Usa tipos de datos adecuados (Decimal para precios, JSONB para detalles adicionales si es necesario).
    
4.  **Estructura:** No borres los comentarios de los módulos en app.blade.php (ej: MÓDULO: State Management), ya que mantienen el código organizado.
    

🚀 Comandos Rápidos
-------------------

*   php artisan serve Iniciar servidor.
    
*   php artisan migrate - Sincronizar base de datos.
    
*   npm run dev- Recursos del compilador de Vite.