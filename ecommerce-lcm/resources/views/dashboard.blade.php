@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bem-vindo, {{ Auth::user()->nome }}!</h2>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Sair</button>
    </form>
</div>
@endsection
