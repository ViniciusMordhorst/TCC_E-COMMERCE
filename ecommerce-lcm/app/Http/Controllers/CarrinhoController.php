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

class CarrinhoController extends Controller
{
    public function index()
    {
        $carrinho = Carrinho::firstOrCreate(['id_usuario' => Auth::id()]);
        $itens = $carrinho->itens()->with('produto')->get();

        return view('carrinho.index', compact('itens'));
    }

    public function adicionar($produtoId)
    {
        $carrinho = Carrinho::firstOrCreate(['id_usuario' => Auth::id()]);
        $produto = Produto::findOrFail($produtoId);

        $item = $carrinho->itens()->where('id_produto', $produtoId)->first();

        if ($item) {
            if ($item->quantidade + 1 > $produto->estoque) {
                return redirect()->back()->with('error', 'Não há estoque suficiente para este produto.');
            }
            $item->quantidade += 1;
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
    }

    public function atualizar(Request $request, $itemId)
    {
        $item = ItemCarrinho::findOrFail($itemId);
        $produto = $item->produto;

        $quantidade = (int)$request->quantidade;
        if ($quantidade > $produto->estoque) {
            $quantidade = $produto->estoque;
            return redirect()->back()->with('error', "Estoque máximo disponível: {$produto->estoque}");
        }

        $item->quantidade = $quantidade;
        $item->subtotal = $quantidade * $produto->preco;
        $item->save();

        return redirect()->back()->with('success', 'Quantidade atualizada!');
    }

    public function remover($itemId)
    {
        $item = ItemCarrinho::findOrFail($itemId);
        $item->delete();
        return redirect()->back()->with('success', 'Produto removido!');
    }

    public function checkout()
    {
        $carrinho = Carrinho::firstOrCreate(['id_usuario' => Auth::id()]);
        $itens = $carrinho->itens()->with('produto')->get();
        $enderecos = Auth::user()->enderecos ?? collect();

        return view('carrinho.checkout', compact('itens', 'enderecos'));
    }

    public function finalizar(Request $request)
    {
        $request->validate([
            'rua' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'cep' => 'required',
        ]);

        $user = Auth::user();
        $endereco = Endereco::updateOrCreate(
            ['id_usuario' => $user->id],
            $request->only('rua','numero','bairro','cidade','estado','cep','complemento')
        );

        $carrinho = Carrinho::firstOrCreate(['id_usuario' => $user->id]);
        $total = $carrinho->itens()->sum('subtotal');

        $pedido = Pedido::create([
            'id_usuario' => $user->id,
            'status' => 'Pendente',
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
    }

    public function sucesso($pedidoId)
    {
        $pedido = Pedido::with('itens.produto', 'endereco')->findOrFail($pedidoId);
        return view('carrinho.sucesso', compact('pedido'));
    }
}
