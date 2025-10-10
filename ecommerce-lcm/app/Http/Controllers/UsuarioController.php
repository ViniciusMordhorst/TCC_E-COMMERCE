<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedido;

class UsuarioController extends Controller
{
    public function pedidos()
    {
        $authController = new AuthController();
        $authController->checkAuth();
        $pedidos = Pedido::with('itens.produto', 'endereco')
                         ->where('id_usuario', Auth::id())  
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('usuario.pedidos', compact('pedidos'));
    }
}
