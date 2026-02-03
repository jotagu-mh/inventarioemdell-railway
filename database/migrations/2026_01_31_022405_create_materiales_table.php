<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            
            // Relación con subcategorías
            $table->foreignId('subcategoria_id')
                  ->constrained('subcategorias')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            
            $table->string('unidad_medida', 50)->default('piezas'); // piezas, kg, litros, etc.
            $table->integer('cantidad_actual')->default(0);
            $table->integer('cantidad_minima')->default(0);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materiales');
    }
};