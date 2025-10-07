@extends('layouts.app')

@section('content')
<h1>Pedidos</h1>

@if($pedidos->count() > 0)
    @foreach($pedidos as $pedido)
        <div class="card mb-3 p-3 shadow-sm">
            <h5>Pedido #{{ $pedido->id }} - Status: {{ $pedido->status }}</h5>
            <ul>
                @foreach($pedido->itens as $item)
                    <li>{{ $item->produto->nome }} - {{ $item->quantidade }}x - R$ {{ number_format($item->subtotal, 2, ',', '.') }}</li>
                @endforeach
            </ul>
            <p><strong>Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</p>
            <p><strong>Endereço:</strong> {{ $pedido->endereco->rua }}, {{ $pedido->endereco->numero }} - {{ $pedido->endereco->cidade }}/{{ $pedido->endereco->estado }}</p>

            {{-- Apenas admin pode alterar status --}}
            @if(Auth::user()->tipo === 1)
                <form action="{{ route('pedidos.updateStatus', $pedido->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm d-inline w-auto">
                        <option value="Pendente" @if($pedido->status === 'Pendente') selected @endif>Pendente</option>
                        <option value="Processando" @if($pedido->status === 'Processando') selected @endif>Processando</option>
                        <option value="Feito" @if($pedido->status === 'Feito') selected @endif>Feito</option>
                        <option value="Cancelado" @if($pedido->status === 'Cancelado') selected @endif>Cancelado</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                </form>
            @endif
        </div>
    @endforeach
@else
    <p>Não há pedidos para exibir.</p>
@endif

<a href="{{ route('home') }}" class="btn btn-secondary mt-3">Voltar à loja</a>
@endsection
