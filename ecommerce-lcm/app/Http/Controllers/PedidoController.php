<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;



class PedidoController extends Controller
{

    
    protected $authController;

    public function __construct()
    {
        $this->authController = new AuthController();
    }

public function index()
{
        $this->authController->checkAuth();

    if ($this->authController->user->tipo === 1) { // admin
        $pedidos = Pedido::with('itens.produto', 'endereco')
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('pedidos.index', compact('pedidos')); // admin view
    } else { // usuário normal
        $pedidos = Pedido::with('itens.produto', 'endereco')
                    ->where('id_usuario', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('usuario.pedidos', compact('pedidos')); // user view
    }
}

public function show($id)
{
    $this->authController->checkAuth();
    $pedido = Pedido::with('itens.produto', 'endereco')->findOrFail($id);

    // Protege o pedido caso não pertença ao usuário normal
    if ($user->tipo !== 1 && $pedido->id_usuario !== $user->id) {
        redirect()->route('home')->with('error', 'Acesso negado')->send();
        exit;
    }

    return view('pedidos.show', compact('pedido'));
}

    public function updateStatus(Request $request, $id)
{
    $user = Auth::user();
    if ($user->tipo !== 1) {
        redirect()->route('home')->with('error', 'Acesso negado')->send();
        exit;
    }

    $pedido = Pedido::findOrFail($id);
    $request->validate([
        'status' => 'required|in:Pendente,Processando,Feito,Cancelado',
    ]);

    $pedido->status = $request->status;
    $pedido->save();

    return redirect()->route('pedidos.index')->with('success', 'Status atualizado com sucesso!');
}
}
