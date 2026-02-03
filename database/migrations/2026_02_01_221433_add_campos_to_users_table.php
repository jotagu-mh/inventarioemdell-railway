<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar columnas solo si no existen
            if (!Schema::hasColumn('users', 'apellido')) {
                $table->string('apellido')->after('name')->nullable();
            }
            if (!Schema::hasColumn('users', 'ci')) {
                $table->string('ci', 20)->after('apellido')->unique()->nullable();
            }
            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono', 20)->after('ci')->nullable();
            }
            if (!Schema::hasColumn('users', 'rol')) {
                $table->enum('rol', ['usuario', 'supervisor', 'administrador'])
                      ->after('password')
                      ->default('usuario');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['apellido', 'ci', 'telefono', 'rol']);
        });
    }
};