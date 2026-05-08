<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            Table::create([
                'table_number' => (string) $i,
                'is_active' => true,
            ]);
        }
    }
}
