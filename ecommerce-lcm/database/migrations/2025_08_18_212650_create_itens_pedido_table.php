<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('itens_pedido', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_pedido')->constrained('pedidos')->onDelete('cascade');
        $table->foreignId('id_produto')->constrained('produtos')->onDelete('cascade');
        $table->integer('quantidade');
        $table->decimal('subtotal', 10, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_pedido');
    }
};
