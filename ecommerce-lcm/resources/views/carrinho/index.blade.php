@extends('layouts.app')

@section('title', 'Meu Carrinho')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Meu Carrinho</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($itens->count() > 0)
        <div class="row g-4">
            @foreach($itens as $item)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        <a href="{{ route('produtos.show', $item->produto->id) }}">
                            <img src="{{ $item->produto->imagem ?? asset('images/placeholder.png') }}" 
                                 alt="{{ $item->produto->nome }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        </a>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->produto->nome }}</h5>
                            <p class="card-text text-muted mb-2">
                                R$ {{ number_format($item->produto->preco, 2, ',', '.') }}
                            </p>

                            <div class="mb-2">
                                <label class="me-2">Qtd:</label>
                                <input type="number" 
                                       name="quantidade[{{ $item->id }}]" 
                                       value="{{ $item->quantidade }}" 
                                       min="1" 
                                       class="form-control form-control-sm" 
                                       style="width: 70px;">
                            </div>

                            <a href="#" 
                               class="btn btn-sm btn-danger w-100 mt-auto"
                               onclick="event.preventDefault(); document.getElementById('remover-{{ $item->id }}').submit();">
                                Remover
                            </a>
                            <form id="remover-{{ $item->id }}" action="{{ route('carrinho.remover', $item->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <h4>
                Total Geral: 
                R$ {{ number_format($itens->sum(fn($item) => $item->produto->preco * $item->quantidade), 2, ',', '.') }}
            </h4>
            <a href="{{ route('carrinho.checkout') }}" class="btn btn-success">Finalizar Compra</a>
        </div>
    @else
        <p>Seu carrinho está vazio.</p>
    @endif
</div>
@endsection
