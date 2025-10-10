<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;

// Página inicial / Home
Route::get('/', [ProdutoController::class, 'home'])->name('home');
Route::get('/home', [ProdutoController::class, 'home'])->name('home');

// Cadastro
Route::get('/cadastro', [CadastroController::class, 'showForm'])->name('cadastro.form');
Route::post('/cadastro', [CadastroController::class, 'store'])->name('cadastro.store');

// Login (formulário)
Route::get('/login', [LoginController::class, 'showForm'])->name('login.form');

// Login (envio do formulário) - agora no AuthController
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout (centralizado no AuthController)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard admin (verificação feita no AuthController)
Route::get('/dashboard', [AuthController::class, 'painel'])->name('dashboard');

// Produtos (CRUD)
Route::resource('produtos', ProdutoController::class)->except(['show']);
Route::get('produtos/{id}', [ProdutoController::class,'show'])->name('produtos.show');

// Catálogo público
Route::get('/catalogo', [ProdutoController::class, 'catalogo'])->name('catalogo');

// Carrinho
Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/adicionar/{produtoId}', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::put('/carrinho/atualizar/{itemId}', [CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
Route::delete('/carrinho/remover/{itemId}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
Route::get('/carrinho/checkout', [CarrinhoController::class, 'checkout'])->name('carrinho.checkout');
Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalizar'])->name('carrinho.finalizar');
Route::get('/carrinho/sucesso/{pedidoId}', [CarrinhoController::class, 'sucesso'])->name('carrinho.sucesso');





// Admin vê todos os pedidos

// Página de pedidos (admin e usuário comum)
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');

// Atualizar status do pedido (apenas admin)
Route::patch('/pedidos/{id}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');




Route::get('/pagamento/{pedidoId}', [PagamentoController::class, 'pagar'])->name('pagamento.pagar');
Route::get('/pagamento/retorno/{pedidoId}', [PagamentoController::class, 'retorno'])->name('pagamento.retorno');