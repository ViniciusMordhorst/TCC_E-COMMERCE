<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Verifica se o usuário está logado
    public function checkAuth(Request $request)
    {
       if (!Auth::check() && !Session::has('usuario')) {
            return redirect()->route('login.form')->with('error', 'Você precisa estar logado para acessar esta página.');
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
    

    // Logout
       public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login.form')->with('success', 'Logout realizado com sucesso!');
        }

        // Fallback para sessão custom
        Session::forget('usuario');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form')->with('success', 'Logout realizado com sucesso!');
    }

    
    // Futuro: recuperação de senha
    public function esqueceuSenha()
    {
        // Enviar e-mail de recuperação
    }

    // Futuro: redefinição de senha
    public function alterarSenha()
    {
        // Implementar redefinição
    }
}
