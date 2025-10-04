<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Endereco extends Model {
    protected $fillable = ['id_usuario', 'rua', 'numero', 'bairro', 'cidade', 'estado', 'cep', 'complemento'];

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
