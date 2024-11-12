<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('prestamo_id')->constrained()->onDelete('cascade'); // Make sure this exists
            $table->decimal('monto_diario', 10, 2)->nullable(); // Ensure this line exists
            $table->boolean('status')->default(false);
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamp('fecha_vencimiento')->nullable();
            $table->decimal('monto_total',10,2)->nullable();
            $table->decimal('multa', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}

