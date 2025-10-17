@extends('layouts.app')

@section('title', 'Pedidos do Sistema')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

@section('content')
<div class="container mt-5">
    <h1>{{ $user->isAdmin() ? 'Todos os Pedidos' : 'Meus Pedidos' }}</h1>

    @if($pedidos->count() > 0)
        @foreach($pedidos as $pedido)
            <div class="card mb-4 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3>Pedido #{{ $pedido->id }}</h3>

                    @php
                        $status = strtolower($pedido->status);
                        $statusClass = match($status) {
                            'enviado' => 'badge bg-primary',
                            'processando' => 'badge bg-warning text-dark',
                            'cancelado' => 'badge bg-danger',
                            default => 'badge bg-secondary',
                        };
                    @endphp

                    <span class="{{ $statusClass }}">{{ ucfirst($pedido->status) }}</span>
                </div>

                <p><strong>Cliente:</strong> {{ $pedido->usuario->nome }} (CPF: {{ $pedido->usuario->cpf }})</p>

                @php $endereco = $pedido->usuario->endereco; @endphp
                @if($endereco)
                    <p><strong>Endereço:</strong> {{ $endereco->rua }}, {{ $endereco->numero }} - {{ $endereco->cidade }}/{{ $endereco->estado }}</p>
                @else
                    <p><strong>Endereço:</strong> Não cadastrado</p>
                @endif

                <ul class="list-group mb-2">
                    @foreach($pedido->itens as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item->produto->nome }} - {{ $item->quantidade }}x
                            <span>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

                <p><strong>Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</p>

                @if($user->isAdmin())
                    <form action="{{ route('pedidos.updateStatus', $pedido->id) }}" method="POST" class="mt-2 d-flex align-items-center">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select me-2" style="width: 200px;">
                            <option value="Pendente" {{ $pedido->status === 'Pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="Processando" {{ $pedido->status === 'Processando' ? 'selected' : '' }}>Processando</option>
                            <option value="Enviado" {{ $pedido->status === 'Enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="Cancelado" {{ $pedido->status === 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <p>{{ $user->isAdmin() ? 'Nenhum pedido registrado.' : 'Você não possui pedidos.' }}</p>
    @endif

    @if(!$user->isAdmin())
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Voltar à loja</a>
    @endif
</div>
@endsection
