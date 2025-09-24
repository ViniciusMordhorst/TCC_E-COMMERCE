<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validação
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string|min:6',
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.required' => 'E-mail ou senha incorretos.',
            'email.email' => 'E-mail ou senha incorretos.',
            'senha.required' => 'E-mail ou senha incorretos.',
            'senha.incorreto' => 'A senha deve ter no mínimo 6 caracteres.',
        ]);

        // Buscar usuário
        $usuario = Usuario::where('email', $request->email)->first();

        // Verificar email e senha
        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            throw ValidationException::withMessages([
                'email' => ['E-mail ou senha incorretos.'],
              
            ]);
        }

      // Autenticar com o guard padrão
        Auth::login($usuario);

        // Redirecionar baseado no tipo
        if ($usuario->tipo == 1) { // admin
            return redirect()->route('dashboard')
                             ->with('success', 'Bem-vindo administrador!');
        }

        return redirect()->route('home')
                         ->with('success', 'Login realizado com sucesso!');
    }

// Logout
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.form')
                         ->with('success', 'Logout realizado com sucesso!');
    }
//Painel do admin (dashboard) — verificação manual sem middleware
    public function painel()
    {
        // se não autenticado, direciona ao login
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Você precisa estar logado para acessar.');
           
        }

        $user = Auth::user();

        // se autenticado mas não for admin
        if ((int) $user->tipo !== 1) {
            return redirect()->route('home')->with('error', 'Acesso negado. Você não é um administrador.');
        }

        return view('dashboard', compact('user'));
    }

  

}
