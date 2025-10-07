<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedido;

class UsuarioController extends Controller
{
    public function pedidos()
    {
        $usuario = Auth::user();
        $pedidos = Pedido::with('itens.produto', 'endereco')
                         ->where('id_usuario', $usuario->id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('usuario.pedidos', compact('pedidos'));
    }
}
