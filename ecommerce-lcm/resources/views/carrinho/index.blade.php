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

        {{-- Total e Botão --}}
        <div class="mt-4">
            <h4>
                Total Geral: R$ 
                <span id="total-geral">
                    {{ number_format($itens->sum('subtotal'), 2, ',', '.') }}
                </span>
            </h4>

            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('carrinho.endereco') }}" class="btn btn-success btn-lg">
                    Confirmar Endereço
                </a>
            </div>
        </div>

    @else
        <p>Seu carrinho está vazio.</p>
        <a href="{{ route('catalogo') }}" class="btn btn-primary mt-3">Voltar ao Catálogo</a>
    @endif
</div>

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

            // Atualiza subtotal e total na tela
            document.getElementById(`subtotal-${id}`).textContent = data.subtotal;
            document.getElementById('total-geral').textContent = data.total;

        } catch (error) {
            alert('Erro ao atualizar quantidade.');
        }
    });
});
</script>
@endsection
