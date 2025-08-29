<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// =========================
// Rotas de Autenticação
// =========================

// Cadastro
Route::get('/cadastro', [AuthController::class, 'showCadastroForm'])->name('cadastro.form');
Route::post('/cadastro', [AuthController::class, 'cadastro'])->name('cadastro');


// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

