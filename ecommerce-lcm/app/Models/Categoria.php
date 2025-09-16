<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nome',
    ];

    // uma categoria pode ter vários produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_categoria');
    }
}
