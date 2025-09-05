<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\LoginController;
use App\Models\Produto;


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

// Página inicial (Home)
Route::get('/', function () {
    $produtos = Produto::all(); // Mesmo que esteja vazio, não dará erro
    return view('home', compact('produtos'));
})->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'admin'])->name('dashboard');

