<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCompletedToPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('cliente_id');
        });
    }
     /**
     * Run the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });
    }
}
