<!-- resources/views/carrinho/checkout.blade.php -->
@extends('layouts.app')

@section('content')
<h1>Checkout</h1>

<h2>Resumo do Carrinho</h2>
<ul>
@foreach($carrinho as $id => $item)
    <li>{{ $item['nome'] }} - {{ $item['quantidade'] }}x - R$ {{ $item['preco'] }}</li>
@endforeach
</ul>

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
    <button type="submit">Finalizar Pedido</button>
</form>
@endsection
