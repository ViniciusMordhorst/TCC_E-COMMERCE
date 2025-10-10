@extends('layouts.app')

@section('title', 'Meu Carrinho')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Meu Carrinho</h2>

    {{-- Mensagens --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($itens->count() > 0)
        {{-- Lista de Itens --}}
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
                            <p class="text-muted mb-2">
                                R$ {{ number_format($item->produto->preco, 2, ',', '.') }}
                            </p>

                            <div class="mb-2">
                                <label class="me-2">Qtd:</label>
                                <input type="number" 
                                       value="{{ $item->quantidade }}" 
                                       min="1" 
                                       class="form-control form-control-sm atualizar-quantidade" 
                                       data-id="{{ $item->id }}"
                                       style="width: 80px;">
                            </div>

                            <p class="fw-bold">
                                Subtotal: R$ 
                                <span id="subtotal-{{ $item->id }}">
                                    {{ number_format($item->subtotal, 2, ',', '.') }}
                                </span>
                            </p>

                            <button class="btn btn-sm btn-danger mt-auto"
                                    onclick="event.preventDefault(); document.getElementById('remover-{{ $item->id }}').submit();">
                                Remover
                            </button>

                            <form id="remover-{{ $item->id }}" 
                                  action="{{ route('carrinho.remover', $item->id) }}" 
                                  method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Total e Botão do Modal --}}
        <div class="mt-4">
            <h4>
                Total Geral: R$ 
                <span id="total-geral">{{ number_format($itens->sum('subtotal'), 2, ',', '.') }}</span>
            </h4>

            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#enderecoModal">
                Escolher Endereço de Entrega
            </button>
        </div>

        {{-- Modal de Endereço --}}
        <div class="modal fade" id="enderecoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Endereço de Entrega</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('carrinho.finalizar') }}" method="POST">
                            @csrf

                            {{-- Campos de Endereço --}}
                            <div class="mb-3">
                                <label for="rua" class="form-label">Rua</label>
                                <input type="text" name="rua" id="rua" class="form-control"
                                       value="{{ optional($endereco)->rua }}" placeholder="Rua">
                            </div>

                            <div class="mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" name="numero" id="numero" class="form-control"
                                       value="{{ optional($endereco)->numero }}" placeholder="Número">
                            </div>

                            <div class="mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control"
                                       value="{{ optional($endereco)->bairro }}" placeholder="Bairro">
                            </div>

                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control"
                                       value="{{ optional($endereco)->cidade }}" placeholder="Cidade">
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" name="estado" id="estado" class="form-control"
                                       value="{{ optional($endereco)->estado }}" placeholder="Estado">
                            </div>

                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" name="cep" id="cep" class="form-control"
                                       value="{{ optional($endereco)->cep }}" placeholder="CEP">
                            </div>

                            {{-- Botões --}}
                            <div class="d-flex justify-content-between mt-3">
                                @isset($endereco)
                                    <button type="submit" class="btn btn-success">
                                        Usar este endereço
                                    </button>
                                @endisset

                                <button type="button" id="btn-usar-outro" class="btn btn-primary">
                                    Usar outro endereço
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    Confirmar Endereço
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @else
        <p>Seu carrinho está vazio.</p>
        <a href="{{ route('catalogo') }}" class="btn btn-primary mt-3">Voltar ao Catálogo</a>
    @endif
</div>

{{-- Script de Atualização de Quantidade --}}
<script>
document.querySelectorAll('.atualizar-quantidade').forEach(input => {
    input.addEventListener('change', async (e) => {
        const id = e.target.dataset.id;
        const quantidade = e.target.value;

        try {
            const response = await fetch(`/carrinho/atualizar/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantidade })
            });

            const data = await response.json();

            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById(`subtotal-${id}`).textContent = data.subtotal;
            document.getElementById('total-geral').textContent = data.total;

        } catch (error) {
            alert('Erro ao atualizar quantidade.');
        }
    });
});

// Script para limpar campos do modal
document.getElementById('btn-usar-outro')?.addEventListener('click', function() {
    ['rua', 'numero', 'bairro', 'cidade', 'estado', 'cep', 'complemento'].forEach(id => {
        document.getElementById(id)?.value = '';
    });
});
</script>
@endsection
