<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Signature Coffee', 'description' => 'Racikan kopi spesial khas Sa.Tuang Coffee'],
            ['name' => 'Espresso', 'description' => 'Berbagai varian espresso based dari biji kopi pilihan'],
            ['name' => 'Non Coffee', 'description' => 'Minuman segar tanpa kopi untuk semua kalangan'],
            ['name' => 'Tea Based', 'description' => 'Aneka teh segar dengan berbagai rasa buah'],
            ['name' => 'Snack', 'description' => 'Camilan ringan pendamping minuman favorit Anda'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}
