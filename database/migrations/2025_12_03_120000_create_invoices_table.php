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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('persona_id');
            $table->date('issue_date');
            $table->date('due_date');
            $table->enum('invoice_type', ['Contado', 'Credito']);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_total', 10, 2);
            $table->decimal('total', 10, 2);
            $table->unsignedBigInteger('user_id');
            $table->timestamp('creado')->useCurrent();
            $table->timestamp('modificado')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')
                ->references('id')->on('usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('persona_id')
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
        Schema::dropIfExists('invoices');
    }
};
