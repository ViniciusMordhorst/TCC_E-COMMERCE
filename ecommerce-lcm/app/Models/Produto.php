<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produto extends Model
{
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
}
