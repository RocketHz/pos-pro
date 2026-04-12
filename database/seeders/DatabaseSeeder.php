<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear un usuario administrador para pruebas
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin POS',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Crear Categorías
        $comida = Category::firstOrCreate(['name' => 'Comida Rápida'], ['icon' => '🍔']);
        $bebidas = Category::firstOrCreate(['name' => 'Bebidas'], ['icon' => '🥤']);
        $postres = Category::firstOrCreate(['name' => 'Postres'], ['icon' => '🍰']);

        // 3. Crear Productos para Comida Rápida
        Product::firstOrCreate(
            ['name' => 'Hamburguesa Clásica'],
            [
                'category_id' => $comida->id,
                'price' => 8.50,
                'stock' => 50,
                'image' => '🍔',
                'is_active' => true
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Papas Fritas XL'],
            [
                'category_id' => $comida->id,
                'price' => 3.00,
                'stock' => 100,
                'image' => '🍟',
                'is_active' => true
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Hot Dog Especial'],
            [
                'category_id' => $comida->id,
                'price' => 5.50,
                'stock' => 40,
                'image' => '🌭',
                'is_active' => true
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Pizza Personal'],
            [
                'category_id' => $comida->id,
                'price' => 7.00,
                'stock' => 30,
                'image' => '🍕',
                'is_active' => true
            ]
        );

        // 4. Crear Productos para Bebidas
        Product::firstOrCreate(
            ['name' => 'Coca-Cola 500ml'],
            [
                'category_id' => $bebidas->id,
                'price' => 1.50,
                'stock' => 200,
                'image' => '🥤',
                'is_active' => true
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Cerveza'],
            [
                'category_id' => $bebidas->id,
                'price' => 3.00,
                'stock' => 150,
                'image' => '🍺',
                'is_active' => true
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Agua Natural'],
            [
                'category_id' => $bebidas->id,
                'price' => 1.00,
                'stock' => 300,
                'image' => '💧',
                'is_active' => true
            ]
        );

        // 5. Crear Productos para Postres
        Product::firstOrCreate(
            ['name' => 'Pastel de Chocolate'],
            [
                'category_id' => $postres->id,
                'price' => 4.50,
                'stock' => 25,
                'image' => '🍰',
                'is_active' => true
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Helado'],
            [
                'category_id' => $postres->id,
                'price' => 2.50,
                'stock' => 50,
                'image' => '🍦',
                'is_active' => true
            ]
        );

        // 6. Crear Mesas del Restaurante
        for ($i = 1; $i <= 12; $i++) {
            Table::firstOrCreate(
                ['number' => 'Mesa ' . $i],
                [
                    'capacity' => ($i <= 5) ? 4 : 2,
                    'status' => 'available'
                ]
            );
        }

        // 7. Crear Cupones de ejemplo
        Coupon::firstOrCreate(
            ['code' => 'PROMO10'],
            [
                'type' => 'fixed',
                'value' => 10.00,
                'min_purchase' => 20.00,
                'max_discount' => 10.00,
                'usage_limit' => 100,
                'is_active' => true
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'DESCUENTO20'],
            [
                'type' => 'percentage',
                'value' => 20.00,
                'min_purchase' => 50.00,
                'max_discount' => 25.00,
                'usage_limit' => 50,
                'is_active' => true
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'BIENVENIDO'],
            [
                'type' => 'percentage',
                'value' => 15.00,
                'min_purchase' => 0,
                'max_discount' => 15.00,
                'usage_limit' => null,
                'usage_limit_per_customer' => 1,
                'is_active' => true
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'PRIMERO5'],
            [
                'type' => 'fixed',
                'value' => 5.00,
                'min_purchase' => 10.00,
                'max_discount' => 5.00,
                'usage_limit' => 10,
                'usage_limit_per_customer' => 1,
                'is_active' => true
            ]
        );
    }
}
