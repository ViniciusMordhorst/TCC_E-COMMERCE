<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PagamentoController;

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

// Página de endereço (dentro da área do carrinho)
Route::get('/carrinho/endereco', [CarrinhoController::class, 'endereco'])->name('carrinho.endereco');
Route::post('/carrinho/endereco', [CarrinhoController::class, 'salvarEndereco'])->name('carrinho.salvarEndereco');



Route::get('/carrinho/sucesso/{pedidoId}', [CarrinhoController::class, 'sucesso'])->name('carrinho.sucesso');





// Admin vê todos os pedidos

// Página de pedidos (admin e usuário comum)
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');

// Atualizar status do pedido (apenas admin)
Route::patch('/pedidos/{id}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');



    // Criar checkout e redirecionar para PagBank
    Route::post('/pagamento', [PagamentoController::class, 'checkout'])->name('pagamento.criar');

    // URLs de retorno
    Route::get('/pagamentos/sucesso', [PagamentoController::class, 'sucesso'])->name('pagamentos.sucesso');
    Route::get('/pagamentos/erro', [PagamentoController::class, 'erro'])->name('pagamentos.erro');

    // Notificação do PagBank (POST)
    Route::post('/pagamentos/notificacao', [PagamentoController::class, 'notificacao'])->name('pagamentos.notificacao');
