<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        // Image mappings - products share category-representative images
        $imageMap = [
            // Signature Coffee
            'BKEN' => 'products/signature-coffee.png',
            'Brame' => 'products/signature-coffee.png',
            'Brasa' => 'products/signature-coffee.png',
            'Silent Hill' => 'products/signature-coffee.png',
            // Espresso
            'Espresso' => 'products/espresso.png',
            'Americano' => 'products/espresso.png',
            'Cappucino' => 'products/cappuccino.png',
            'Mochaccino' => 'products/chocolate.png',
            'Cafe Latte' => 'products/cappuccino.png',
            'Hazelnut Latte' => 'products/cappuccino.png',
            'Tiramisu Latte' => 'products/cappuccino.png',
            'Vanilla Latte' => 'products/cappuccino.png',
            // Non Coffee
            'Berrykurt' => 'products/fruit-tea.png',
            'Lycheekurt' => 'products/fruit-tea.png',
            'Mojito' => 'products/mojito.png',
            'Matcha' => 'products/matcha.png',
            'Red Velvet' => 'products/red-velvet.png',
            'Chocoloco' => 'products/chocolate.png',
            'Choco Hazelnut' => 'products/chocolate.png',
            'Alpenlibe' => 'products/signature-coffee.png',
            // Tea Based
            'Strawberry Tea' => 'products/fruit-tea.png',
            'Peach Tea' => 'products/fruit-tea.png',
            'Mexican Lime Tea' => 'products/lemon-tea.png',
            'Lychee Tea' => 'products/fruit-tea.png',
            'Lemon Tea' => 'products/lemon-tea.png',
            // Snack
            'French Fries' => 'products/french-fries.png',
            'Mix Platter' => 'products/french-fries.png',
            'Cireng' => 'products/cireng.png',
            'Nachos' => 'products/nachos.png',
        ];

        $products = [
            // Signature Coffee
            ['name' => 'BKEN', 'price' => 23000, 'category' => 'Signature Coffee', 'description' => 'Kopi Susu Gula Aren — perpaduan espresso, susu, dan gula aren pilihan'],
            ['name' => 'Brame', 'price' => 25000, 'category' => 'Signature Coffee', 'description' => 'Kopi Susu Caramel — espresso dengan susu dan sirup caramel'],
            ['name' => 'Brasa', 'price' => 25000, 'category' => 'Signature Coffee', 'description' => 'Kopi Mocktail — racikan kopi dengan sentuhan mocktail segar'],
            ['name' => 'Silent Hill', 'price' => 27000, 'category' => 'Signature Coffee', 'description' => 'Kopi Mocktail — kopi premium dengan twist mocktail unik'],

            // Espresso
            ['name' => 'Espresso', 'price' => 15000, 'category' => 'Espresso', 'description' => 'Single shot espresso dengan crema tebal yang kaya rasa'],
            ['name' => 'Americano', 'price' => 23000, 'category' => 'Espresso', 'description' => 'Espresso dengan tambahan air panas, sempurna untuk penikmat kopi murni'],
            ['name' => 'Cappucino', 'price' => 25000, 'category' => 'Espresso', 'description' => 'Espresso, steamed milk, dan foam milk yang lembut'],
            ['name' => 'Mochaccino', 'price' => 27000, 'category' => 'Espresso', 'description' => 'Perpaduan espresso, cokelat, dan susu steamed yang creamy'],
            ['name' => 'Cafe Latte', 'price' => 25000, 'category' => 'Espresso', 'description' => 'Espresso dengan susu steamed yang creamy dan lembut'],
            ['name' => 'Hazelnut Latte', 'price' => 27000, 'category' => 'Espresso', 'description' => 'Cafe latte dengan sirup hazelnut yang nikmat'],
            ['name' => 'Tiramisu Latte', 'price' => 27000, 'category' => 'Espresso', 'description' => 'Cafe latte dengan rasa tiramisu yang khas'],
            ['name' => 'Vanilla Latte', 'price' => 27000, 'category' => 'Espresso', 'description' => 'Cafe latte dengan sirup vanilla yang manis lembut'],

            // Non Coffee
            ['name' => 'Berrykurt', 'price' => 20000, 'category' => 'Non Coffee', 'description' => 'Yogurt segar dengan topping buah berry pilihan'],
            ['name' => 'Lycheekurt', 'price' => 20000, 'category' => 'Non Coffee', 'description' => 'Yogurt segar dengan rasa lychee yang menyegarkan'],
            ['name' => 'Mojito', 'price' => 25000, 'category' => 'Non Coffee', 'description' => 'Minuman segar ala mojito dengan lime dan mint'],
            ['name' => 'Matcha', 'price' => 25000, 'category' => 'Non Coffee', 'description' => 'Matcha premium Jepang dengan susu yang creamy'],
            ['name' => 'Red Velvet', 'price' => 25000, 'category' => 'Non Coffee', 'description' => 'Red velvet latte yang manis dan lembut'],
            ['name' => 'Chocoloco', 'price' => 25000, 'category' => 'Non Coffee', 'description' => 'Cokelat premium dengan susu yang rich dan creamy'],
            ['name' => 'Choco Hazelnut', 'price' => 27000, 'category' => 'Non Coffee', 'description' => 'Cokelat hazelnut dengan susu steamed yang nikmat'],
            ['name' => 'Alpenlibe', 'price' => 27000, 'category' => 'Non Coffee', 'description' => 'Minuman dengan rasa caramel alpenlibe yang creamy'],

            // Tea Based
            ['name' => 'Strawberry Tea', 'price' => 20000, 'category' => 'Tea Based', 'description' => 'Teh segar dengan rasa strawberry yang manis asam'],
            ['name' => 'Peach Tea', 'price' => 20000, 'category' => 'Tea Based', 'description' => 'Teh segar dengan rasa peach yang menyegarkan'],
            ['name' => 'Mexican Lime Tea', 'price' => 20000, 'category' => 'Tea Based', 'description' => 'Teh dengan perasan jeruk lime ala Mexican'],
            ['name' => 'Lychee Tea', 'price' => 20000, 'category' => 'Tea Based', 'description' => 'Teh segar dengan rasa lychee yang harum'],
            ['name' => 'Lemon Tea', 'price' => 18000, 'category' => 'Tea Based', 'description' => 'Teh klasik dengan perasan lemon segar'],

            // Snack
            ['name' => 'French Fries', 'price' => 18000, 'category' => 'Snack', 'description' => 'Kentang goreng renyah dengan bumbu spesial'],
            ['name' => 'Mix Platter', 'price' => 25000, 'category' => 'Snack', 'description' => 'Paket camilan campuran pilihan terbaik'],
            ['name' => 'Cireng', 'price' => 18000, 'category' => 'Snack', 'description' => 'Cireng goreng renyah dengan saus sambal khas'],
            ['name' => 'Nachos', 'price' => 20000, 'category' => 'Snack', 'description' => 'Keripik tortilla dengan saus keju dan salsa'],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']) . '-' . Str::random(5),
                'description' => $product['description'],
                'price' => $product['price'],
                'category_id' => $categories[$product['category']]->id,
                'image' => $imageMap[$product['name']] ?? null,
                'is_active' => true,
            ]);
        }
    }
}
