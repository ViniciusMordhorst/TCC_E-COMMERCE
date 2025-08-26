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
    Schema::create('enderecos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
        $table->string('rua')->nullable();
        $table->string('cidade', 100)->nullable();
        $table->string('estado', 50)->nullable();
        $table->string('cep', 9)->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
