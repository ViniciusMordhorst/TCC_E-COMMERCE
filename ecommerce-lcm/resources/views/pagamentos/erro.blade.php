@extends('layouts.app')

@section('title', 'Erro no Pagamento')

@section('content')
<div class="container mt-5">
    <h2>Ocorreu um erro ao processar seu pagamento</h2>
    <p>Por favor, tente novamente ou entre em contato com o suporte.</p>
    <a href="{{ route('carrinho.index') }}" class="btn btn-danger mt-3">Voltar ao carrinho</a>
</div>
@endsection
