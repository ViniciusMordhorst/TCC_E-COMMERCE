<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Produto;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller{
    // Verifica se o usuário está logado
    public function checkAuth()
    {
        if (!Auth::check() && !Session::has('usuario')) {
            redirect()->route('login.form')
                     ->with('error', 'Você precisa estar logado para acessar esta página.')
                     ->send();
            exit;
        }
        return true;
    
    }
      // Verifica se o usuário é admin
    public function checkAdmin()
    {
        $this->checkAuth(); // garante que está logado

        $user = Auth::user();
        if ((int)$user->tipo !== 1) {
            redirect()->route('home')
                     ->with('error', 'Acesso negado. Você não é administrador.')
                     ->send();
            exit;
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
        $this->checkAuth();
        $produtos = Produto::all();
        return view('home', compact('produtos'));
    }

    // Logout centralizado
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

    // Login centralizado
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string|min:6',
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'E-mail ou senha incorretos.',
            'senha.required' => 'E-mail ou senha incorretos.',
            'senha.incorreto' => 'A senha deve ter no mínimo 6 caracteres.',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            throw ValidationException::withMessages([
                'email' => ['E-mail ou senha incorretos.'],
            ]);
        }

        Auth::login($usuario);

        if ($usuario->tipo == 1) {
            return redirect()->route('dashboard')
                             ->with('success', 'Bem-vindo administrador!');
        }

        return redirect()->route('home')
                         ->with('success', 'Login realizado com sucesso!');
    }

    // Painel do admin (dashboard)
    public function painel()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')
                             ->with('error', 'Você precisa estar logado para acessar.');
        }

        $user = Auth::user();

        if ((int) $user->tipo !== 1) {
            return redirect()->route('home')
                             ->with('error', 'Acesso negado. Você não é um administrador.');
        }

        return view('dashboard', compact('user'));
    }

    // Futuro: recuperação de senha
    public function esqueceuSenha() {}

    // Futuro: redefinição de senha
    public function alterarSenha() {}
}
