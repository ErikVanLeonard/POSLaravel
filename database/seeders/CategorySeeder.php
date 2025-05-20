<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si la categoría ya existe
        if (!DB::table('categories')->where('name', 'Abarrotes')->exists()) {
            DB::table('categories')->insert([
                'name' => 'Abarrotes',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('Categoría "Abarrotes" creada exitosamente.');
        } else {
            $this->command->info('La categoría "Abarrotes" ya existe en la base de datos.');
        }
    }
}
