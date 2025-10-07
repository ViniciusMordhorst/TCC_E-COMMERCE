{{-- resources/views/produtos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gerenciar Produtos')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
@section('content')
<div class="container mt-5">
    <h2>Gerenciar Produtos</h2>

    <a href="{{ route('produtos.create') }}" class="btn btn-success mb-3" title="Adicionar novo produto">Adicionar Produto</a>

    @if($produtos->isEmpty())
        <p>Nenhum produto cadastrado.</p>
    @else
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Referência</th>
                    <th>Código</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                    <tr>
                        <td>
                            <img src="{{ $produto->imagem ? asset('storage/' . $produto->imagem) : asset('images/placeholder.png') }}" 
                                 alt="{{ $produto->nome }}" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer;"
                                 onclick="window.location='{{ route('produtos.show', $produto->id) }}'">
                        </td>
                        <td>{{ $produto->nome }}</td>
                        <td>{{ $produto->ref ?? '-' }}</td>
                        <td>{{ $produto->cod ?? '-' }}</td>
                        <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                        <td>{{ $produto->estoque }}</td>
                        <td>{{ $produto->categoria->nome ?? 'Sem categoria' }}</td>
                        <td>{{ $produto->descricao ?? '-' }}</td>
                        <td>
                            <a href="{{ route('produtos.edit', $produto->id) }}" 
                               class="btn btn-warning btn-sm" 
                               title="Editar produto">Editar</a>

                            <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir este produto?')"
                                    title="Excluir produto">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
