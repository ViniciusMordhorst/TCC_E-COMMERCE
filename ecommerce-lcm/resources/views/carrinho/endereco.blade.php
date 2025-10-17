@extends('layouts.app')

@section('title', 'Endereço de Entrega')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4 text-center">Endereço de Entrega</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('pagamento.criar') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Rua</label>
                    <input type="text" name="rua" class="form-control" value="{{ old('rua', optional($endereco)->rua) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Número</label>
                    <input type="text" name="numero" class="form-control" value="{{ old('numero', optional($endereco)->numero) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bairro</label>
                    <input type="text" name="bairro" class="form-control" value="{{ old('bairro', optional($endereco)->bairro) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cidade</label>
                    <input type="text" name="cidade" class="form-control" value="{{ old('cidade', optional($endereco)->cidade) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <input type="text" name="estado" class="form-control" value="{{ old('estado', optional($endereco)->estado) }}" maxlength="2" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">CEP</label>
                    <input type="text" name="cep" class="form-control" value="{{ old('cep', optional($endereco)->cep) }}" required>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('carrinho.index') }}" class="btn btn-outline-secondary">Voltar ao Carrinho</a>
                <button type="submit" class="btn btn-success">
                    {{ $endereco ? 'Atualizar e Ir para Pagamento' : 'Cadastrar e Ir para Pagamento' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
