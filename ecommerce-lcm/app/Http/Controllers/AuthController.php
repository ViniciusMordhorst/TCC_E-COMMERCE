<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // =========================
    // Formulário de Cadastro
    // =========================
    public function showCadastroForm()
    {
        return view('auth.cadastro');
    }

    // =========================
    // Cadastro de Usuário Cliente
    // =========================
    public function cadastro(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6|confirmed',
            'cpf' => 'nullable|string|max:20',
            'telefone' => 'nullable|string|max:20',
        ]);

        $userId = DB::table('usuarios')->insertGetId([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->password), // hash da senha
            'tipo' => 0, // sempre cliente
            'cpf' => $request->cpf ?? null,
            'telefone' => $request->telefone ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        return view('auth.cadastro', [
            'success' => 'Usuário cadastrado com sucesso!',
            'userId' => $userId
        ]);
    }
 
}
