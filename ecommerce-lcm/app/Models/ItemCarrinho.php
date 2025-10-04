<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCarrinho extends Model
{
    protected $table = 'itens_carrinho'; 
    protected $fillable = ['id_carrinho', 'id_produto', 'quantidade', 'subtotal'];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }

    public function carrinho()
    {
        return $this->belongsTo(Carrinho::class, 'id_carrinho');
    }
}