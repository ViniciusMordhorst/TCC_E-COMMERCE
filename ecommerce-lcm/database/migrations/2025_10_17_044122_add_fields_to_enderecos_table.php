<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enderecos', function (Blueprint $table) {
            if (!Schema::hasColumn('enderecos', 'numero')) {
                $table->string('numero')->nullable();
            }
            if (!Schema::hasColumn('enderecos', 'complemento')) {
                $table->string('complemento')->nullable();
            }
            if (!Schema::hasColumn('enderecos', 'bairro')) {
                $table->string('bairro')->nullable();
            }
            if (!Schema::hasColumn('enderecos', 'cidade')) {
                $table->string('cidade')->nullable();
            }
            if (!Schema::hasColumn('enderecos', 'estado')) {
                $table->string('estado')->nullable();
            }
            if (!Schema::hasColumn('enderecos', 'cep')) {
                $table->string('cep', 9)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('enderecos', function (Blueprint $table) {
            $table->dropColumn(['numero', 'complemento', 'bairro', 'cidade', 'estado', 'cep']);
        });
    }
};
