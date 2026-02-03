<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();

            // Relación con material
            $table->foreignId('material_id')->constrained('materiales')->onUpdate('cascade')->onDelete('restrict');

            // Tipo de movimiento
            $table->enum('tipo', ['entrada', 'salida']);

            // Datos del movimiento
            $table->date('fecha');
            $table->integer('cantidad');
            $table->decimal('costo_unitario', 10, 2);
            $table->decimal('total', 10, 2); // cantidad * costo_unitario

            // Campos específicos de ENTRADA
            $table->string('numero_factura', 100)->nullable(); // Solo para entradas
            $table->string('numero_ingreso', 100)->nullable(); // Solo para entradas
            $table->string('proveedor', 200)->nullable(); // Solo para entradas

            // Campos específicos de SALIDA
            $table->string('numero_salida', 100)->nullable(); // Solo para salidas
            $table->string('solicitante', 200)->nullable(); // Solo para salidas (ej: JEFE TECNICO, SECRETARIA)
            $table->text('motivo')->nullable(); // Solo para salidas

            // Saldo después del movimiento
            $table->integer('saldo_cantidad'); // Cantidad que queda después del movimiento
            $table->decimal('saldo_total', 10, 2); // Valor total del saldo

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
};