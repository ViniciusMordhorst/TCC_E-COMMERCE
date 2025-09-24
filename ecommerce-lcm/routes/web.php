<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\LoginController;
use App\Models\Produto;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;


// Página inicial
Route::get('/', function () {
    return view('welcome');
});


// Rotas de Autenticação




// Cadastro
Route::get('/cadastro', [CadastroController::class, 'showForm'])->name('cadastro.form');
Route::post('/cadastro', [CadastroController::class, 'store'])->name('cadastro.store');

// Login
Route::get('/login', [LoginController::class, 'showForm'])->name('login.form');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home
Route::get('/home', [ProdutoController::class, 'home'])->name('home');

// Dashboard - chamar método do controller diretamente (verificação no controller)
Route::get('/dashboard', [LoginController::class, 'painel'])->name('dashboard');

//Produtos
Route::resource('produtos', ProdutoController::class)->except(['show']);
Route::get('produtos/{id}', [ProdutoController::class,'show'])->name('produtos.show');



Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho');
Route::get('/carrinho/adicionar/{id}', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
