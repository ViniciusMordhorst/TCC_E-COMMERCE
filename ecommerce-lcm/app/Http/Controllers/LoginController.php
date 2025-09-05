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
            'email.email' => 'Digite um e-mail válido.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.min' => 'A senha deve ter no mínimo 6 caracteres.',
        ]);

        // Buscar usuário
        $usuario = Usuario::where('email', $request->email)->first();

        // Verificar email e senha
        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            throw ValidationException::withMessages([
                'email' => ['E-mail ou senha incorretos.']
            ]);
        }

        // Autenticar manualmente
        Auth::login($usuario);

        // Redirecionar baseado no tipo
        if ($usuario->tipo == 1) { // admin
            return redirect()->route('dashboard')
                             ->with('success', 'Bem-vindo administrador!');
        }

        return redirect()->route('home')
                         ->with('success', 'Login realizado com sucesso!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form')
                         ->with('success', 'Logout realizado com sucesso!');
    }
}
