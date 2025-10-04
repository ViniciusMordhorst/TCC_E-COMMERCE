@extends('layouts.app')

@section('title', 'Meu Carrinho')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Meu Carrinho</h2>

    @if(session('error'))
        <p class="text-danger fw-bold">{{ session('error') }}</p>
    @endif
    @if(session('success'))
        <p class="text-success fw-bold">{{ session('success') }}</p>
    @endif

    @if($itens->count() > 0)
        <div class="row g-4">
            @foreach($itens as $item)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        {{-- Imagem do produto --}}
                        @if($item->produto->imagem && file_exists(storage_path('app/public/' . $item->produto->imagem)))
                            <img src="{{ asset('storage/' . $item->produto->imagem) }}" 
                                 alt="{{ $item->produto->nome }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}" 
                                 alt="Sem imagem" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->produto->nome }}</h5>
                            <p class="card-text text-muted mb-2">
                                R$ {{ number_format($item->produto->preco, 2, ',', '.') }}
                            </p>

                            {{-- Quantidade --}}
                            <form action="{{ route('carrinho.atualizar', $item->id) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PUT')
                                <div class="d-flex align-items-center">
                                    <label class="me-2">Qtd:</label>
                                    <input type="number" name="quantidade" 
                                           value="{{ $item->quantidade }}" 
                                           min="1" 
                                           max="{{ $item->produto->estoque }}" 
                                           class="form-control form-control-sm" 
                                           style="width: 70px;">
                                </div>
                                @if($item->quantidade > $item->produto->estoque)
                                    <p class="text-danger small mt-1">Quantidade maior que o estoque disponível!</p>
                                @endif
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Atualizar</button>
                            </form>

                            {{-- Remover do carrinho --}}
                            <form action="{{ route('carrinho.remover', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remover</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Total geral --}}
        <div class="mt-4">
            <h4>Total Geral: R$ {{ number_format($itens->sum(function($item){ return $item->produto->preco * $item->quantidade; }), 2, ',', '.') }}</h4>
            <a href="{{ route('carrinho.checkout') }}" class="btn btn-success">Finalizar Compra</a>
        </div>

    @else
        <p>Seu carrinho está vazio.</p>
    @endif
</div>
@endsection
