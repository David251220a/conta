<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained();
            $table->string('descripcion', 250);
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 0)->default(0);
            $table->decimal('monto_total', 12, 0)->default(0);
            $table->tinyInteger('iva_afecta')->default(3)->comment('3 exento , 10 iva y 5 iva');
            $table->decimal('exento', 12, 0)->default(0);
            $table->decimal('grabado', 12, 0)->default(0);
            $table->decimal('iva', 12, 0)->default(0);
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
        Schema::dropIfExists('factura_detalles');
    }
};
