<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primero, crear algunos roles por defecto
        DB::table('roles')->insert([
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Supervisor', 'descripcion' => 'Supervisión y reportes', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Usuario', 'descripcion' => 'Usuario estándar del sistema', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('users', function (Blueprint $table) {
            // Agregar la nueva columna rol_id
            $table->foreignId('rol_id')->nullable()->after('password')->constrained('roles')->onDelete('restrict');
        });

        // Migrar los datos existentes del campo 'rol' al nuevo 'rol_id'
        $usuarios = DB::table('users')->get();
        foreach ($usuarios as $usuario) {
            $rolId = null;
            switch ($usuario->rol) {
                case 'administrador':
                    $rolId = 1;
                    break;
                case 'supervisor':
                    $rolId = 2;
                    break;
                case 'usuario':
                    $rolId = 3;
                    break;
            }
            if ($rolId) {
                DB::table('users')->where('id', $usuario->id)->update(['rol_id' => $rolId]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            // Eliminar la columna antigua 'rol'
            $table->dropColumn('rol');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaurar la columna rol
            $table->enum('rol', ['usuario', 'supervisor', 'administrador'])->default('usuario')->after('password');
        });

        // Migrar los datos de vuelta
        $usuarios = DB::table('users')->get();
        foreach ($usuarios as $usuario) {
            $rol = 'usuario';
            switch ($usuario->rol_id) {
                case 1:
                    $rol = 'administrador';
                    break;
                case 2:
                    $rol = 'supervisor';
                    break;
                case 3:
                    $rol = 'usuario';
                    break;
            }
            DB::table('users')->where('id', $usuario->id)->update(['rol' => $rol]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropColumn('rol_id');
        });
    }
};