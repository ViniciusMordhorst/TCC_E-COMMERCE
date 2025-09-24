{{-- resources/views/produtos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gerenciar Produtos')

@section('content')
<div class="container mt-5">
    <h2>Gerenciar Produtos</h2>

    <a href="{{ route('produtos.create') }}" class="btn btn-success mb-3">Adicionar Produto</a>

    @if($produtos->isEmpty())
        <p>Nenhum produto cadastrado.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                    <tr>
                        <td><img src="{{ asset('storage/' . $produto->imagem) }}" width="100" alt="{{ $produto->nome }}"></td>
                        <td>{{ $produto->nome }}</td>
                        <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                        <td>{{ $produto->descricao }}</td>
                        <td>
                            <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
