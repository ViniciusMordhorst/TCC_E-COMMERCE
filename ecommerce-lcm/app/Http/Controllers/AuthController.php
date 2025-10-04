<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Produto;

class AuthController extends Controller
{
    // Verifica se o usuário está logado
    public function checkAuth()
    {
        if (!Auth::check() && !Session::has('usuario')) {
            redirect()->route('login.form')
                     ->with('error', 'Você precisa estar logado para acessar esta página.')
                     ->send(); // envia o redirect imediatamente
            exit; // interrompe a execução do método que chamou checkAuth
        }
        return true;
    }

    // Retorna usuário autenticado
    public function retornaUsuario()
    {
        if (Auth::check()) {
            return response()->json(Auth::user());
        }
        if (!Session::has('usuario')) {
            return response()->json(['error' => 'Não autenticado.'], 401);
        }
        return response()->json(Session::get('usuario'));
    }

    // Home (usuários comuns)
    public function home()
    {
        $this->checkAuth(); // garante que o usuário esteja logado

        $produtos = Produto::all();
        return view('home', compact('produtos'));
    }

    // Logout
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        } else {
            Session::forget('usuario');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')
                         ->with('success', 'Logout realizado com sucesso!');
    }

    // Futuro: recuperação de senha
    public function esqueceuSenha() {}

    // Futuro: redefinição de senha
    public function alterarSenha() {}
}
