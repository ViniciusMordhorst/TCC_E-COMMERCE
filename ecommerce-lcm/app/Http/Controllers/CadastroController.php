<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;

class CadastroController extends Controller
{
    // Mostra o formulário de cadastro
    public function showForm()
    {
        return view('auth.cadastro');
    }

    // Salva o usuário no banco
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

        // Criar usuário com Eloquent
        $usuario = new Usuario();
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->senha = Hash::make($request->senha);
        $usuario->tipo = 0; // sempre cliente
        $usuario->cpf = $request->cpf ?? null;
        $usuario->telefone = $request->telefone ?? null;
        $usuario->save();

        // Redirecionar para login com mensagem de sucesso
        return redirect()->route('login.form')
                         ->with('success', 'Usuário cadastrado com sucesso!');
    }
}
