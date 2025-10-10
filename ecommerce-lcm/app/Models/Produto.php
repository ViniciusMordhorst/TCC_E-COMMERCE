<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'id_categoria',
        'nome',
        'descricao',
        'preco',
        'estoque',
        'ref',
        'cod',
        'imagem',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function itensPedido()
    {
        return $this->hasMany(ItemPedido::class, 'id_produto'); // relacionamento para pedidos
    }

    public function itensCarrinho()
    {
        return $this->hasMany(ItemCarrinho::class, 'id_produto'); // relacionamento para carrinho
    }
}
