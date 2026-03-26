<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear un usuario administrador para pruebas
        User::factory()->create([
            'name' => 'Admin POS',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Crear Categorías
        $comida = Category::create(['name' => 'Comida Rápida', 'icon' => '🍔']);
        $bebidas = Category::create(['name' => 'Bebidas', 'icon' => '🥤']);
        $postres = Category::create(['name' => 'Postres', 'icon' => '🍰']);

        // 3. Crear Productos para Comida Rápida
        Product::create([
            'category_id' => $comida->id,
            'name' => 'Hamburguesa Clásica',
            'price' => 8.50,
            'stock' => 50,
            'image' => '🍔',
            'is_active' => true
        ]);

        Product::create([
            'category_id' => $comida->id,
            'name' => 'Papas Fritas XL',
            'price' => 3.00,
            'stock' => 100,
            'image' => '🍟',
            'is_active' => true
        ]);

        // 4. Crear Productos para Bebidas
        Product::create([
            'category_id' => $bebidas->id,
            'name' => 'Coca-Cola 500ml',
            'price' => 1.50,
            'stock' => 200,
            'image' => '🥤',
            'is_active' => true
        ]);

        // 5. Crear Mesas del Restaurante
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'number' => 'Mesa ' . $i,
                'capacity' => ($i <= 5) ? 4 : 2, // Mesas 1-5 para 4 personas, rest para 2
                'status' => 'available'
            ]);
        }
    }
}