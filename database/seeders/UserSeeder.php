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

        User::create([
            'name'      => 'Juan',
            'apellido'  => 'Pérez',
            'ci'        => '12345678',
            'telefono'  => '70123456',
            'email'     => 'admin@emdell.com',
            'password'  => Hash::make('password123'),
            'rol_id'    => $rolAdmin->id,
        ]);
    }
}