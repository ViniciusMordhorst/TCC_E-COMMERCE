
@extends('layouts.app')

@section('content')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <span class="badge bg-primary rounded-circle" style="width:42px;height:42px;display:inline-flex;align-items:center;justify-content:center;">
                                {{ strtoupper(substr(optional(Auth::user())->nome,0,1) ?? 'A') }}
                            </span>
                        </div>
                        <div>
                            <div class="fw-bold">{{ optional(Auth::user())->nome ?? 'Usuário' }}</div>
                            <small class="text-muted">{{ optional(Auth::user())->email ?? '' }}</small>
                        </div>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link py-2" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="nav-link py-2" href="{{ route('produtos.index') }}">Lista de Produtos</a>
                        <a class="nav-link py-2" href="{{ route('produtos.create') }}">Cadastrar Produto</a>
                    </nav>

                    <hr>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger w-100">Sair</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <main class="col-md-9">
            <div class="card shadow p-4">
                <h2 class="mb-3">Painel de Administração</h2>

                <p class="text-muted mb-4">Bem-vindo, <strong>{{ optional(Auth::user())->nome ?? 'Usuário' }}</strong>. Use o menu à esquerda para navegar.</p>

                {{-- Atalhos --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 position-relative">
                            <div class="text-muted small">Produtos</div>
                            <div class="h5 mt-2">Ver / Gerenciar</div>
                            <a href="{{ route('produtos.index') }}" class="stretched-link"></a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 position-relative">
                            <div class="text-muted small">Cadastrar</div>
                            <div class="h5 mt-2">Novo produto</div>
                            <a href="{{ route('produtos.create') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    @yield('dashboard-content')
                </div>
            </div>
        </main>
    </div>
</div>
@endsection