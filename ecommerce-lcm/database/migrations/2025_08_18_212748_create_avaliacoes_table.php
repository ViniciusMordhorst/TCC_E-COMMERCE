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
    Schema::create('avaliacoes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
        $table->foreignId('id_produto')->constrained('produtos')->onDelete('cascade');
        $table->tinyInteger('nota')->unsigned()->check('nota >= 1 AND nota <= 5');
        $table->text('comentario')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
