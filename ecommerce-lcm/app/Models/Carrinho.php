<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model {
    protected $fillable = ['id_usuario'];

    public function itens() {
        return $this->hasMany(ItemCarrinho::class, 'id_carrinho');
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}