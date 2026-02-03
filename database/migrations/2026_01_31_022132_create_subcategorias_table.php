<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100); // Papelería, Herramientas, etc.
            $table->text('descripcion')->nullable();
            
            // Relación con categorías (Administración o Técnica)
            $table->foreignId('categoria_id')
                  ->constrained('categorias')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subcategorias');
    }
};