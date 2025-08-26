<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Supabase\SupabaseClient;

class AuthController extends Controller
{
    protected $supabase;

    public function __construct()
    {
        $this->supabase = new SupabaseClient(
            env('SUPABASE_URL'),
            env('SUPABASE_KEY')
        );
    }

    // =========================
    // Registro
    // =========================
    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'tipo' => 'required|string|max:50',
        ]);

        // Criar usuário no Supabase Auth
        $signup = $this->supabase->auth->signUp([
            'email' => $request->email,
            'password' => $request->senha
        ]);

        if (isset($signup['error'])) {
            return response()->json(['error' => $signup['error']['message']], 400);
        }

        // Criar registro no banco Laravel
        $userId = DB::table('usuarios')->insertGetId([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha), // bcrypt
            'tipo' => $request->tipo,
            'cpf' => $request->cpf ?? null,
            'telefone' => $request->telefone ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'user_id' => $userId
        ], 201);
    }

    // =========================
    // Login
    // =========================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        // Autenticar via Supabase
        $login = $this->supabase->auth->signIn([
            'email' => $request->email,
            'password' => $request->senha
        ]);

        if (isset($login['error'])) {
            return response()->json(['error' => $login['error']['message']], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'user' => $login['data']['user'] ?? null,
            'session' => $login['data']['session'] ?? null
        ], 200);
    }

    // =========================
    // Logout
    // =========================
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token não fornecido'], 401);
        }

        $signOut = $this->supabase->auth->signOut($token);

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }
}
