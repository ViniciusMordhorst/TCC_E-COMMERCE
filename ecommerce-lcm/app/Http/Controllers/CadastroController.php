<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CadastroController extends Controller
{
    public function showForm()
    {
        return view('auth.cadastro');
    }

    public function store(Request $request)
    {
        // Validações
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6|confirmed',
            'cpf' => 'nullable|string|max:20|unique:usuarios,cpf',
            'telefone' => 'nullable|string|max:20',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais que 255 caracteres.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',

            'senha.required' => 'A senha é obrigatória.',
            'senha.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'senha.confirmed' => 'A confirmação da senha não confere.',

            'cpf.unique' => 'Este CPF já está cadastrado.',
            'cpf.max' => 'O CPF não pode ter mais que 20 caracteres.',

            'telefone.max' => 'O telefone não pode ter mais que 20 caracteres.',
        ]);

        try {
            $userId = DB::table('usuarios')->insertGetId([
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => Hash::make($request->senha),
                'tipo' => 0, // Cliente padrão
                'cpf' => $request->cpf ?? null,
                'telefone' => $request->telefone ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('login')->with('success', 'Usuário cadastrado com sucesso!');
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' => 'Erro ao cadastrar usuário. Tente novamente.'
            ]);
        }
    }
}
