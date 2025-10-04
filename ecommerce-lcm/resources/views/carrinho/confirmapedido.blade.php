@extends('layouts.app')

@section('content')
<h1>Pedido Confirmado</h1>

@if(session('pedido'))
    <h2>Resumo do Pedido</h2>
    <ul>
        @foreach(session('pedido')['itens'] as $item)
            <li>{{ $item['nome'] }} - {{ $item['quantidade'] }}x - R$ {{ $item['preco'] }}</li>
        @endforeach
    </ul>

    <h2>Endereço de Entrega</h2>
    <p>
        {{ session('pedido')['endereco']['rua'] }}, {{ session('pedido')['endereco']['numero'] }}<br>
        {{ session('pedido')['endereco']['bairro'] }} - {{ session('pedido')['endereco']['cidade'] }}/{{ session('pedido')['endereco']['estado'] }}<br>
        CEP: {{ session('pedido')['endereco']['cep'] }}<br>
        Complemento: {{ session('pedido')['endereco']['complemento'] ?? '-' }}
    </p>

    <h3>Total: R$ {{ collect(session('pedido')['itens'])->sum(function($item){ return $item['preco'] * $item['quantidade']; }) }}</h3>

@else
    <p>Não há pedido para mostrar.</p>
@endif

<a href="{{ route('home') }}"><button>Voltar para a loja</button></a>
@endsection
