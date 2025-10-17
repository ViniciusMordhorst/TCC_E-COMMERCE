<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrinho;
use App\Models\ItemCarrinho;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\Endereco;
use App\Models\Pagamento;
use Illuminate\Support\Facades\Auth;

class CarrinhoController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new AuthController();
    }


    public function index()
    {
    $this->auth->checkAuth();

    try {
        $carrinho = Carrinho::firstOrCreate(['id_usuario' => auth()->id()]);
        $itens = $carrinho->itens()->with('produto')->get();

        // Pega o endereço do usuário (hasOne)
        $endereco = auth()->user()->endereco; 

        return view('carrinho.index', compact('itens', 'endereco'));
    } catch (\Exception $e) {
        \Log::error('Erro ao carregar carrinho: '.$e->getMessage());
        return redirect()->route('home')->with('error', 'Erro ao carregar o carrinho.');
    }
    }


    public function adicionar($produtoId)
    {
        $this->auth->checkAuth();

        try {
            $carrinho = Carrinho::firstOrCreate(['id_usuario' => auth()->id()]);
            $produto = Produto::findOrFail($produtoId);

            $item = $carrinho->itens()->where('id_produto', $produtoId)->first();

            if ($item) {
                if ($item->quantidade + 1 > $produto->estoque) {
                    return redirect()->back()->with('error', 'Não há estoque suficiente.');
                }
                $item->quantidade++;
            } else {
                if ($produto->estoque < 1) {
                    return redirect()->back()->with('error', 'Produto sem estoque disponível.');
                }

                $item = $carrinho->itens()->create([
                    'id_produto' => $produto->id,
                    'quantidade' => 1,
                    'subtotal' => $produto->preco,
                ]);
            }

            $item->subtotal = $item->quantidade * $produto->preco;
            $item->save();

            return redirect()->route('carrinho.index')->with('success', 'Produto adicionado ao carrinho!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao adicionar produto ao carrinho.');
        }
    }
public function atualizar(Request $request, $itemId)
{
    $this->auth->checkAuth();
    $userId = auth()->id();

    try {
        // Busca o carrinho do usuário logado
        $carrinho = Carrinho::where('id_usuario', $userId)->firstOrFail();

        // Busca o item dentro do carrinho
        $item = $carrinho->itens()
            ->with('produto')
            ->where('id', $itemId)
            ->firstOrFail();

        // Quantidade mínima 1
        $quantidade = max(1, (int) $request->quantidade);

        // Se quantidade for maior que o estoque, avisa
        $mensagemEstoque = null;
        if ($quantidade > $item->produto->estoque) {
            $mensagemEstoque = "Quantidade acima do estoque! Produtos extras serão produzidos.";
        }

        $preco = (float) str_replace(',', '.', $item->produto->preco);

        $item->quantidade = $quantidade;
        $item->subtotal = $quantidade * $preco;
        $item->save();

        $total = $carrinho->itens()->sum('subtotal');

        return response()->json([
            'success' => true,
            'subtotal' => number_format($item->subtotal, 2, ',', '.'),
            'total' => number_format($total, 2, ',', '.'),
            'mensagemEstoque' => $mensagemEstoque
        ]);
    } catch (\Exception $e) {
        \Log::error('Erro ao atualizar item do carrinho: '.$e->getMessage());
        return response()->json(['error' => 'Erro ao atualizar item.'], 500);
    }
}





    public function remover($itemId)
    {
        $this->auth->checkAuth();

        try {
            $item = ItemCarrinho::findOrFail($itemId);
            $item->delete();

            return redirect()->back()->with('success', 'Produto removido do carrinho!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao remover o item do carrinho.');
        }
    }

public function checkout()
{
    $this->auth->checkAuth();

    try {
        // Carrinho e itens do usuário logado
        $carrinho = Carrinho::firstOrCreate(['id_usuario' => auth()->id()]);
        $itens = $carrinho->itens()->with('produto')->get();

        if ($itens->isEmpty()) {
            return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio.');
        }

        // Pega o primeiro endereço do usuário, se existir
        $usuario = auth()->user();
        $endereco = $usuario->enderecos()->first(); // usa hasMany, pega o 1º

        return view('carrinho.index', compact('itens', 'endereco'));
    } catch (\Exception $e) {
        return redirect()->route('carrinho.index')->with('error', 'Erro ao carregar o checkout.');
    }
}



   public function endereco()
{
    $user = auth()->user();
    $endereco = \App\Models\Endereco::where('id_usuario', $user->id)->first();

    return view('carrinho.endereco', compact('endereco'));
}

public function salvarEndereco(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'rua' => 'required|string|max:255',
        'numero' => 'required|string|max:10',
        'bairro' => 'required|string|max:255',
        'cidade' => 'required|string|max:255',
        'estado' => 'required|string|max:2',
        'cep' => 'required|string|max:9',
    ]);

    \App\Models\Endereco::updateOrCreate(
        ['id_usuario' => $user->id],
        $request->only(['rua', 'numero', 'bairro', 'cidade', 'estado', 'cep'])
    );

    return redirect()->route('pagamento.criar')
        ->with('success', 'Endereço salvo com sucesso! Redirecionando para o pagamento...');
}





    public function sucesso($pedidoId)
    {
        $this->auth->checkAuth();

        try {
            $pedido = Pedido::with('itens.produto', 'endereco')->findOrFail($pedidoId);
            return view('carrinho.sucesso', compact('pedido'));
        } catch (\Exception $e) {
            return redirect()->route('carrinho.index')->with('error', 'Pedido não encontrado.');
        }
    }
}
