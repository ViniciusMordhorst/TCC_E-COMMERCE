<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
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
        $usuario = DB::table('usuarios')->where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha inválidos.'
            ]);
        }

        // Criar sessão
        Session::put('usuario', [
            'id' => $usuario->id,
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'tipo' => $usuario->tipo,
        ]);

        return redirect()->route('dashboard')->with('success', 'Login realizado com sucesso!');
    }
}
