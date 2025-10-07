<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrinho;
use App\Models\ItemCarrinho;
use App\Models\Produto;
use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\Endereco;
use Illuminate\Support\Facades\Auth;

class CarrinhoController extends Controller{

 protected $auth;

    public function __construct()
    {
        $this->auth = new AuthController();
    }

 public function index(){

        $this->auth->checkAuth();
        try {
            $carrinho = Carrinho::firstOrCreate(['id_usuario' => auth()->id()]);
            $itens = $carrinho->itens()->with('produto')->get();

            if ($itens->isEmpty()) {
                return redirect()->route('catalogo')->with('error', 'O carrinho está vazio.');
            }

            return view('carrinho.index', compact('itens'));
        } catch (\Exception $e) {
            return redirect()->route('catalogo')->with('error', 'Erro ao carregar o carrinho.');
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
                    return redirect()->back()->with('error', 'Não há estoque suficiente para este produto.');
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

            return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao adicionar produto ao carrinho.');
        }
    }

    public function atualizar(Request $request, $itemId)
    {

        $this->auth->checkAuth();
        try {
            $item = ItemCarrinho::findOrFail($itemId);
            $produto = $item->produto;

            $quantidade = (int)$request->quantidade;

            if ($quantidade < 1) {
                return redirect()->back()->with('error', 'Quantidade inválida.');
            }

            if ($quantidade > $produto->estoque) {
                return redirect()->back()->with('error', "Estoque máximo disponível: {$produto->estoque}");
            }

            $item->quantidade = $quantidade;
            $item->subtotal = $quantidade * $produto->preco;
            $item->save();

            return redirect()->back()->with('success', 'Quantidade atualizada!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Item não encontrado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar o item do carrinho.');
        }
    }

    public function remover($itemId)
    {
        $this->auth->checkAuth();
        try {
            $item = ItemCarrinho::findOrFail($itemId);
            $item->delete();

            return redirect()->back()->with('success', 'Produto removido do carrinho!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Item não encontrado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao remover o item do carrinho.');
        }
    }

    public function checkout()
    {
        $this->auth->checkAuth();
        try {
            $carrinho = Carrinho::firstOrCreate(['id_usuario' => auth()->id()]);
            $itens = $carrinho->itens()->with('produto')->get();
            $enderecos = auth()->user()->enderecos ?? collect();

            if ($itens->isEmpty()) {
                return redirect()->route('catalogo')->with('error', 'O carrinho está vazio.');
            }

            return view('carrinho.checkout', compact('itens', 'enderecos'));
        } catch (\Exception $e) {
            return redirect()->route('catalogo')->with('error', 'Erro ao carregar o checkout.');
        }
    }

    public function finalizar(Request $request)
    {
        $this->auth->checkAuth();
        try {
            $request->validate([
                'rua' => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
                'estado' => 'required',
                'cep' => 'required',
            ]);

            $user = auth()->user();
            $endereco = Endereco::updateOrCreate(
                ['id_usuario' => $user->id],
                $request->only('rua','numero','bairro','cidade','estado','cep','complemento')
            );

            $carrinho = Carrinho::firstOrCreate(['id_usuario' => $user->id]);
            if ($carrinho->itens->isEmpty()) {
                return redirect()->route('catalogo')->with('error', 'Não há produtos no carrinho para finalizar o pedido.');
            }

            $total = $carrinho->itens->sum('subtotal');

            $pedido = Pedido::create([
                'id_usuario' => $user->id,
                'status' => 'Feito',
                'total' => $total,
                'id_endereco' => $endereco->id,
            ]);

            foreach ($carrinho->itens as $item) {
                ItemPedido::create([
                    'id_pedido' => $pedido->id,
                    'id_produto' => $item->id_produto,
                    'quantidade' => $item->quantidade,
                    'subtotal' => $item->subtotal,
                ]);
            }

            $carrinho->itens()->delete();

            return redirect()->route('carrinho.sucesso', $pedido->id);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao finalizar o pedido: ' . $e->getMessage());
        }
    }

    public function sucesso($pedidoId)
    {
        $this->auth->checkAuth();
        try {
            $pedido = Pedido::with('itens.produto', 'endereco')->findOrFail($pedidoId);
            return view('carrinho.sucesso', compact('pedido'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('catalogo')->with('error', 'Pedido não encontrado.');
        } catch (\Exception $e) {
            return redirect()->route('catalogo')->with('error', 'Erro ao exibir o pedido.');
        }
    }
}
