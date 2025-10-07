@extends('layouts.app')

@section('content')
<h1>Meus Pedidos</h1>

@if($pedidos->count() > 0)
    @foreach($pedidos as $pedido)
        <div class="pedido-card">
            <h3>Pedido #{{ $pedido->id }} - Status: {{ $pedido->status }}</h3>
            <ul>
                @foreach($pedido->itens as $item)
                    <li>{{ $item->produto->nome }} - {{ $item->quantidade }}x - R$ {{ number_format($item->subtotal, 2, ',', '.') }}</li>
                @endforeach
            </ul>
            <p>Total: R$ {{ number_format($pedido->total, 2, ',', '.') }}</p>
            <p>Endereço: {{ $pedido->endereco->rua }}, {{ $pedido->endereco->numero }} - {{ $pedido->endereco->cidade }}/{{ $pedido->endereco->estado }}</p>
        </div>
    @endforeach
@else
    <p>Você não possui pedidos.</p>
@endif

<a href="{{ route('home') }}" class="btn btn-primary mt-3">Voltar à loja</a>
@endsection
