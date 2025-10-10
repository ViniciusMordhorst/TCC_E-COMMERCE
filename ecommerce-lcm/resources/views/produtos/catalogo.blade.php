@extends('layouts.app')

@section('title', 'Catálogo de Produtos')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Catálogo de Produtos</h2>

    @if($produtos->isEmpty())
        <p>Nenhum produto disponível no momento.</p>
    @else
        <div class="row g-4">
            @foreach($produtos as $produto)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        {{-- Imagem clicável leva ao produto --}}
                        <a href="{{ route('produtos.show', $produto->id) }}">
                            <img src="{{ $produto->imagem ?? asset('images/placeholder.png') }}" 
                                 alt="{{ $produto->nome }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        </a>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $produto->nome }}</h5>
                            <p class="card-text text-muted mb-2">
                                R$ {{ number_format($produto->preco, 2, ',', '.') }}
                            </p>
                                    {{-- Botão carrinho --}}
                                    <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">Adicionar ao Carrinho</button>
                                    </form>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
