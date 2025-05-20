<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si el usuario ya existe
        $admin = User::where('email', 'admin@admin.com')->first();
        
        // Si el usuario no existe, crearlo
        if (!$admin) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
            $this->command->info('Usuario administrador creado exitosamente.');
        } else {
            // Actualizar la contraseña por si acaso
            $admin->update([
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
            $this->command->info('Usuario administrador actualizado.');
        }
    }
}
