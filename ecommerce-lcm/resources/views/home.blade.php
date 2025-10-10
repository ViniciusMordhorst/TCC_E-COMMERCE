@extends('layouts.app')

@section('title', 'Home')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">


@section('content')
<div class="mt-5">

    {{-- Mensagens --}}
    @if(session('error1'))
        <div class="alert alert-danger">{{ session('error1') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Boas-vindas --}}
    @auth
        <div id="bemVindo" class="alert alert-info text-center">
            Bem-vindo <strong>{{ Auth::user()->nome }}</strong>!
        </div>
    @endauth

    {{-- Seção inicial --}}
    <section class="aside text-center py-5 mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Couro Artesanal</h1>
            <p class="lead mb-4">
                Descubra nossa coleção cuidadosamente selecionada de produtos artesanais em couro de alta qualidade.
            </p>
            <a href="{{ route('catalogo') }}" class="btn btn-light btn-lg me-2">Comprar Agora</a>
            <a href="#features" class="btn btn-outline-light btn-lg">Saiba Mais</a>
        </div>
    </section>

    {{-- Benefícios --}}
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-2">🚚</div>
                    <h5>Frete Grátis</h5>
                    <p>Entrega gratuita em pedidos acima de R$ 200</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-2">🛡️</div>
                    <h5>Pagamento Seguro</h5>
                    <p>Checkout 100% seguro garantido</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-2">⭐</div>
                    <h5>Qualidade Artesanal</h5>
                    <p>Apenas os melhores produtos artesanais em couro</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Produtos em Destaque --}}
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Produtos em Destaque</h2>
                <p class="text-muted">Seleção especial dos nossos itens mais populares</p>
            </div>

            @if($produtos->isEmpty())
                <p>Nenhum produto cadastrado.</p>
            @else
                <div class="row">
                    @foreach($produtos as $produto)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                {{-- Imagem clicável --}}
                                <a href="{{ route('produtos.show', $produto->id) }}">
                                    <img src="{{ $produto->imagem ?? asset('images/placeholder.png') }}" 
                                         class="card-img-top" 
                                         alt="{{ $produto->nome }}">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $produto->nome }}</h5>
                                    <p class="card-text text-success fw-bold">
                                        R$ {{ number_format($produto->preco ?? 0, 2, ',', '.') }}
                                    </p>

                                    {{-- Botão carrinho --}}
                                    <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">Adicionar ao Carrinho</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Chamada final --}}
    <section class="cta text-center text-white py-5 bg-dark">
        <div class="container">
            <h2 class="fw-bold mb-3">Pronto para Experimentar a Qualidade Premium?</h2>
            <p class="mb-4">Junte-se a milhares de clientes satisfeitos que confiam em nós para suas necessidades em couro artesanal.</p>
            <a href="{{ route('login.form') }}" class="btn btn-light btn-lg">Comece Hoje Mesmo</a>
        </div>
    </section>
</div>
@endsection
