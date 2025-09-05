@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="mt-5">

    {{-- Mensagem de sessão ativa --}}
    @if(Auth::check())
        <div id="sessaoAtiva" 
             style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
                    background: rgba(0,0,0,0.8); color: #fff; padding: 15px 25px; border-radius: 10px;
                    display: flex; align-items: center; gap: 10px; z-index: 9999;">
            <span style="width: 15px; height: 15px; background-color: green; border-radius: 50%; display: inline-block;"></span>
            <span>Sessão ativa como <strong>{{ Auth::user()->nome }}</strong></span>
        </div>
    @endif

    <h2>Bem-vindo <strong>{{ Auth::user()->nome }}</strong>!</h2>

    {{-- Botão de Logout --}}
    <form action="{{ route('logout') }}" method="POST" class="mb-4">
        @csrf
        <button type="submit" class="btn btn-danger">Sair</button>
    </form>

    {{-- Lista de produtos --}}
    <h4>Produtos:</h4>
    @if(isset($produtos) && $produtos->isEmpty())
        <p>Nenhum produto cadastrado.</p>
    @else
        <ul>
            @foreach($produtos as $produto)
                <li>{{ $produto->nome ?? 'Produto sem nome' }}</li>
            @endforeach
        </ul>
    @endif
</div>

{{-- Script para remover a mensagem após 10 segundos --}}
<script>
    setTimeout(() => {
        const msg = document.getElementById('sessaoAtiva');
        if(msg) {
            msg.style.display = 'none';
        }
    }, 10000); // 10000ms = 10 segundos
</script>
@endsection
