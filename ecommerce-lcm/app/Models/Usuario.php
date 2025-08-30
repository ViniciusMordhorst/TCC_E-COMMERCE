<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'cpf',
        'telefone',
        'tipo',
    ];

    protected $hidden = [
        'senha',
    ];

    public function isAdmin()
    {
        return $this->tipo === 1;
    }

    public function isCliente()
    {
        return $this->tipo === 0;
    }
}
