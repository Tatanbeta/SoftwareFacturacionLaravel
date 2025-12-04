<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('persona')->nullable();
            $table->string('nick', 250)->nullable();
            $table->string('pass', 250)->nullable();
            $table->string('token', 250)->nullable();
            $table->dateTime('ultimo_acceso')->nullable();
            $table->string('estado', 20)->default('ACTIVO');
            $table->timestamp('creado')->useCurrent();
            $table->timestamp('modificado')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('persona')
                ->references('id')->on('personas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
