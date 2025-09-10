<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\LoginController;
use App\Models\Produto;
use App\Http\Controllers\ProdutoController;



// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// =========================
// Rotas de Autenticação
// =========================



// Cadastro
Route::get('/cadastro', [CadastroController::class, 'showForm'])->name('cadastro.form');
Route::post('/cadastro', [CadastroController::class, 'store'])->name('cadastro.store');

// Login
Route::get('/login', [LoginController::class, 'showForm'])->name('login.form');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    if (!Auth::check()) {
        return redirect()->route('login.form')
            ->with('error', 'Você precisa estar logado para acessar essa página.');
    }

    $produtos = Produto::all();
    return view('home', compact('produtos'));
})->name('home');

// Dashboard: rota protegida por auth; somente admin (verificação no controller)
Route::get('/dashboard', [LoginController::class, 'painel'])
    ->middleware('auth')
    ->name('dashboard');