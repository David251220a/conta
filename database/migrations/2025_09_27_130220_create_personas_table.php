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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('documento', 20);
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->foreignId('tipo_persona_id')->default(1);
            $table->date('fecha_nacimiento')->nullable();
            $table->tinyInteger('sexo_id')->default(0);
            $table->tinyInteger('estado_civil')->default(0);
            $table->string('email', 250);
            $table->string('celular', 20)->nullable();
            $table->string('ruc', 20)->nullable();
            $table->foreignId('estado_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->bigInteger('usuario_modificacion');
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
        Schema::dropIfExists('personas');
    }
};
