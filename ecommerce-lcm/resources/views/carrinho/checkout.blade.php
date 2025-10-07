@extends('layouts.app')

@section('content')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

<h1>Checkout</h1>

<h2>Resumo do Carrinho</h2>
@if($itens->count() > 0)
    <ul>
        @foreach($itens as $item)
            <li>
                {{ $item->produto->nome }} - {{ $item->quantidade }}x - 
                R$ {{ number_format($item->subtotal, 2, ',', '.') }}
            </li>
        @endforeach
    </ul>
@else
    <p>O carrinho está vazio.</p>
@endif

<h2>Endereço de Entrega</h2>
<form action="{{ route('carrinho.finalizar') }}" method="POST">
    @csrf
    <input type="text" name="rua" placeholder="Rua" value="{{ old('rua') }}" required>
    <input type="text" name="numero" placeholder="Número" value="{{ old('numero') }}" required>
    <input type="text" name="bairro" placeholder="Bairro" value="{{ old('bairro') }}" required>
    <input type="text" name="cidade" placeholder="Cidade" value="{{ old('cidade') }}" required>
    <input type="text" name="estado" placeholder="Estado" value="{{ old('estado') }}" required>
    <input type="text" name="cep" placeholder="CEP" value="{{ old('cep') }}" required>
    <input type="text" name="complemento" placeholder="Complemento" value="{{ old('complemento') }}">
    <button type="submit" class="btn btn-success mt-3">Finalizar Pedido</button>
</form>
@endsection
