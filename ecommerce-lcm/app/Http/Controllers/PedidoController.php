<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new AuthController();
    }



public function index()
{
    $this->auth->checkAuth(); // Garante login
    $user = Auth::user(); // Pega o usuário logado corretamente

    if ($user->isAdmin()) {
        $pedidos = Pedido::with('itens.produto', 'usuario.endereco')
                    ->orderByDesc('created_at')
                    ->get();
    } else {
        $pedidos = Pedido::with('itens.produto', 'usuario.endereco')
                    ->where('id_usuario', $user->id)
                    ->orderByDesc('created_at')
                    ->get();
    }

    return view('pedidos.index', compact('pedidos', 'user'));
}


    public function updateStatus(Request $request, $id)
    {
        $this->auth->checkAdmin(); // Somente admin

        $request->validate([
            'status' => 'required|in:Pendente,Processando,Feito,Cancelado'
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->status = $request->status;
        $pedido->save();

        return redirect()->back()->with('success', 'Status atualizado!');
    }
}
