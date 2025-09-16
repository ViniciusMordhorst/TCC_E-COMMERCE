@extends('layouts.app')

@section('title', $produto->nome ?? 'Produto')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $produto->nome }}</h2>

      @if($produto->imagem)
    <img src="{{ $produto->imagem }}" alt="{{ $produto->nome }}" class="img-fluid" style="max-width:480px;">
@endif

            <p><strong>Preço:</strong> R$ {{ number_format($produto->preco ?? 0, 2, ',', '.') }}</p>
            <p><strong>Estoque:</strong> {{ $produto->estoque ?? 0 }}</p>
            <p><strong>Categoria:</strong> {{ $produto->categoria->nome ?? 'Sem categoria' }}</p>

            @if(!empty($produto->descricao))
                <div class="mt-3">
                    <h5>Descrição</h5>
                    <p>{!! nl2br(e($produto->descricao)) !!}</p>
                </div>
            @endif

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Voltar</a>

                @auth
                    @if((int) Auth::user()->tipo === 1)
                        <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-primary">Editar</a>

                        <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" onsubmit="return confirm('Confirma remoção deste produto?');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Remover</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
