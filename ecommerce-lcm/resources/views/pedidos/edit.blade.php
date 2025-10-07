@extends('layouts.app')

@section('title', 'Editar Pedido - Admin')

@section('content')
<h1>Editar Pedido #{{ $pedido->id }}</h1>

<form action="{{ route('admin.pedidos.update', $pedido->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select">
            @foreach(['Pendente','Processando','Enviado','Entregue','Cancelado'] as $status)
                <option value="{{ $status }}" {{ $pedido->status == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success">Atualizar</button>
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
