@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow p-4">
        <h2 class="mb-4">Bem-vindo, {{ Auth::user()->nome }}!</h2>

        <p class="text-muted">Você está logado como <strong>{{ Auth::user()->email }}</strong>.</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger mt-3">Sair</button>
        </form>
    </div>
</div>
@endsection
