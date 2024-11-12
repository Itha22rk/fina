<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->decimal('interes', 5, 2);
            $table->integer('plazo_dias');
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento');
            $table->decimal('monto_total', 15, 2);
            $table->decimal('monto_pendiente', 15, 2);
            $table->boolean('estado')->default(false);
            $table->decimal('total_pagado', 15, 2)->default(0);  
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestamos');
    }
}

