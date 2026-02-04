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
        // Buscar un rol existente (ej: Administrador)
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();

        if (!$rolAdmin) {
            $this->command->error('No existe el rol Administrador');
            return;
        }

        // Crear o actualizar usuario admin
        User::updateOrCreate(
            ['email' => 'admin@emdell.com'], // evita duplicados
            [
                'name'     => 'Juan',
                'apellido' => 'Pérez',
                'ci'       => '12345678',
                'telefono' => '70123456',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')), // contraseña desde .env
                'rol_id'   => $rolAdmin->id,
            ]
        );

        $this->command->info('Usuario admin creado o actualizado correctamente.');
    }
}
