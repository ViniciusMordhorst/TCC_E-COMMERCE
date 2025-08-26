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
    Schema::create('produtos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_categoria')->nullable()->constrained('categorias')->nullOnDelete();
        $table->string('nome');
        $table->text('descricao')->nullable();
        $table->decimal('preco', 10, 2);
        $table->string('imagem')->nullable();
        $table->integer('estoque');
        $table->string('ref', 50)->nullable();
        $table->string('cod', 50)->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
