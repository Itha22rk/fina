<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Agrega la columna que falta
            $table->string('numero');
            $table->string('direccion');
            $table->string('ine'); // Columna para la ruta de la imagen INE
            $table->string('comprobante_domicilio'); // Columna para la ruta de la imagen Comprobante de domicilio
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
        Schema::dropIfExists('alumnos');
    }
}