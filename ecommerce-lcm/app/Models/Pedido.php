<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model {
    protected $fillable = ['id_usuario', 'status', 'total'];

    public function itens() {
        return $this->hasMany(ItemPedido::class, 'id_pedido');
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function endereco() {
        return $this->belongsTo(Endereco::class, 'id_usuario', 'id_usuario');
    }

    public function pagamento() {
        return $this->hasOne(Pagamento::class, 'id_pedido');
    }
}