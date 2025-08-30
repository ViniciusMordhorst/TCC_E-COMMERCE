<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Verifica se o usuário está logado
    public function checkAuth()
    {
        if (!Session::has('usuario')) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }
        return true;
    }

    // Retorna usuário autenticado
    public function me()
    {
        if (!Session::has('usuario')) {
            return response()->json(['error' => 'Não autenticado.'], 401);
        }
        return response()->json(Session::get('usuario'));
    }

    // Logout
    public function logout()
    {
        Session::forget('usuario');
        return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
    }

    // Futuro: recuperação de senha
    public function forgotPassword()
    {
        // Enviar e-mail de recuperação
    }

    // Futuro: redefinição de senha
    public function resetPassword()
    {
        // Implementar redefinição
    }
}
