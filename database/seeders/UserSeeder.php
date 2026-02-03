<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar rol existente (por nombre o ID)
        $rol = Rol::where('nombre', 'Administrador')->first();

        if (!$rol) {
            $this->command->error('El rol Administrador no existe.');
            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@ejemplo.com'],
            [
                'name' => 'Admin',
                'apellido' => 'Admin',
                'ci' => '4234',
                'telefono' => '7777',
                'password' => Hash::make('admin123'),
                'rol_id' => $rol->id,
            ]
        );
    }
}
