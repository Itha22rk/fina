<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained('pagos')->onDelete('cascade'); // Relación con la tabla pagos
            $table->foreignId('prestamo_id')->constrained()->onDelete('cascade');
            $table->decimal('monto', 8, 2)->default(100); // Monto de la multa, por defecto $100
            $table->boolean('status_multa')->default(false);
            $table->timestamp('fecha_generada')->nullable();

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
        Schema::dropIfExists('multas');
    }
}
