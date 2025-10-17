{{-- resources/views/carrinho/sucesso.blade.php --}}
@extends('layouts.app')

@section('content')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

  <h3>✅ Pagamento realizado com sucesso!</h3>
  <p>Obrigado por comprar conosco.</p>

<h2>Itens do Pedido</h2>
@if($pedido->itens->count() > 0)
    <ul>
        @foreach($pedido->itens as $item)
            <li>
                {{ $item->produto->nome }} - {{ $item->quantidade }}x - 
                R$ {{ number_format($item->subtotal, 2, ',', '.') }}
            </li>
        @endforeach
    </ul>
@else
    <p>Não há itens neste pedido.</p>
@endif

<h2>Endereço de Entrega</h2>
<p>
    {{ $pedido->endereco->rua }}, {{ $pedido->endereco->numero }}<br>
    {{ $pedido->endereco->bairro }} - {{ $pedido->endereco->cidade }}/{{ $pedido->endereco->estado }}<br>
    CEP: {{ $pedido->endereco->cep }}<br>
    Complemento: {{ $pedido->endereco->complemento ?? '-' }}
</p>

<h3>Total: R$ {{ number_format($pedido->total, 2, ',', '.') }}</h3>

<a href="{{ route('home') }}">
    <button class="btn btn-primary mt-3">Voltar para a loja</button>
</a>
<a href="{{ route('usuario.pedidos') }}" class="btn btn-info mt-3">Meus Pedidos</a>
@endsection
