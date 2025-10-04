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

// Página de catálogo (para clientes)
Route::get('/catalogo', [ProdutoController::class, 'catalogo'])->name('produtos.catalogo');








// Carrinho
Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');

// Adicionar item (POST, porque cria ou altera dados)
Route::post('/carrinho/adicionar/{produtoId}', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');

// Atualizar quantidade (PUT)
Route::put('/carrinho/atualizar/{itemId}', [CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');

// Remover item (DELETE)
Route::delete('/carrinho/remover/{itemId}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');

// Checkout (GET)
Route::get('/carrinho/checkout', [CarrinhoController::class, 'checkout'])->name('carrinho.checkout');

// Finalizar pedido (POST)
Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalizar'])->name('carrinho.finalizar');

// Página de sucesso/confirmacao (GET)
Route::get('/carrinho/sucesso/{pedidoId}', [CarrinhoController::class, 'sucesso'])->name('carrinho.sucesso');
